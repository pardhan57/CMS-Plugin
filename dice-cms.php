<?php
/*
Plugin Name: CMS 
Plugin URI: #
Description: Provides endpoints for content.
Version: 1.0
Author: Pardhan
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Define constants for plugin paths
define('DICE_CMS_VERSION', '1.0');
define('DICE_CMS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DICE_CMS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the activator and deactivator classes
require_once plugin_dir_path(__FILE__) . 'include/activation/Dice_CMS_Activator.php';
require_once plugin_dir_path(__FILE__) . 'include/activation/Dice_CMS_Deactivator.php';

// Register activation and deactivation hooks
register_activation_hook(__FILE__, array('Dice_CMS_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Dice_CMS_Deactivator', 'deactivate'));

// Intialize the plugin
function run_dice_cms()
{
    $init_plugin = new \include\Init;
    $init_plugin->run();
}

// run dice cms on plugin activation
run_dice_cms();
