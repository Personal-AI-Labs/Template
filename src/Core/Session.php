<?php

class Session {
    /**
     * Starts a new or resumes an existing session.
     * Ensures session is only started once.
     */
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Sets a value in the session.
     * @param string $key The key.
     * @param mixed $value The value.
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets a value from the session.
     * @param string $key The key.
     * @param mixed $default The default value if key doesn't exist.
     * @return mixed The session value or default.
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Removes a key from the session.
     * @param string $key The key to unset.
     */
    public static function forget($key) {
        unset($_SESSION[$key]);
    }

    /**
     * Destroys the entire session.
     */
    public static function destroy() {
        session_destroy();
    }
}