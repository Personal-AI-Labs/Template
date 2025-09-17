-- =================================================================
--  Seed the `users` table with an admin and a regular user
-- =================================================================

-- NOTE: Passwords are pre-hashed. NEVER store plain text passwords.
-- The hash below corresponds to the plain text password: 'password123'
-- In your application, you would use a function like PHP's password_hash() to generate this.

INSERT INTO users (
    email,
    password,
    first_name,
    last_name,
    role,
    is_active,
    email_verified_at
) VALUES
      (
          'admin@example.com',
          '$2y$10$fTuNQuFn8/zfRkERGXU04OVDF7IeG7ggNK1Q6HRzBkkG6HLcEGtRy', -- Hashed 'password123'
          'Admin',
          'User',
          'admin',  -- Explicitly set the role to 'admin'
          true,
          NOW()     -- Set the email as verified for immediate use
      ),
      (
          'test@example.com',
          '$2y$10$fTuNQuFn8/zfRkERGXU04OVDF7IeG7ggNK1Q6HRzBkkG6HLcEGtRy', -- Hashed 'password123'
          'Test',
          'User',
          'user',   -- Explicitly set the role to 'user' (or you could omit to use the default)
          true,
          NOW()     -- Set the email as verified for immediate use
      );