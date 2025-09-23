<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Handles requests for static pages like the homepage.
 */
class DashboardController extends Controller
{

    /**
     * DashboardController constructor.
     * Receives the database connection and instantiates the User model.
     */
    public function __construct($db)
    {
        parent::__construct($db);
    }

    /**
     * Renders the home page.
     */
    public function home()
    {
        // Data to pass to the view
        $data = [
            'title' => 'Dashboard',
        ];

        // Create a new View instance and render it
        $this->render('home', [
            'title' => 'Dashboard'
        ]);
    }
}