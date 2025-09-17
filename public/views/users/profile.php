<?php
// This view now expects the controller to provide:
// 1. A $user variable with the current user's data.
// 2. An optional $errors variable for validation messages.
// 3. An optional $input variable to repopulate the form on error.
?>

<div class="profile-grid">
    <div class="card">
        <div class="card-header">
            <h2>Profile Information</h2>
        </div>
        <div class="card-body">
            <form action="/profile" method="POST" novalidate>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($input['first_name'] ?? $user['first_name']); ?>" required>
                    <?php if (isset($errors['first_name'])): ?>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['first_name'][0]); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($input['last_name'] ?? $user['last_name']); ?>" required>
                    <?php if (isset($errors['last_name'])): ?>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['last_name'][0]); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($input['email'] ?? $user['email']); ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email'][0]); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Change Password</h2>
        </div>
        <div class="card-body">
            <form action="/profile/password" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm New Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>