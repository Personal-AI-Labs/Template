<?php

namespace App\Controllers;

// 1. Use statements for namespacing and autoloading
use App\Core\Controller;
use App\Core\View;
use App\Models\Settings;
use App\Models\User;
use Exception;

/**
 * Handles user authentication: login, registration, logout, and email verification.
 */
class AuthController extends Controller
{
    /** @var User The user model instance. */
    private User $userModel;

    /**
     * AuthController constructor.
     * 2. UPDATED: Receives the database connection via Dependency Injection from the Router.
     *
     * @param mixed $db The database connection instance.
     */
    public function __construct($db)
    {
        // We no longer need parent::__construct() for the database connection.
        parent::__construct($db);
        $this->userModel = new User($db);
    }

    /**
     * Displays the login form view.
     */
    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $view = new View('auth/login', ['title' => 'Login']);
        $view->render();
    }

    /**
     * Processes the login form submission.
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && $this->userModel->verifyPassword($password, $user->password)) {

            $settingsModel = new Settings($this->db);
            $settings = $settingsModel->getAllSettings();

            if (($settings['maintenance_mode'] ?? 'false') === 'true' && $user->role !== 'admin') {
                $this->setFlashMessage('error', 'Site is currently in maintenance. Login for non-admins is disabled.');
                $this->redirect('/login');
                return; // Stop execution
            }

            if ($user->email_verified_at === null) {
                $this->setFlashMessage('error', 'Please verify your email before logging in.');
                $this->redirect('/login');
                return;
            }

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_role'] = $user->role;

            $this->setFlashMessage('success', 'Welcome back!');
            $this->redirect('/');
        } else {
            $this->setFlashMessage('error', 'Invalid email or password.');
            $this->redirect('/login');
        }
    }

    /**
     * Displays the registration form view.
     */
    public function showRegister()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $view = new View('auth/register', ['title' => 'Register']);
        $view->render();
    }

    /**
     * Processes the registration form submission.
     */
    public function register()
    {
        // (Your existing registration logic was excellent and remains unchanged)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
        ];

        // Basic validation... in a real app, you'd use a more robust validator
        if (empty($data['email']) || empty($data['password']) || empty($data['first_name'])) {
            $this->setFlashMessage('error', 'Please fill out all required fields.');
            $this->redirect('/register');
            return;
        }

        if ($this->userModel->findByEmail($data['email'])) {
            $this->setFlashMessage('error', 'This email address is already registered.');
            $this->redirect('/register');
            return;
        }

        try {
            $userId = $this->userModel->create($data);
            if ($userId) {
                // In a real application, you would send a verification email here.
                // For this template, we'll just show a success message.
                $this->setFlashMessage('success', 'Registration successful! Please check your email to verify your account.');
                $this->redirect('/login');
            } else {
                throw new Exception('Failed to create user account.');
            }
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'An error occurred during registration. Please try again.');
            $this->redirect('/register');
        }
    }

    /**
     * 3. NEW: Handles the email verification link.
     */
    public function verifyEmail()
    {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            $this->redirect('/'); // Or to an error page
            return;
        }

        if ($this->userModel->verifyEmailByToken($token)) {
            $this->setFlashMessage('success', 'Your email has been verified! You can now log in.');
        } else {
            $this->setFlashMessage('error', 'Invalid or expired verification token.');
        }

        $this->redirect('/login');
    }

    /**
     * Destroys the user's session and logs them out.
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}