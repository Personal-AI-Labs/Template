<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    private Settings $settingsModel;

    public function __construct($db)
    {
        parent::__construct($db); // This is important!
        $this->settingsModel = new Settings($db);
    }

    /**
     * Displays the settings page.
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
     * Handles the form submission to update settings.
     */
    public function update()
    {
        // For simplicity, we'll update all POST data.
        // In a real app, you'd add validation and sanitation here.
        $settingsData = $_POST;

        if ($this->settingsModel->updateSettings($settingsData)) {
            $this->setFlashMessage('success', 'Settings updated successfully!');
        } else {
            $this->setFlashMessage('error', 'There was an error updating settings.');
        }

        $this->redirect('/settings');
    }
}