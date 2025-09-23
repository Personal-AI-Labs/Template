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


    /**
     * Adjusts the brightness of a hex color.
     * @param string $hex The hex color code.
     * @param int $steps A value from -255 to 255. Negative to darken, positive to lighten.
     * @return string The new hex color code.
     */
    function adjustBrightness(string $hex, int $steps): string
    {
        $steps = max(-255, min(255, $steps));
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
            . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
            . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Returns the correct CSS font-family stack based on the setting.
     * @param string $fontName The font name from settings.
     * @return string The CSS font-family value.
     */
    function getFontFamily(string $fontName): string
    {
        switch ($fontName) {
            case 'Inter':
                return "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
            case 'Roboto':
                return "'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif";
            case 'System':
            default:
                return "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
        }
    }
}