<?php

namespace App\Core;

use Exception;

class Router {
    private array $routes = [];
    private $db; // To hold the database connection for dependency injection
    private array $groupAttributes = []; // To hold middleware for a group

    /**
     * Router constructor.
     * We now pass in the database connection.
     *
     * @param mixed $db The database connection instance.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    public function get($pattern, $handler) {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post($pattern, $handler) {
        $this->addRoute('POST', $pattern, $handler);
    }

    // You can add put(), delete(), etc. here if needed

    /**
     * NEW: The group() method.
     *
     * @param array $attributes Attributes for the group (e.g., ['middleware' => 'auth']).
     * @param callable $closure The function containing the route definitions.
     */
    public function group(array $attributes, callable $closure) {
        // Store the current group attributes in case of nested groups
        $parentGroupAttributes = $this->groupAttributes;
        // Set the new attributes for the routes defined inside the closure
        $this->groupAttributes = array_merge($parentGroupAttributes, $attributes);

        // Call the closure, which will define the routes inside the group
        call_user_func($closure, $this);

        // IMPORTANT: Restore the parent attributes to not affect subsequent routes
        $this->groupAttributes = $parentGroupAttributes;
    }

    /**
     * UPDATED: addRoute now checks for group attributes like middleware.
     */
    private function addRoute($method, $pattern, $handler) {
        $route = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler
        ];
        // If we are inside a group, merge the group's attributes (like middleware)
        $this->routes[] = array_merge($route, $this->groupAttributes);
    }

    /**
     * UPDATED: dispatch now handles middleware.
     */
    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = '#^' . $route['pattern'] . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove the full match

                // NEW: Handle middleware before calling the controller
                $this->handleMiddleware($route);

                return $this->callHandler($route['handler'], $matches);
            }
        }
        $this->handleNotFound();
    }

    /**
     * NEW: Instantiates and runs the middleware for a route.
     *
     * @param array $route The matched route array.
     * @throws Exception if middleware class or handle method not found.
     */
    private function handleMiddleware(array $route) {
        if (!isset($route['middleware'])) {
            return; // No middleware to run
        }

        $middlewareName = $route['middleware'];
        // Assumes middleware classes are in App\Middleware namespace
        $middlewareClass = 'App\\Middleware\\' . ucfirst($middlewareName) . 'Middleware';

        if (!class_exists($middlewareClass)) {
            throw new Exception("Middleware class {$middlewareClass} not found.");
        }

        $middleware = new $middlewareClass();

        if (!method_exists($middleware, 'handle')) {
            throw new Exception("Handle method not found in middleware {$middlewareClass}.");
        }

        // Execute the middleware's handle method
        $middleware->handle();
    }

    /**
     * UPDATED: callHandler now passes the DB connection to controllers.
     */
    private function callHandler($handler, $params = []) {
        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controllerName, $method] = explode('@', $handler);
            // Assumes controller classes are in App\Controllers namespace
            $controllerClass = 'App\\Controllers\\' . $controllerName;

            if (!class_exists($controllerClass)) {
                throw new Exception("Controller {$controllerClass} not found");
            }

            // UPDATED: Pass the database connection to the controller's constructor
            $controller = new $controllerClass($this->db);

            if (!method_exists($controller, $method)) {
                throw new Exception("Method {$method} not found in {$controllerClass}");
            }

            return call_user_func_array([$controller, $method], $params);
        }

        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        throw new Exception("Invalid route handler");
    }

    private function handleNotFound() {
        http_response_code(404);
        // Assuming you have a 404 view file
        $view = new View('errors/404', ['title' => 'Page Not Found']);
        $view->render();
    }
}