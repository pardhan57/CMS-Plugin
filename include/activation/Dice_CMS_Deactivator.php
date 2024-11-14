<?php

/**
 * Fired during plugin deactivation
 *
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 */
if (!defined('ABSPATH')) {
    exit;
}

class Dice_CMS_Deactivator
{

    /**
     * Method to run on plugin deactivation.
     */
    public static function deactivate()
    {
        // Perform deactivation tasks
        if (!current_user_can('activate_plugins')) {
            return;
        }

    }
}
