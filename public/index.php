<?php

// Set a default timezone for all date functions
date_default_timezone_set('UTC');

// Start the session for handling user login state and flash messages
session_start();

// Define a constant for the project root directory
define('ROOT_PATH', dirname(__DIR__));

// 1. Use Composer's Autoloader (Modern & Standard)
// This handles loading all classes automatically.
require_once ROOT_PATH . '/vendor/autoload.php';

// 2. Load Environment Variables from the .env file
// This makes configuration secure and flexible.
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// 3. Set Error Reporting based on the Environment
// Shows detailed errors in development, but hides them in production.
if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// 4. Initialize Core App Components
// Get the single database wrapper instance (Singleton Pattern).
$db = App\Core\Database::getInstance();
// Create the router and pass the database connection to it (Dependency Injection).
$router = new App\Core\Router($db);


// =================================================================
//  ROUTE DEFINITIONS
// =================================================================

// --- PUBLIC ROUTES (for guests and specific actions) ---

// The 'guest' middleware redirects to '/' if the user is already logged in.
$router->group(['middleware' => 'guest'], function($router) {
    $router->get('/login', 'AuthController@showLogin');
    $router->post('/login', 'AuthController@login');
    $router->get('/register', 'AuthController@showRegister');
    $router->post('/register', 'AuthController@register');
});

// These must be public so users can receive the email and click the link.
$router->get('/verify-email', 'AuthController@verifyEmail'); // Expects ?token=...
$router->get('/forgot-password', 'PasswordResetController@showRequestForm');
$router->post('/forgot-password', 'PasswordResetController@handleRequest');
$router->get('/reset-password', 'PasswordResetController@showResetForm'); // Expects ?token=...
$router->post('/reset-password', 'PasswordResetController@handleReset');


// --- PROTECTED ROUTES (for logged-in users) ---

// The 'auth' middleware protects all routes in this group.
// It will redirect any guest trying to access them to the '/login' page.
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/', 'DashboardController@home');
    $router->get('/profile', 'UserController@profile');
    $router->post('/profile', 'UserController@updateProfile');
    $router->get('/logout', 'AuthController@logout');
    $router->post('/profile/password', 'UserController@updatePassword');
    $router->get('/logout', 'AuthController@logout');
});


// =================================================================
//  DISPATCH THE ROUTER
// =================================================================
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    // Render a user-friendly error page in production, or a detailed one in debug mode.
    http_response_code(500);
    if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true') {
        // In development, show the detailed error.
        echo "<h1>Fatal Error</h1>";
        echo "<pre><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<pre><strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "</pre>";
        echo "<pre><strong>Stack Trace:</strong><br>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        // In production, show a generic error page.
        $view = new App\Core\View('errors/500');
        $view->renderPartial();
    }
}