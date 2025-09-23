<?php

namespace App\Models;

use App\Core\Database;

class Settings
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Retrieves all settings from the database and returns them as an associative array.
     *
     * @return array An array where keys are setting_key and values are setting_value.
     */
    public function getAllSettings(): array
    {
        $results = $this->db->fetchAll("SELECT setting_key, setting_value FROM settings");

        // Convert the array of records into a single key->value array
        return array_column($results, 'setting_value', 'setting_key');
    }

    /**
     * Updates multiple settings in the database.
     *
     * @param array $settings An associative array of settings to update ['key' => 'value'].
     * @return bool True on success.
     */
    public function updateSettings(array $settings): bool
    {
        // Use a transaction to ensure all settings are updated or none are.
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                    ON CONFLICT (setting_key) DO UPDATE SET setting_value = EXCLUDED.setting_value";

            foreach ($settings as $key => $value) {
                $this->db->execute($sql, [$key, $value]);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            // In a real app, you would log the error
            error_log($e->getMessage());
            return false;
        }
    }
}