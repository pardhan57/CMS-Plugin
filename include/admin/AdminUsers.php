<?php

namespace include\admin;

if (!defined('ABSPATH')) {
    exit;
}

class AdminUsers
{ 
    /**
     * Remove the default post type from the WordPress admin sidebar.
     *
     * This method is used to hide the default post type from the WordPress admin
     * sidebar. The default post type is unnecessary for the DICE CMS plugin, and
     * removing it helps to declutter the admin interface.
     *
     * This method is called in the AdminUsers class constructor.
     *
     * @return void
     */
    public function remove_default_post_type(){
        remove_menu_page( 'edit.php' );
        remove_menu_page( 'edit-comments.php' );
        remove_menu_page( 'edit.php?post_type=page' );
        remove_submenu_page('tools.php', 'tools.php');
    }

    public function remove_default_post_type_menu_bar( $wp_admin_bar ) {
        $wp_admin_bar->remove_node( 'new-post' );
        $wp_admin_bar->remove_node( 'new-page' );
    }

    /**
     * Enqueue the admin CSS style sheet.
     *
     * This method enqueues the dice-admin.css style sheet which is used to
     * style the WordPress admin interface.
     *
     * The style sheet is located in the include/assets directory.
     *
     * @return void
     */
    public function dice_admin_theme_style() {

        /**
         * Register the admin CSS style sheet.
         *
         * The style sheet is registered with a version number of 1.5.
         */
        $css_file_path = DICE_CMS_PLUGIN_DIR . 'include/assets/dice-admin.css'; // Absolute path to the CSS file
        $version = file_exists($css_file_path) ? filemtime($css_file_path) : '1.5'; // Get file modification time as version

        wp_register_style(
            'dice-admin-style',
            DICE_CMS_PLUGIN_URL . 'include/assets/dice-admin.css',
            [],
            $version,
            'all'
        );

        /**
         * Enqueue the admin CSS style sheet.
         *
         * The style sheet is enqueued using the wp_enqueue_style() function.
         */
        wp_enqueue_style('dice-admin-style');
    }

    public function set_custom_admin_color_for_seo_editors($user_id) {
        // Get the current user's role
        $user = wp_get_current_user();


        // Check if the user has the 'seo_editor' role (replace 'seo_editor' with the actual role of your SEO editors)
        if (in_array('editor', $user->roles)) {
            // Set the preferred admin color scheme (e.g., 'sunrise', 'coffee', etc.)
            $css_file_path = DICE_CMS_PLUGIN_DIR . 'include/assets/editors-style.css'; // Absolute path to the editor CSS file
            $version = file_exists($css_file_path) ? filemtime($css_file_path) : '1.8'; // Get file modification time as version


            update_user_meta($user->ID, 'admin_color', 'blue'); // Change 'sunrise' to any color scheme you prefer


            wp_register_style(
                'dice-editor-style',
                DICE_CMS_PLUGIN_URL . 'include/assets/editors-style.css',
                [],
                $version,
                'all'
            );

            /**
             * Enqueue the admin CSS style sheet.
             *
             * The style sheet is enqueued using the wp_enqueue_style() function.
             */
            wp_enqueue_style('dice-editor-style');
        }
}

}


