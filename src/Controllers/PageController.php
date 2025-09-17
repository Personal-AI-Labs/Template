<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;

/**
 * Handles requests for static pages like the homepage.
 */
class PageController extends Controller
{
    /**
     * PageController constructor.
     * Receives the database connection from the Router, even if not used,
     * to maintain a consistent structure with other controllers.
     */
    public function __construct($db)
    {
        // We receive the $db instance but may not need to use it for simple pages.
    }

    /**
     * Renders the home page.
     */
    public function home()
    {
        // Data to pass to the view
        $data = [
            'title' => 'Welcome to the Template'
        ];

        // Create a new View instance and render it
        $view = new View('home', $data);
        $view->render();
    }
}