<?php
namespace include\api;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if (!class_exists('RestApiEndpoints')) :

    class RestApiEndpoints {

        /**
         * Constructor to initialize the class.
         */
        public function __construct() {
            add_action('rest_api_init', array($this, 'register_endpoints'));
        }

         /**
         * Register all the custom REST API endpoints.
         */
        public function register_endpoints()
        {
            $this->acf_options_endpoint();
            $this->seopress_redirections_endpoint();
        }

        /**
         * Register the custom REST API endpoint.
         */
       
        public function acf_options_endpoint() {
            register_rest_route('dice', '/brand-settings', [
              'methods' => 'GET',
              'callback' =>  array($this, 'acf_options_route'),
            ]);
        }

        public function acf_options_route() {
            return get_fields('options');
        }
        
        // Register SEO Press global redirections endpoint
        public function seopress_redirections_endpoint() {
            register_rest_route('dice', '/redirections', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_seopress_redirections'),
                'permission_callback' => '__return_true',
            ));
        }
        
        public function get_seopress_redirections() {
            $args = array(
                'post_type' => 'seopress_404',
                'post_status' => 'publish',
                'posts_per_page' => -1, // Fetch all redirections
            );

            $redirections = get_posts($args);
            $redirection_data = array();

            if ($redirections) {
                foreach ($redirections as $redirection) {
                    $source_url = get_post_meta($redirection->ID, '_seopress_redirections_value', true);
                    $target_url = $redirection->post_title;
                    $redirection_type = get_post_meta($redirection->ID, '_seopress_redirections_type', true); 

                    $redirection_data[] = array(
                        'source_url' => $source_url,
                        'target_url' => $target_url,
                        'redirection_type' => $redirection_type,
                    );
                }
                return rest_ensure_response($redirection_data);
            } else {
                return new \WP_Error('no_redirections', 'No redirections found', array('status' => 404));
            }
        }



    }

endif;
