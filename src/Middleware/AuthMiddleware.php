<?php

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Handle the incoming request.
     *
     * This middleware checks if a user is logged in. If not, it redirects
     * them to the login page and stops the script.
     */
    public function handle(): void
    {
        // If the 'user_id' session variable is NOT set, the user is not logged in.
        if (!isset($_SESSION['user_id'])) {
            // Redirect to the login page.
            header('Location: /login');
            // Stop the script from executing any further.
            exit();
        }
    }
}