<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\User;

class UserController extends Controller
{
    private User $userModel;

    /**
     * UserController constructor.
     * Receives the database connection and instantiates the User model.
     *
     * @param mixed $db The database wrapper instance.
     */
    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    /**
     * NEW: Show the main dashboard for a logged-in user.
     */
    public function home()
    {
        // Middleware has already confirmed the user is logged in.
        $user = $this->userModel->findById($_SESSION['user_id']);

        $view = new View('home', [
            'title' => 'Home',
            'user' => $user
        ]);
        $view->render();
    }

    /**
     * Show the current user's profile page.
     */
    public function profile()
    {
        // Middleware has already confirmed the user is logged in.
        $user = $this->userModel->findById($_SESSION['user_id']);

        $view = new View('users/profile', [
            'title' => 'My Profile',
            'user' => $user
        ]);
        $view->render();
    }

    /**
     * Handle updates to the user's profile information.
     */
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        $userId = $_SESSION['user_id'];
        $currentUser = $this->userModel->findById($userId);

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];

        // --- Validation (In a real app, use a dedicated Validator class) ---
        $errors = [];
        if (empty($data['first_name'])) $errors['first_name'][] = "First name is required.";
        if (empty($data['last_name'])) $errors['last_name'][] = "Last name is required.";
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'][] = "A valid email is required.";

        // Check if email is being changed to one that already exists
        if ($data['email'] !== $currentUser['email'] && $this->userModel->findByEmail($data['email'])) {
            $errors['email'][] = 'This email address is already in use.';
        }

        if (!empty($errors)) {
            // If there are errors, re-render the profile view with errors and old input
            $view = new View('users/profile', [
                'title' => 'My Profile',
                'user' => $currentUser, // The original user data
                'errors' => $errors,     // The validation errors
                'input' => $data         // The submitted form data to repopulate fields
            ]);
            $view->render();
            return; // Stop execution
        }

        // --- Update User ---
        if ($this->userModel->update($userId, $data)) {
            $this->setFlashMessage('success', 'Profile updated successfully!');
        } else {
            $this->setFlashMessage('info', 'No changes were made to your profile.');
        }

        $this->redirect('/profile');
    }

    /**
     * Handle password change.
     */
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // 1. Verify current password using the model (Improved MVC)
        if (!$this->userModel->isCurrentPasswordCorrect($userId, $currentPassword)) {
            $this->setFlashMessage('error', 'Your current password is incorrect.');
            $this->redirect('/profile');
            return;
        }

        // 2. Validate new password
        if (strlen($newPassword) < 8) { // Increased minimum length
            $this->setFlashMessage('error', 'New password must be at least 8 characters long.');
            $this->redirect('/profile');
            return;
        }
        if ($newPassword !== $passwordConfirm) {
            $this->setFlashMessage('error', 'New passwords do not match.');
            $this->redirect('/profile');
            return;
        }

        // 3. Update password via the model
        if ($this->userModel->update($userId, ['password' => $newPassword])) {
            $this->setFlashMessage('success', 'Password changed successfully!');
        } else {
            $this->setFlashMessage('error', 'Failed to change password.');
        }

        $this->redirect('/profile');
    }
}