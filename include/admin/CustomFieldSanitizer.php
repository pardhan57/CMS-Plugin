<?php
namespace include\admin;
class CustomFieldSanitizer {

/**
 * Sanitize WYSIWYG content to allow only specific HTML tags
 *
 * @param string $content The content to sanitize
 * @return string Sanitized content
 */
public function sanitize_wysiwyg_content($content) {
    // Allow basic HTML but disallow iframe
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
        ),
        'b' => array(),
        'strong' => array(),
        'i' => array(),
        'em' => array(),
        'p' => array(),
        'br' => array(),
        'ul' => array(),
        'ol' => array(),
        'li' => array(),
        'h1' => array(),
        'h2' => array(),
        'h3' => array(),
        'h4' => array(),
        'h5' => array(),
        'h6' => array(),
    );

    return wp_kses($content, $allowed_html);
}

/**
 * Hook into the save function of the repeater field and sanitize content
 *
 * @param int $post_id The ID of the post being saved
 */
public function save_custom_repeater_fields($post_id) {
    // Array of custom field IDs to sanitize
    $custom_field_ids = array('content_sections', 'content', 'footer_copyright');

    foreach ($custom_field_ids as $field_id) {
        if (isset($_POST[$field_id])) {
            // Sanitize the content for each custom field
            $wysiwyg_content = $_POST[$field_id];
            $sanitized_content = $this->sanitize_wysiwyg_content($wysiwyg_content);
            update_post_meta($post_id, $field_id, $sanitized_content);
        }
    }
}


/**
 * Sanitize ACF WYSIWYG content before saving it
 *
 * @param string $value The content to sanitize
 * @param int $post_id The ID of the post
 * @param array $field The field array
 * @return string Sanitized content
 */
public function sanitize_acf_wysiwyg_content($value, $post_id, $field) {
    return $this->sanitize_wysiwyg_content($value);
}
}
