<?php
if (!defined('ABSPATH')) {
    exit;
}

function save_partner_meta($post_id) {
    // თუ ეს ავტომატური შენახვაა, გამოვიდეთ
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // თუ არ არის პოსტი, გამოვიდეთ
    if (!isset($_POST['post_type'])) {
        return;
    }

    // თუ არ არის partner ტიპის პოსტი, გამოვიდეთ
    if ('partner' !== $_POST['post_type']) {
        return;
    }

    // შევამოწმოთ უფლებები
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // ძირითადი ველების შენახვა
    $fields = array(
        'partner_website',
        'partner_phone',
        'partner_email',
        'partner_address',
        'partner_founding_year',
        'partner_employees',
        'partner_social_facebook',
        'partner_social_linkedin',
        'partner_social_twitter',
        'partner_social_instagram',
        'partner_logo_id',
        'partner_cover_id'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            
            // სანიტიზაცია ველის ტიპის მიხედვით
            if (strpos($field, 'website') !== false || strpos($field, 'social_') !== false) {
                $value = esc_url_raw($value);
            } elseif (strpos($field, 'email') !== false) {
                $value = sanitize_email($value);
            } elseif (strpos($field, '_id') !== false || 
                      strpos($field, 'founding_year') !== false || 
                      strpos($field, 'employees') !== false) {
                $value = absint($value);
            } else {
                $value = sanitize_text_field($value);
            }
            
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    // სამუშაო საათების შენახვა
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    
    foreach ($days as $day) {
        // სტატუსი (ღია/დაკეტილი)
        $open_field = 'partner_' . $day . '_open';
        if (isset($_POST[$open_field])) {
            update_post_meta($post_id, '_' . $open_field, sanitize_text_field($_POST[$open_field]));
        }

        // გახსნის დრო
        $opening_field = 'partner_' . $day . '_opening';
        if (isset($_POST[$opening_field])) {
            update_post_meta($post_id, '_' . $opening_field, sanitize_text_field($_POST[$opening_field]));
        }

        // დახურვის დრო
        $closing_field = 'partner_' . $day . '_closing';
        if (isset($_POST[$closing_field])) {
            update_post_meta($post_id, '_' . $closing_field, sanitize_text_field($_POST[$closing_field]));
        }
    }
}

// ვაკეთებთ დერეგისტრაციას ძველი hook-ის
remove_action('save_post', 'save_partner_meta');

// ვარეგისტრირებთ ახალ, უფრო სპეციფიურ hook-ს
add_action('save_post_partner', 'save_partner_meta', 10, 1);
