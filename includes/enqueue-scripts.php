<?php
/**
 * Enqueue Scripts and Styles
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// სტილების და სკრიპტების მიბმა
function ibn_partners_enqueue_scripts() {
    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        array(),
        '5.15.4'
    );
    
    // მთავარი CSS ფაილი - მხოლოდ პარტნიორების გვერდებზე
    if (is_singular('partner') || is_post_type_archive('partner')) {
        wp_enqueue_style(
            'ibn-partners-style',
            plugins_url('assets/css/ibn-partners-style.css', dirname(__FILE__)),
            array(),
            filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/css/ibn-partners-style.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'ibn_partners_enqueue_scripts');

// ადმინ პანელის სკრიპტები
function ibn_partners_admin_scripts($hook) {
    global $post_type;
    
    // მხოლოდ პარტნიორის დამატება/რედაქტირების გვერდზე
    if ($post_type === 'partner' && in_array($hook, array('post.php', 'post-new.php'))) {
        // მედია აფთვირთვის API
        wp_enqueue_media();
        
        // დამატებითი სტილები ადმინისთვის
        wp_add_inline_style('wp-admin', '
            .partner-meta-group {
                background: #fff;
                padding: 15px;
                margin-bottom: 15px;
                border-radius: 5px;
                border: 1px solid #e2e4e7;
            }
            .partner-meta-title {
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 2px solid #0073aa;
                color: #23282d;
                font-size: 14px;
                font-weight: 600;
            }
        ');
    }
}
add_action('admin_enqueue_scripts', 'ibn_partners_admin_scripts');
