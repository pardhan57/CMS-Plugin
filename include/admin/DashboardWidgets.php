<?php
/**
 * Customize the widgets in the WordPress dashboard.
 */
namespace include\admin;

class DashboardWidgets
{
    /**
     * Remove default dashboard widgets
     *
     * This method removes some of the default WordPress dashboard widgets, such as the Welcome panel,
     * Quick Draft widget, Activity widget, WordPress News widget, and the At a Glance widget. 
     * It cleans up the dashboard to make room for custom widgets.
     */
    public function remove_default_dashboard_widgets() {
        remove_action( 'welcome_panel', 'wp_welcome_panel' ); // Remove Welcome panel
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Remove Quick Draft widget
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); // Remove Activity widget
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // Remove WordPress News widget
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // Remove At a Glance widget
    }

    /**
     * Add custom post type widgets to the dashboard
     *
     * This method registers new custom widgets to the WordPress dashboard.
     * The widgets correspond to custom post types: Banners, Blocks, Custom Page Content, and Bonus.
     * Each widget displays a summary of posts from the respective custom post type.
     */
    public function add_custom_cpt_widgets() {
        wp_add_dashboard_widget( 'banners_at_dashboard', 'Banner', array( $this, 'render_banners_widget' ) );
        wp_add_dashboard_widget( 'blocks_at_dashboard', 'Block', array( $this, 'render_blocks_widget' ) );
        wp_add_dashboard_widget( 'page_content_at_dashboard', 'Custom Page Content', array( $this, 'render_page_content_widget' ) );
        wp_add_dashboard_widget( 'bonus_at_dashboard', 'Bonus', array( $this, 'render_bonus_widget' ) );
    }

    /**
     * Render Banners widget
     *
     * This method calls the generic `render_widget()` method to render the Banners widget.
     */
    public function render_banners_widget() {
        $this->render_widget( 'banner', 'Banner' );
    }

    /**
     * Render Blocks widget
     *
     * This method calls the generic `render_widget()` method to render the Blocks widget.
     */
    public function render_blocks_widget() {
        $this->render_widget( 'block', 'Block' );
    }

    /**
     * Render Custom Page Content widget
     *
     * This method calls the generic `render_widget()` method to render the Custom Page Content widget.
     */
    public function render_page_content_widget() {
        $this->render_widget( 'custom-page-content', 'Custom Page Content' );
    }

    /**
     * Render Bonus widget
     *
     * This method calls the generic `render_widget()` method to render the Bonus widget.
     */
    public function render_bonus_widget() {
        $this->render_widget( 'bonus', 'Bonus' );
    }

    /**
     * Generic method to render custom widgets for any post type
     *
     * @param string $post_type The custom post type to query (e.g., 'banner', 'block', etc.).
     * @param string $label The label used in the widget header and the quick link button.
     *
     * This method queries the last 5 posts of a given custom post type and displays them in a table.
     * It also provides a quick link to add new posts of that type. The output includes the post title, 
     * published date, and a link to edit each post.
     */
    private function render_widget( $post_type, $label ) {
        // Query the latest 5 posts of the given custom post type
        $query = new \WP_Query(array(
            'post_type'      => $post_type,
            'posts_per_page' => 5
        ));

        // Display the table header with Title and Published Date columns
        echo '<table style="width:100%; text-align:left;">';
        echo '<thead><tr><th>Title</th><th>Published Date</th></tr></thead>';
        echo '<tbody>';

        // Loop through the posts and display the title and published date
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                
                // Get title and published date for each post
                $title = get_the_title();
                $date = get_the_date( 'F j, Y' );
                $edit_link = get_edit_post_link();

                // Display post title with a link to edit, and its published date
                echo '<tr>';
                echo '<td><a href="' . $edit_link . '">' . $title . '</a></td>';
                echo '<td>' . $date . '</td>';
                echo '</tr>';
            }
        } else {
            // If no posts are found, display a message
            echo '<tr><td colspan="2">No ' . strtolower( $label ) . ' found.</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';

        // Reset the post data after the loop
        wp_reset_postdata();

        // Add a quick link to create a new post of the given post type
        echo '<p><a class="button button-primary" href="' . admin_url( 'post-new.php?post_type=' . $post_type ) . '">Add New ' . $label . '</a></p>';
    }
}
