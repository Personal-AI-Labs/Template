<form action="/settings" method="POST" id="settings-form">
    <div class="profile-grid">
        <div class="card">
            <div class="card-header">
                <h2>General Settings</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" id="site_name" name="site_name" class="form-control"
                           value="<?= htmlspecialchars($settings['site_name'] ?? 'My App') ?>">
                </div>

                <div class="form-group">
                    <label for="maintenance_mode">Maintenance Mode</label>
                    <select id="maintenance_mode" name="maintenance_mode" class="form-control">
                        <option value="false" <?= ($settings['maintenance_mode'] ?? 'false') == 'false' ? 'selected' : '' ?>>Off</option>
                        <option value="true" <?= ($settings['maintenance_mode'] ?? 'false') == 'true' ? 'selected' : '' ?>>On (Site disabled for non-admins)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Styling Options</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="theme_color">Primary Theme Color</label>
                    <input type="color" id="theme_color" name="theme_color" class="form-control"
                           value="<?= htmlspecialchars($settings['theme_color'] ?? '#007bff') ?>">
                    <small>Used for primary buttons, links, and accents.</small>
                </div>

                <div class="form-group">
                    <label for="theme_mode">Theme Mode</label>
                    <select id="theme_mode" name="theme_mode" class="form-control">
                        <option value="light" <?= ($settings['theme_mode'] ?? 'light') == 'light' ? 'selected' : '' ?>>Light</option>
                        <option value="dark" <?= ($settings['theme_mode'] ?? 'light') == 'dark' ? 'selected' : '' ?>>Dark</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="layout_density">Layout Density</label>
                    <select id="layout_density" name="layout_density" class="form-control">
                        <option value="comfortable" <?= ($settings['layout_density'] ?? 'comfortable') == 'comfortable' ? 'selected' : '' ?>>Comfortable</option>
                        <option value="compact" <?= ($settings['layout_density'] ?? 'comfortable') == 'compact' ? 'selected' : '' ?>>Compact</option>
                    </select>
                    <small>Adjusts spacing and padding throughout the site.</small>
                </div>

                <div class="form-group">
                    <label for="base_font">Base Font</label>
                    <select id="base_font" name="base_font" class="form-control">
                        <option value="Inter" <?= ($settings['base_font'] ?? 'Inter') == 'Inter' ? 'selected' : '' ?>>Inter (Modern)</option>
                        <option value="Roboto" <?= ($settings['base_font'] ?? 'Inter') == 'Roboto' ? 'selected' : '' ?>>Roboto (Classic)</option>
                        <option value="System" <?= ($settings['base_font'] ?? 'Inter') == 'System' ? 'selected' : '' ?>>System Default</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions" style="border-top: none; padding-top: 0;">
        <button type="submit" class="btn btn-primary" id="settings-save-button">Save</button>
    </div>
</form>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const settingsForm = document.getElementById('settings-form');
        const saveButton = document.getElementById('settings-save-button');

        if (settingsForm) {
            settingsForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const originalButtonText = saveButton.innerHTML;
                saveButton.disabled = true;
                saveButton.innerHTML = 'Saving...';

                const formData = new FormData(settingsForm);

                fetch('/settings', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Check if the server told us to reload
                        if (data.success && data.reload) {
                            location.reload(); // Reload the page immediately
                        } else {
                            // If it's not a success/reload, show an error toast directly
                            Toastify({
                                text: data.message,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#dc3545",
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toastify({
                            text: "A network error occurred. Please try again.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#dc3545",
                        }).showToast();
                    })
                    .finally(() => {
                        // This part will only run if the page doesn't reload
                        if (saveButton) {
                            saveButton.disabled = false;
                            saveButton.innerHTML = originalButtonText;
                        }
                    });
            });
        }
    });
</script>