<?php

namespace App\Core;

use App\Models\User;
use App\Models\Settings;
use JetBrains\PhpStorm\NoReturn;

/**
 * The base Controller class.
 * Provides common helper methods for all controllers.
 */
abstract class Controller
{
    /**
     * The database connection instance.
     * @var mixed
     */
    protected $db;

    /**
     * The currently logged-in user object, or null if no one is logged in.
     * @var object|null
     */
    protected ?object $user = null;

    /**
     * @var array Holds all site settings.
     */
    protected array $settings = [];

    /**
     * This now runs for any controller that extends this class. It handles
     * fetching the authenticated user automatically.
     *
     * @param mixed $db The database wrapper instance, passed from the Router.
     */
    public function __construct($db)
    {
        $this->db = $db;

        // If a user is logged in, fetch their full user object
        if ($this->isLoggedIn()) {
            $userModel = new User($this->db);
            $this->user = $userModel->findById($_SESSION['user_id']);
        }

        $settingsModel = new Settings($this->db);
        $this->settings = $settingsModel->getAllSettings();
    }

    /**
     * This automatically passes the user object to every view,
     * so you don't have to do it manually in every controller method.
     *
     * @param string $viewName The name of the view file (e.g., 'home').
     * @param array $data Optional data to pass to the view.
     * @throws \Exception
     */
    protected function render(string $viewName, array $data = []): void
    {
        $viewData = array_merge($data, [
            'user' => $this->user,
            'settings' => $this->settings
        ]);

        $view = new View($viewName, $viewData);
        $view->render();
    }

    /**
     * Helper method to redirect to a different page.
     *
     * @param string $url The URL to redirect to.
     */
    #[NoReturn] protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Helper method to return a JSON response.
     *
     * @param mixed $data The data to encode as JSON.
     * @param int $statusCode The HTTP status code.
     */
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Helper method to set a flash message in the session.
     *
     * @param string $type The message type (e.g., 'success', 'error').
     * @param string $message The message content.
     */
    protected function setFlashMessage(string $type, string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Helper method to check if a user is currently logged in.
     *
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }
}