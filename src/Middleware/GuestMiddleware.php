<?php

namespace App\Middleware;

class GuestMiddleware
{
    /**
     * Handle the incoming request.
     *
     * This middleware checks if a user is already logged in. If they are,
     * it redirects them away from guest-only pages (like login/register)
     * to the main application homepage.
     */
    public function handle(): void
    {
        // If the 'user_id' session variable IS set, the user is already logged in.
        if (isset($_SESSION['user_id'])) {
            // Redirect to the homepage.
            header('Location: /');
            // Stop the script from executing any further.
            exit();
        }
    }
}