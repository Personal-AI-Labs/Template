<div class="card">
    <div class="card-header">
        <h2>General</h2>
    </div>
    <div class="card-body">
        <form action="/settings" method="POST">

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

            <hr style="margin: 2rem 0;">

            <div class="form-group">
                <label for="theme_color">Primary Theme Color</label>
                <input type="color" id="theme_color" name="theme_color" class="form-control"
                       value="<?= htmlspecialchars($settings['theme_color'] ?? '#007bff') ?>">
                <small>This color is used for primary buttons and links.</small>
            </div>

            <div class="form-group">
                <label for="base_font">Base Font</label>
                <select id="base_font" name="base_font" class="form-control">
                    <option value="Inter" <?= ($settings['base_font'] ?? 'Inter') == 'Inter' ? 'selected' : '' ?>>Inter (Modern)</option>
                    <option value="Roboto" <?= ($settings['base_font'] ?? 'Inter') == 'Roboto' ? 'selected' : '' ?>>Roboto (Classic)</option>
                    <option value="System" <?= ($settings['base_font'] ?? 'Inter') == 'System' ? 'selected' : '' ?>>System Default</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>