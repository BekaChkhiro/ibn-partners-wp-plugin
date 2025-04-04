<?php
/**
 * Save Meta Data for Partner Post Type
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// 6. მეტა ველების შენახვა
function save_partner_meta($post_id) {
    // შევამოწმოთ nonce
    if (!isset($_POST['partner_nonce']) || !wp_verify_nonce($_POST['partner_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // შევამოწმოთ ავტომატური შენახვა
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // შევამოწმოთ უფლებები
    if ('partner' == $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // შევინახოთ მეტა ველები
    $fields = array(
        'partner_website' => 'sanitize_url',
        'partner_phone' => 'sanitize_text_field',
        'partner_email' => 'sanitize_email',
        'partner_address' => 'sanitize_text_field',
        'partner_founding_year' => 'intval',
        'partner_employees' => 'intval',
        'partner_social_facebook' => 'sanitize_url',
        'partner_social_linkedin' => 'sanitize_url',
        'partner_social_twitter' => 'sanitize_url',
        'partner_social_instagram' => 'sanitize_url'
    );
    
    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            $value = call_user_func($sanitize_callback, $_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
    
    // სურათების შენახვა
    if (isset($_POST['partner_images_nonce']) && wp_verify_nonce($_POST['partner_images_nonce'], basename(__FILE__))) {
        // ლოგოს შენახვა
        if (isset($_POST['partner_logo_id'])) {
            update_post_meta($post_id, '_partner_logo_id', sanitize_text_field($_POST['partner_logo_id']));
        }
        
        // ქავერის შენახვა
        if (isset($_POST['partner_cover_id'])) {
            update_post_meta($post_id, '_partner_cover_id', sanitize_text_field($_POST['partner_cover_id']));
        }
    }
    
    // სამუშაო საათების შენახვა
    if (isset($_POST['partner_hours_nonce']) && wp_verify_nonce($_POST['partner_hours_nonce'], basename(__FILE__))) {
        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        
        foreach ($days as $day) {
            if (isset($_POST['partner_' . $day . '_open'])) {
                update_post_meta($post_id, '_partner_' . $day . '_open', sanitize_text_field($_POST['partner_' . $day . '_open']));
            }
            
            if (isset($_POST['partner_' . $day . '_opening'])) {
                update_post_meta($post_id, '_partner_' . $day . '_opening', sanitize_text_field($_POST['partner_' . $day . '_opening']));
            }
            
            if (isset($_POST['partner_' . $day . '_closing'])) {
                update_post_meta($post_id, '_partner_' . $day . '_closing', sanitize_text_field($_POST['partner_' . $day . '_closing']));
            }
        }
    }
}
add_action('save_post', 'save_partner_meta');
