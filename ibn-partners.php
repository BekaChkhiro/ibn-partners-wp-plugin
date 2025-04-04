<?php
/**
 * Plugin Name: IBN Partners
 * Plugin URI: 
 * Description: პარტნიორების ძირითადი ფუნქციონალი - თანამედროვე დიზაინით
 * Version: 1.0.0
 * Author: Infinity Solutions
 * Author URI: infinity.ge
 * Text Domain: ibn-partners
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('IBN_PARTNERS_VERSION', '1.0.0');
define('IBN_PARTNERS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IBN_PARTNERS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once IBN_PARTNERS_PLUGIN_DIR . 'includes/post-types.php';
require_once IBN_PARTNERS_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once IBN_PARTNERS_PLUGIN_DIR . 'includes/save-meta.php';
require_once IBN_PARTNERS_PLUGIN_DIR . 'includes/templates.php';
require_once IBN_PARTNERS_PLUGIN_DIR . 'includes/enqueue-scripts.php';

// Activation hook
register_activation_hook(__FILE__, 'ibn_partners_activate');

function ibn_partners_activate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'ibn_partners_deactivate');

function ibn_partners_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
