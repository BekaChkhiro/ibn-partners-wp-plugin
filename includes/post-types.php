<?php
/**
 * Register Custom Post Types
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// 1. პარტნიორების Custom Post Type-ის შექმნა
function create_partners_post_type() {
    $labels = array(
        'name'               => 'პარტნიორები',
        'singular_name'      => 'პარტნიორი',
        'menu_name'          => 'პარტნიორები',
        'add_new'            => 'ახლის დამატება',
        'add_new_item'       => 'ახალი პარტნიორის დამატება',
        'edit_item'          => 'პარტნიორის რედაქტირება',
        'new_item'           => 'ახალი პარტნიორი',
        'view_item'          => 'პარტნიორის ნახვა',
        'search_items'       => 'პარტნიორების ძიება',
        'not_found'          => 'პარტნიორები არ მოიძებნა',
        'not_found_in_trash' => 'პარტნიორები არ მოიძებნა წაშლილებში',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'partner'), 
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
    );

    register_post_type('partner', $args);
}
add_action('init', 'create_partners_post_type');

// 7. სიახლეების დაკავშირება პარტნიორთან
function add_partner_connection_to_posts() {
    add_meta_box(
        'post_partner_connection',
        'დაკავშირებული პარტნიორი',
        'post_partner_connection_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_partner_connection_to_posts');

// 8. პარტნიორის არჩევის ველი სიახლეებისთვის
function post_partner_connection_callback($post) {
    wp_nonce_field(basename(__FILE__), 'post_partner_nonce');
    
    $selected_partner = get_post_meta($post->ID, '_connected_partner', true);
    
    // მივიღოთ ყველა პარტნიორი
    $partners = get_posts(array(
        'post_type' => 'partner',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    
    if ($partners) {
        echo '<div style="margin-bottom: 10px;">';
        echo '<label for="connected_partner" style="display: block; margin-bottom: 5px; font-weight: 500;">აირჩიეთ პარტნიორი:</label>';
        echo '<select name="connected_partner" id="connected_partner" style="width: 100%; padding: 8px; border-radius: 4px; border-color: #ddd;">';
        echo '<option value="">- აირჩიეთ -</option>';
        
        foreach ($partners as $partner) {
            echo '<option value="' . $partner->ID . '" ' . selected($selected_partner, $partner->ID, false) . '>' . $partner->post_title . '</option>';
        }
        
        echo '</select>';
        echo '</div>';
        
        echo '<p class="description" style="color: #666; font-size: 12px; margin-top: 5px;">დაუკავშირეთ ეს სიახლე პარტნიორს. ეს გამოჩნდება პარტნიორის გვერდზე.</p>';
    } else {
        echo '<p style="color: #666;">პარტნიორები არ არის დამატებული.</p>';
    }
}

// 9. დაკავშირებული პარტნიორის შენახვა სიახლეებში
function save_post_partner_connection($post_id) {
    // შევამოწმოთ nonce
    if (!isset($_POST['post_partner_nonce']) || !wp_verify_nonce($_POST['post_partner_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // შევამოწმოთ ავტომატური შენახვა
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // შევამოწმოთ უფლებები
    if ('post' == $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // შევინახოთ დაკავშირებული პარტნიორი
    if (isset($_POST['connected_partner'])) {
        update_post_meta($post_id, '_connected_partner', sanitize_text_field($_POST['connected_partner']));
    }
}
add_action('save_post', 'save_post_partner_connection');

// 11. პერმალინკების დასარეგისტრირებლად
function partner_flush_rewrite_rules() {
    // დარეგისტრირებული CPT-ის შემდეგ გადავტვირთოთ პერმალინკები
    create_partners_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'partner_flush_rewrite_rules');

// დამატებითი ფუნქციონალი პარტნიორებისთვის
function add_partners_grid_styles() {
    if (!is_post_type_archive('partner')) {
        return;
    }
    // Add the grid styles CSS (copied from original code)
    ?>
    <style>
    /* პარტნიორების გრიდის სტილები */
    .partners-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 30px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .partners-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* პარტნიორის ბარათის სტილები */
    .partner-card-archive {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .partner-card-archive:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .partner-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #ff5722;
        color: white;
        font-weight: 600;
        font-size: 14px;
        padding: 5px 12px;
        border-radius: 20px;
        z-index: 10;
    }
    
    .partner-image-container {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: #f9f9f9;
    }
    
    .partner-logo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .partner-card-archive:hover .partner-logo-img {
        transform: scale(1.05);
    }
    
    .partner-tag-container {
        position: absolute;
        bottom: 15px;
        left: 15px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .partner-tag {
        background: #ff5722;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .partner-tag.secondary {
        background: white;
        color: #333;
    }
    
    .partner-info {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .partner-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }
    
    .partner-category {
        display: inline-block;
        background: #f5f5f5;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 13px;
        color: #666;
        margin-bottom: 15px;
    }
    
    .partner-date {
        display: flex;
        align-items: center;
        color: #666;
        font-size: 14px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }
    
    .partner-date i {
        margin-right: 5px;
    }
    </style>
    <?php
}
