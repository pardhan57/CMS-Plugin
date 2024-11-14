<?php

namespace include\api;

use \Firebase\JWT\JWT;
use WP_REST_Request;
use WP_Error;
use WP_REST_Response;

if (!defined('ABSPATH')) {
    exit;
}

class GenerateToken {
    const JWT_SECRET_KEY = 'Your_token_here'; 

    public function register_rest_routes() {
        add_action('rest_api_init', function () {
            register_rest_route('dice', '/token', array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_login'),
                'permission_callback' => '__return_true',
            ));
        });

    }

    /**
     * Handle login request and generate JWT token on successful login.
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function handle_login(WP_REST_Request $request) {

        $username = $request->get_param('username');
        $password = $request->get_param('password');

        // Debugging
        // error_log("Username: " . $username);
        // error_log("Password: " . $password);

        // Authenticate user
        $user = wp_authenticate($username, $password);

        if (is_wp_error($user)) {
            error_log("Login Error: " . $user->get_error_message());

            return new \WP_Error('rest_invalid', 'Invalid credentials', array('status' => 401));
        }

        // Generate JWT Token on successful login
        $token = $this->generate_jwt_token($user);

      
        // Return the token in the response
        return new WP_REST_Response(
            array(
                'token' => $token,
                'user_email' => $user->user_email,
                'user_id' => $user->ID
            ),
            200
        );
    }

    
    private function generate_jwt_token($user) {
        $issued_at = time();
        $expiration_time = $issued_at + (8 * 60 * 60); // Token expires in 8 hour
        $payload = array(
            'iss' => get_bloginfo('url'),  // Issuer
            'iat' => $issued_at,           // Issued at
            'exp' => $expiration_time,     // Expiry time
            'data' => array(               // User data
                'user_id' => $user->ID,
                'username' => $user->user_login
            )
        );

        // Encode the token using the secret key
        // return JWT::encode($payload, self::JWT_SECRET_KEY);

        // Specify the signing algorithm (HS256)
        $jwt_token = \Firebase\JWT\JWT::encode($payload, JWT_SECRET_KEY, 'HS256');

        return $jwt_token;
    }

}



