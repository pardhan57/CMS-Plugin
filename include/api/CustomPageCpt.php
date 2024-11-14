<?php
namespace include\api;

/**
 * Create endpoint for Custom pages Content
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class CustomPageCpt {

    /**
     * Register the custom REST API endpoint.
     */
    public function create_custom_page_endpoint() {
        register_rest_route('dice', 'dice_(?P<post_title>[a-zA-Z0-9-_]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_cpt_slug_content'),
            'permission_callback' => '__return_true', 
        ));
    }


    public function get_cpt_slug_content($request) {
        // Get the post title from the request
        $post_title = $request['post_title'];

        // Query the custom post type based on the post title
        $args = array(
            'post_type' => 'custom-page-content', // Your CPT name
            'name' => sanitize_title($post_title), // Slug of the post
            'posts_per_page' => 1
        );

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            $posts = array();

            while ($query->have_posts()) {
                $query->the_post();

                // Initialize an array to store repeater field content
                $sections = array();

                // Check if repeater field has content
                if (have_rows('content_sections')) {
                    while (have_rows('content_sections')) {
                        the_row();

                        // Get each section's ID and content
                        $section_id = get_sub_field('section_id');
                        $section_content = get_sub_field('section_content');

                        // Add section content to sections array
                        $sections[] = array(
                            'id' => $section_id,
                            'content' => apply_filters('the_content', $section_content)
                        );
                    }
                }

                // Get SEOPress meta data
                $seo_title = get_post_meta(get_the_ID(), '_seopress_titles_title', true); // SEOPress title
                $seo_description = get_post_meta(get_the_ID(), '_seopress_titles_desc', true); // SEOPress description
                $seo_keywords = get_post_meta(get_the_ID(), '_seopress_titles_keywords', true); // SEOPress keywords
                $seo_canonical_url = get_post_meta(get_the_ID(), '_seopress_robots_canonical', true); // SEOPress canonical URL
                $seo_robots = array(
                    'noindex' =>  get_post_meta(get_the_ID(), '_seopress_robots_index', true), // SEOPress Ronbots URL
                    'nofollow'=> get_post_meta(get_the_ID(), '_seopress_robots_follow', true), // SEOPress Ronbots Follow
                    'noimageindex' =>  get_post_meta(get_the_ID(), '_seopress_robots_imageindex', true) // SEOPress Ronbots Imageindex
                );
                $seo_redirection = array(
                    'redirection_enabled' => get_post_meta(get_the_ID(), '_seopress_redirections_enabled', true),
                    'redirection_url' => get_post_meta(get_the_ID(), '_seopress_redirections_value', true),
                );

                $faq_schema = get_post_meta(get_the_ID(), '_seopress_pro_schemas_manual', true); // FAQ Schema
                 
                // Prepare the response data
                $posts[] = array(
                    'id'          => get_the_ID(),
                    'title'       => get_the_title(),
                    'slug'        => get_post_field('post_name', get_the_ID()),
                    'content'     => apply_filters('the_content', get_the_content()),
                    'acf_fields'  => get_fields(), // Fetch all ACF fields
                    'seo'          => array(
                        'title' => $seo_title,
                        'description' => $seo_description,
                        'keywords'  => $seo_keywords,
                        'canonical_url' => $seo_canonical_url,
                        'robots'        => $seo_robots,
                        'redirections'   => $seo_redirection
                    ),
                    'faq_schema' => $faq_schema
                );
            }

            wp_reset_postdata();

            return new \WP_REST_Response($posts, 200);
        } else {
            return new \WP_REST_Response(array('message' => 'No content found'), 404);
        }
    }

    /**
     * Change the default REST API URL prefix.
     */
    public function change_rest_prefix() {
        return 'api'; // Your custom prefix
    }
}