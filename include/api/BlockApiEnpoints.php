<?php

namespace include\api;

if (!defined('ABSPATH')) {
    exit;
}

class BlockApiEnpoints
{

    /**
     * Block access to specific REST API endpoints.
     *
     * @param WP_Error|null|bool $result Error from another authentication handler, null if none.
     * @return WP_Error|null|bool WP_Error if authentication error, null if authentication method is not enabled, true if authentication succeeded.
     */
    public function block_rest_api_endpoints($result)
    {
        // If the result is already an error, return it
        if (!empty($result)) {
            return $result;
        }

        // List of REST API endpoints to block
        $blocked_endpoints = array(
            // rest_get_url_prefix() . '/wp/v2/posts',
            rest_get_url_prefix() . '/wp/v2/pages',
            rest_get_url_prefix() . '/wp/v2/comments',
            rest_get_url_prefix() . '/wp/v2/users',
        );

        // Check if the current request URI matches any blocked endpoint
        foreach ($blocked_endpoints as $endpoint) {
            if (strpos($_SERVER['REQUEST_URI'], $endpoint) !== false) {
                return new \WP_Error('rest_forbidden', __('You are not allowed to access this REST API endpoint.'), array('status' => 403));
            }
        }
        if (!is_user_logged_in()) {
            return $result;
        }
    }

    // filter to remove the extra wp routes from api
    function remove_rest_api_endpoints($endpoints){
        foreach ($endpoints as $route => $endpoint) {
            // check the content
            if (0 === stripos($route, '/wp/') || 0 === stripos($route, '/wp-block-editor/') || 0 === stripos($route, '/oembed') || 0 === stripos($route, '/wp-site-health/')) {
                unset($endpoints[$route]);
            }
        }

        return $endpoints;

    }

}
