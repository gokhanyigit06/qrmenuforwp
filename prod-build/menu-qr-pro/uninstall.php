<?php
/**
 * Menu QR Pro Uninstaller
 * 
 * Fired when the plugin is uninstalled via WordPress admin.
 * This file will delete all plugin data from the database.
 * 
 * @package Menu_QR_Pro
 * @version 2.0.0
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all plugin options from wp_options table
delete_option('mickey_mickeys_menu_data');
delete_option('mickey_mickeys_categories');
delete_option('mickey_mickeys_allergens');
delete_option('mickey_mickeys_banners');
delete_option('mickey_theme_settings');

// Delete any transients if used
delete_transient('mickey_menu_cache');
delete_transient('mickey_categories_cache');

// For multisite installations
if (is_multisite()) {
    global $wpdb;

    // Get all blog IDs
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);

        // Delete options for each site
        delete_option('mickey_mickeys_menu_data');
        delete_option('mickey_mickeys_categories');
        delete_option('mickey_mickeys_allergens');
        delete_option('mickey_mickeys_banners');
        delete_option('mickey_theme_settings');

        // Delete transients
        delete_transient('mickey_menu_cache');
        delete_transient('mickey_categories_cache');

        restore_current_blog();
    }
}

// Clear any cached data
wp_cache_flush();

// Log uninstallation (only in debug mode)
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Menu QR Pro: Plugin uninstalled and all data removed.');
}
