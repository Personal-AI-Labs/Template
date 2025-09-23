<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    private Settings $settingsModel;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->settingsModel = new Settings($db);
    }

    /**
     * Displays the settings page.
     * @throws \Exception
     */
    public function show()
    {
        $settings = $this->settingsModel->getAllSettings();
        $this->render('settings/index', [
            'title'    => 'Settings',
            'settings' => $settings
        ]);
    }

    /**
     * Handles the AJAX form submission to update settings.
     */
    public function update()
    {
        $settingsData = $_POST;

        if ($this->settingsModel->updateSettings($settingsData)) {
            // 1. Set the flash message in the session
            $this->setFlashMessage('success', 'Settings updated successfully!');

            // 2. Tell the JavaScript to reload the page
            $this->json(['success' => true, 'reload' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'An error occurred or no changes were made.'], 400);
        }
    }
}