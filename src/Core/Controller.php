<?php

namespace App\Core;

/**
 * The base Controller class.
 * Provides common helper methods for all controllers.
 */
abstract class Controller
{
    /**
     * Helper method to redirect to a different page.
     *
     * @param string $url The URL to redirect to.
     */
    protected function redirect(string $url): void
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
        // Ensure flash session array exists
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