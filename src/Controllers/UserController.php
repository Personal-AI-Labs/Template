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
        parent::__construct($db);
        $this->userModel = new User($db);
    }

    /**
     * Show the current user's profile page.
     */
    public function profile()
    {
        $this->render('users/index', [
            'title' => 'Profile',
        ]);
    }

    /**
     * Handle updates to the user's profile information.
     */
    public function updateProfile()
    {
        $userId = $_SESSION['user_id'];
        $currentUser = $this->user->findById($userId); // Assuming user model is on base controller

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];

        // --- Validation ---
        $errors = [];
        if (empty($data['first_name'])) $errors['first_name'][] = "First name is required.";
        if (empty($data['last_name'])) $errors['last_name'][] = "Last name is required.";
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'][] = "A valid email is required.";
        if ($data['email'] !== $currentUser->email && $this->user->findByEmail($data['email'])) {
            $errors['email'][] = 'This email address is already in use.';
        }

        if (!empty($errors)) {
            // If there are validation errors, return them as JSON
            $this->json(['success' => false, 'errors' => $errors], 422); // 422 Unprocessable Entity
        }

        // --- Update User ---
        if ($this->user->update($userId, $data)) {
            $this->setFlashMessage('success', 'Profile updated successfully!');
        } else {
            $this->setFlashMessage('info', 'No changes were made to your profile.');
        }

        // On success, tell the frontend to reload
        $this->json(['success' => true, 'reload' => true]);
    }

    /**
     * Handle password change.
     */
    public function updatePassword()
    {
        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (!$this->user->isCurrentPasswordCorrect($userId, $currentPassword)) {
            $this->setFlashMessage('error', 'Your current password is incorrect.');
        } elseif (strlen($newPassword) < 8) {
            $this->setFlashMessage('error', 'New password must be at least 8 characters long.');
        } elseif ($newPassword !== $passwordConfirm) {
            $this->setFlashMessage('error', 'New passwords do not match.');
        } elseif ($this->user->update($userId, ['password' => $newPassword])) {
            $this->setFlashMessage('success', 'Password changed successfully!');
        } else {
            $this->setFlashMessage('error', 'Failed to change password.');
        }

        // For this form, we'll always reload to show the flash message
        $this->json(['success' => true, 'reload' => true]);
    }
}