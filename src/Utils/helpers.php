<?php

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