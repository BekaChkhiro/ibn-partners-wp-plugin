<?php
/**
 * Templates and Frontend Display for Partners
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// 1. შაბლონების გამოყენება
function partner_template_loader($template) {
    if (is_singular('partner')) {
        // პარტნიორის დეტალური გვერდი
        include_once IBN_PARTNERS_PLUGIN_DIR . 'templates/single-partner.php';
        exit;
    } elseif (is_post_type_archive('partner')) {
        // პარტნიორების არქივის გვერდი
        include_once IBN_PARTNERS_PLUGIN_DIR . 'templates/archive-partner.php';
        exit;
    }
    
    return $template;
}
add_action('template_include', 'partner_template_loader', 99);

// 2. პარტნიორის მონაცემების მიღება
function get_partner_data($post_id) {
    $data = array(
        'website' => get_post_meta($post_id, '_partner_website', true),
        'phone' => get_post_meta($post_id, '_partner_phone', true),
        'email' => get_post_meta($post_id, '_partner_email', true),
        'address' => get_post_meta($post_id, '_partner_address', true),
        'founding_year' => get_post_meta($post_id, '_partner_founding_year', true),
        'employees' => get_post_meta($post_id, '_partner_employees', true),
        'social_facebook' => get_post_meta($post_id, '_partner_social_facebook', true),
        'social_linkedin' => get_post_meta($post_id, '_partner_social_linkedin', true),
        'social_twitter' => get_post_meta($post_id, '_partner_social_twitter', true),
        'social_instagram' => get_post_meta($post_id, '_partner_social_instagram', true),
        'logo_id' => get_post_meta($post_id, '_partner_logo_id', true),
        'cover_id' => get_post_meta($post_id, '_partner_cover_id', true)
    );
    
    // სურათების URL-ები
    $data['logo_url'] = $data['logo_id'] ? wp_get_attachment_image_url($data['logo_id'], 'medium') : '';
    $data['cover_url'] = $data['cover_id'] ? wp_get_attachment_image_url($data['cover_id'], 'full') : '';
    
    // სამუშაო საათები
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    foreach ($days as $day) {
        $data['hours'][$day] = array(
            'is_open' => get_post_meta($post_id, '_partner_' . $day . '_open', true),
            'opening' => get_post_meta($post_id, '_partner_' . $day . '_opening', true),
            'closing' => get_post_meta($post_id, '_partner_' . $day . '_closing', true)
        );
    }
    
    return $data;
}
