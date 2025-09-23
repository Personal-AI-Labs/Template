<?php

namespace App\Middleware;

class MaintenanceMiddleware
{
    /**
     * Handles the request. If in maintenance mode, it shows the maintenance
     * page to non-admins and stops the application.
     *
     * @param array $settings The global site settings.
     * @param ?object $user The currently logged-in user, or null.
     * @param string $uri The requested page URI (e.g., '/login').
     * @return void
     */
    public function handle(array $settings, ?object $user, string $uri): void
    {
        if (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] === 'true') {

            // ADD THIS: Always allow access to the login page to prevent lock-out.
            if ($uri === '/login') {
                return;
            }

            // Allow admins to bypass maintenance mode on all other pages
            if (isset($user) && $user->role === 'admin') {
                return; // Admin can proceed.
            }

            // For everyone else, show the maintenance page and stop execution.
            http_response_code(503); // Service Unavailable
            require_once ROOT_PATH . '/public/views/maintenance.php';
            exit();
        }
    }
}