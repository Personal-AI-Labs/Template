<div class="profile-grid">
    <div class="card">
        <div class="card-header">
            <h2>Profile for <?= htmlspecialchars($user->getFullName()) ?></h2>
        </div>
        <div class="card-body">
            <form action="/profile" method="POST" novalidate id="profile-update-form">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : ''; ?>"
                           value="<?= htmlspecialchars($input['first_name'] ?? $user->first_name); ?>" required>
                    <?php if (isset($errors['first_name'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['first_name'][0]); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : ''; ?>"
                           value="<?= htmlspecialchars($input['last_name'] ?? $user->last_name); ?>" required>
                    <?php if (isset($errors['last_name'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['last_name'][0]); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : ''; ?>"
                           value="<?= htmlspecialchars($input['email'] ?? $user->email); ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['email'][0]); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="profile-update-button">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Change Password</h2>
        </div>
        <div class="card-body">
            <form action="/profile/password" method="POST" id="password-change-form">
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
                    <button type="submit" class="btn btn-primary" id="password-change-button">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        // --- Handler for Profile Update Form ---
        const profileForm = document.getElementById('profile-update-form');
        const profileButton = document.getElementById('profile-update-button');

        if (profileForm) {
            profileForm.addEventListener('submit', function (event) {
                event.preventDefault();
                handleFormSubmit(profileForm, profileButton, '/profile');
            });
        }

        // --- Handler for Password Change Form ---
        const passwordForm = document.getElementById('password-change-form');
        const passwordButton = document.getElementById('password-change-button');

        if (passwordForm) {
            passwordForm.addEventListener('submit', function(event) {
                event.preventDefault();
                handleFormSubmit(passwordForm, passwordButton, '/profile/password');
            });
        }

        // --- Reusable Form Submission Logic ---
        function handleFormSubmit(form, button, url) {
            const originalButtonText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = 'Saving...';
            clearValidationErrors(form);

            const formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (body.success && body.reload) {
                        location.reload();
                    } else if (!body.success && body.errors) {
                        // Handle and display validation errors
                        showValidationErrors(form, body.errors);
                        Toastify({ text: "Please correct the errors and try again.", backgroundColor: "#dc3545" }).showToast();
                    } else {
                        // Generic error for password form or other issues
                        Toastify({ text: body.message || "An unexpected error occurred.", backgroundColor: "#dc3545" }).showToast();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({ text: "A network error occurred.", backgroundColor: "#dc3545" }).showToast();
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalButtonText;
                });
        }

        function showValidationErrors(form, errors) {
            for (const field in errors) {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    let errorContainer = input.parentElement.querySelector('.invalid-feedback');
                    if (!errorContainer) {
                        errorContainer = document.createElement('div');
                        errorContainer.className = 'invalid-feedback';
                        input.parentElement.appendChild(errorContainer);
                    }
                    errorContainer.textContent = errors[field][0]; // Show the first error message
                }
            }
        }

        function clearValidationErrors(form) {
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        }
    });
</script>