<?php

// 1. Declare that this class belongs to the App\Models namespace
namespace App\Models;

// 2. Import the global PDO class
use App\Core\Database;
use PDO;

class User {
    /**
     * @var PDO The database connection object.
     */
    protected Database $db;

    /**
     * User constructor.
     * @param Database $db The database connection instance.
     */
    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Finds a user by their unique ID. Does not select the password hash.
     *
     * @param string $id The UUID of the user.
     * @return mixed The user record as an associative array, or false if not found.
     */
    public function findById($id) {
        // Excludes sensitive fields like password and tokens
        $sql = "SELECT id, email, first_name, last_name, avatar, role, is_active, timezone, email_verified_at, created_at, updated_at 
                FROM users 
                WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Finds an active user by their email. Used for login.
     *
     * @param string $email The email address of the user.
     * @return mixed The full user record (including password hash), or false if not found.
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? AND is_active = true";
        return $this->db->fetchOne($sql, [$email]);
    }

    /**
     * Creates a new user and generates an email verification token.
     *
     * @param array $data Associative array of user data (email, password, first_name, last_name).
     * @return array|false An array containing the new user's ID and token, or false on failure.
     */
    public function create($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $verificationToken = bin2hex(random_bytes(32));

        $sql = "INSERT INTO users (email, password, first_name, last_name, email_verification_token, role)
                VALUES (?, ?, ?, ?, ?, ?)
                RETURNING id";

        $params = [
            $data['email'],
            $hashedPassword,
            $data['first_name'],
            $data['last_name'],
            $verificationToken,
            $data['role'] ?? 'user'
        ];

        $userId = $this->db->fetchColumn($sql, $params);

        return $userId ? ['id' => $userId, 'token' => $verificationToken] : false;
    }

    /**
     * Updates an existing user's information.
     * Note: `updated_at` is handled automatically by the database trigger.
     *
     * @param string $id The UUID of the user to update.
     * @param array $data Associative array of data to update.
     * @return bool True on success, false on failure.
     */
    public function update($id, $data) {
        $setClauses = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key === 'password' && !empty($value)) {
                $setClauses[] = "password = ?";
                $params[] = password_hash($value, PASSWORD_BCRYPT);
            } elseif ($key !== 'id' && $key !== 'password') {
                $setClauses[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        if (empty($setClauses)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $params[] = $id;

        return $this->db->execute($sql, $params);
    }

    /**
     * Verifies a user's password against the stored hash.
     *
     * @param string $password The plain-text password to verify.
     * @param string $hash The stored password hash from the database.
     * @return bool True if the password is correct, false otherwise.
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Finds a user by their verification token and marks them as verified.
     *
     * @param string $token The email verification token.
     * @return bool True on success, false if token is invalid or user already verified.
     */
    public function verifyEmailByToken($token) {
        $sql = "UPDATE users
                SET email_verified_at = NOW(), email_verification_token = NULL
                WHERE email_verification_token = ? AND email_verified_at IS NULL
                RETURNING id";

        $userId = $this->db->fetchColumn($sql, [$token]);

        return $userId !== false;
    }

    /**
     * Sets a password reset token for a user.
     *
     * @param string $email The user's email address.
     * @return mixed The token string on success, false on failure (user not found).
     */
    public function generatePasswordResetToken($email) {
        $token = bin2hex(random_bytes(32));
        // Use DateTime for safer date manipulation
        $expiryTime = (new \DateTime('+1 hour'))->format('Y-m-d H:i:s');

        $sql = "UPDATE users
                SET password_reset_token = ?, password_reset_expires_at = ?
                WHERE email = ? AND is_active = true
                RETURNING id";

        $userId = $this->db->fetchColumn($sql, [$token, $expiryTime, $email]);

        return $userId ? $token : false;
    }

    /**
     * Resets a user's password using a valid token.
     *
     * @param string $token The password reset token.
     * @param string $newPassword The new plain-text password.
     * @return bool True on success, false if token is invalid or expired.
     */
    public function resetPassword($token, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $sql = "UPDATE users
                SET password = ?,
                    password_reset_token = NULL,
                    password_reset_expires_at = NULL
                WHERE password_reset_token = ? AND password_reset_expires_at > NOW()
                RETURNING id";

        $userId = $this->db->fetchColumn($sql, [$hashedPassword, $token]);

        return $userId !== false;
    }

    /**
     * Checks if the provided plain-text password matches the user's stored hash.
     *
     * @param string $userId The user's ID.
     * @param string $password The plain-text password to check.
     * @return bool
     */
    public function isCurrentPasswordCorrect(string $userId, string $password): bool
    {
        $hash = $this->db->fetchColumn("SELECT password FROM users WHERE id = ?", [$userId]);

        // If no hash is found for the user, return false
        if (!$hash) {
            return false;
        }

        // Use password_verify to securely compare the password to the hash
        return password_verify($password, $hash);
    }
}