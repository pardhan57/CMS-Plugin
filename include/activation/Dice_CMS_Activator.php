<?php

/**
 * Fired during plugin activation
 *
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 */
if (!defined('ABSPATH')) {
    exit;
}

class Dice_CMS_Activator
{

    /**
     * Method to run on plugin activation.
     */


    public static function activate()
    {
        // Perform activation tasks
        // check the current user's capability to run plugins


        if (!current_user_can('activate_plugins')) {
            return;
        }

    }
}
