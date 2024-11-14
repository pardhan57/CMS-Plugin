<?php

namespace include\api; 

// Ensure the file is not accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class SecureApi {

    private $secret_key = 'Your_token_here'; 

    /**
     * Verify the JWT token from the Authorization header
     */
    public function verify_jwt_tokens($request) {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? false;

       $request_uri = $_SERVER['REQUEST_URI'];
    
        // Check if the request starts with /api
       if(strpos($request_uri, '/token') !== false ){
            return;
        }

        if (!$header) {
            return new \WP_Error('rest_forbidden', __('Authorization header missing'), array('status' => 403));
        }

        // Extract the token from the Bearer header
        list($type, $token) = explode(' ', $header, 2);

        if (empty($type) || strtolower($type) !== 'bearer' || empty($token)) {
            return new \WP_Error('rest_forbidden', __('Invalid authorization header'), array('status' => 403));
        }

        try {
            // Decode the token
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return true; // Return true if token is valid
        } catch (\Firebase\JWT\ExpiredException $e) {
            return new \WP_Error('rest_token_expired', __('Token has expired'), array('status' => 401));
        } catch (\Exception $e) {
            return new \WP_Error('rest_invalid_token', __('Invalid token'), array('status' => 401));
        }
    }
}
