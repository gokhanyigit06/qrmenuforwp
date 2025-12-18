<?php
/**
 * Plugin Name: Mickey's QR Menu
 * Description: A dynamic QR Menu & Admin Panel plugin.
 * Version: 1.6.0
 * Author: Mickey's
 * Text Domain: mickeys-qr-menu
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MICKEYS_QR_MENU_PATH', plugin_dir_path(__FILE__));
define('MICKEYS_QR_MENU_URL', plugin_dir_url(__FILE__));

class MickeysQRMenu
{

    public function __construct()
    {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Register shortcode
        add_shortcode('mickeys_qr_menu', array($this, 'render_frontend_menu'));

        // AJAX handlers
        add_action('wp_head', array($this, 'print_dynamic_styles'), 100);
        add_action('wp_ajax_mickey_save_data', array($this, 'handle_save_data'));
        add_action('wp_ajax_mickey_get_data', array($this, 'handle_get_data'));
        add_action('wp_ajax_nopriv_mickey_get_data', array($this, 'handle_get_data')); // Allow frontend to get data
    }

    public function enqueue_frontend_assets()
    {
        $version = time(); // Force cache busting for dev
        wp_register_style('mickeys-style', MICKEYS_QR_MENU_URL . 'style.css', array(), $version);
        wp_register_script('mickeys-menu-data', MICKEYS_QR_MENU_URL . 'menu-data.js', array(), $version, true);
        wp_register_script('mickeys-app', MICKEYS_QR_MENU_URL . 'script.js', array('mickeys-menu-data', 'jquery'), $version, true);

        // Pass AJAX URL to script
        wp_localize_script('mickeys-app', 'mickeyData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mickey_nonce')
        ));
    }

    public function enqueue_admin_assets($hook)
    {
        if ($hook != 'toplevel_page_mickeys-qr-admin') {
            return;
        }

        $version = time(); // Force cache busting for dev
        wp_enqueue_style('mickeys-admin-style', MICKEYS_QR_MENU_URL . 'admin-style.css', array(), $version);
        wp_enqueue_script('xlsx', 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js', array(), '0.18.5', true);

        // Enqueue WP Media Uploader
        wp_enqueue_media();

        wp_enqueue_script('mickeys-menu-data', MICKEYS_QR_MENU_URL . 'menu-data.js', array(), $version, true);
        wp_enqueue_script('mickeys-admin-script', MICKEYS_QR_MENU_URL . 'admin-script.js', array('jquery', 'jquery-ui-sortable', 'xlsx', 'mickeys-menu-data'), $version, true);

        wp_localize_script('mickeys-admin-script', 'mickeyAdminData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mickey_admin_nonce')
        ));
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Mickey\'s Menu Admin',
            'QR Menu Panel',
            'manage_options',
            'mickeys-qr-admin',
            array($this, 'render_admin_page'),
            'dashicons-food',
            6
        );
    }

    public function render_admin_page()
    {
        // We will load the content of admin.html here
        // Note: We need to adjust admin.html to be compatible with WP Admin structure if needed
        include(MICKEYS_QR_MENU_PATH . 'admin-view.php');
    }

    public function render_frontend_menu($atts)
    {
        wp_enqueue_style('mickeys-style');
        wp_enqueue_script('mickeys-app');
        $atts = shortcode_atts(
            array(
                'layout' => 'full', // 'full' or 'embed'
            ),
            $atts,
            'mickeys_qr_menu'
        );

        $layout = $atts['layout'];

        // Pass theme settings to view
        $theme_settings_raw = get_option('mickey_theme_settings', '{}');
        $theme_settings = json_decode($theme_settings_raw, true);
        if (!$theme_settings)
            $theme_settings = array();

        ob_start();
        // Variables $layout and $theme_settings will be available in the included file
        include plugin_dir_path(__FILE__) . 'frontend-view.php';
        return ob_get_clean();
    }

    // AJAX: Save Data
    public function handle_save_data()
    {
        check_ajax_referer('mickey_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $key = sanitize_text_field($_POST['key']);
        $value = wp_unslash($_POST['value']); // JSON string, keep as is

        if ($key && $value) {
            update_option('mickey_' . $key, $value);
            wp_send_json_success('Saved');
        } else {
            wp_send_json_error('Missing data');
        }
    }

    // AJAX: Get Data
    public function handle_get_data()
    {
        // Optionally verify nonce for frontend read-only if strict
        // check_ajax_referer('mickey_nonce', 'nonce');

        $key = sanitize_text_field($_GET['key']);

        if ($key) {
            $data = get_option('mickey_' . $key, null); // Return null if not set
            wp_send_json_success($data);
        } else {
            wp_send_json_error('Missing key');
        }
    }

    public function print_dynamic_styles()
    {
        $settings = get_option('mickey_theme_settings', null);
        if ($settings) {
            $settings = json_decode($settings, true);
            if ($settings) {
                // Enqueue Google Fonts
                $fonts = array();
                if (!empty($settings['fontPrimary']))
                    $fonts[] = $settings['fontPrimary'];
                if (!empty($settings['fontDisplay']))
                    $fonts[] = $settings['fontDisplay'];

                if (!empty($fonts)) {
                    $fonts = array_unique($fonts);
                    $font_family_strings = array();
                    foreach ($fonts as $font) {
                        if ($font === 'Metropolis')
                            continue; // Skip non-Google
                        $font_family_strings[] = urlencode($font) . ':wght@300;400;500;600;700';
                    }
                    if (!empty($font_family_strings)) {
                        // Use pipe | as separator for multiple families in one URL if needed, but separate &family= is standard
                        $query_args = array(
                            'display' => 'swap',
                        );
                        // Manually build string to avoid encoding issues with multiple family params
                        $base_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $font_family_strings) . '&display=swap';

                        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
                        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
                        echo '<link href="' . esc_url($base_url) . '" rel="stylesheet">';
                    }
                }

                echo '<style id="mickeys-dynamic-styles">';
                echo ':root {';

                // Fonts
                if (!empty($settings['fontPrimary']))
                    echo '--font-primary: \'' . esc_attr($settings['fontPrimary']) . '\', sans-serif !important;';
                if (!empty($settings['fontDisplay']))
                    echo '--font-display: \'' . esc_attr($settings['fontDisplay']) . '\', serif !important;';

                // Colors
                if (!empty($settings['primaryColor']))
                    echo '--primary-color: ' . esc_attr($settings['primaryColor']) . ';';
                if (!empty($settings['secondaryColor']))
                    echo '--secondary-color: ' . esc_attr($settings['secondaryColor']) . ';';
                if (!empty($settings['bgColor']))
                    echo '--bg-body: ' . esc_attr($settings['bgColor']) . ';';
                if (!empty($settings['cardColor']))
                    echo '--bg-card: ' . esc_attr($settings['cardColor']) . ';';
                if (!empty($settings['textColor']))
                    echo '--text-primary: ' . esc_attr($settings['textColor']) . ';';

                // Footer Colors
                if (!empty($settings['footerHeaderColor']))
                    echo '--footer-header-color: ' . esc_attr($settings['footerHeaderColor']) . ';';
                if (!empty($settings['footerTextColor']))
                    echo '--footer-text-color: ' . esc_attr($settings['footerTextColor']) . ';';

                // Visuals
                $radius = isset($settings['borderRadius']) ? $settings['borderRadius'] : '12';
                $width = isset($settings['borderWidth']) ? $settings['borderWidth'] : '1';
                // Layout
                $maxWidth = '100%';
                if (isset($settings['layoutWidth'])) {
                    if ($settings['layoutWidth'] === 'container')
                        $maxWidth = '1200px';
                    if ($settings['layoutWidth'] === 'narrow')
                        $maxWidth = '800px';
                    if ($settings['layoutWidth'] === 'custom' && isset($settings['customWidth'])) {
                        $customPercent = intval($settings['customWidth']);
                        if ($customPercent >= 50 && $customPercent <= 100) {
                            $maxWidth = $customPercent . '%';
                        }
                    }
                }

                // Header sizes
                $headerSize = isset($settings['headerFontSize']) ? $settings['headerFontSize'] : '24';
                $headerSpacing = isset($settings['headerSpacing']) ? $settings['headerSpacing'] : '0';

                echo '--border-radius: ' . esc_attr($radius) . 'px;';
                echo '--border-width: ' . esc_attr($width) . 'px;';
                echo '--container-max-width: ' . esc_attr($maxWidth) . ';';
                echo '--header-font-size: ' . esc_attr($headerSize) . 'px;';
                echo '--header-spacing: ' . esc_attr($headerSpacing) . 'px;';

                echo '}';

                // Apply Styles
                if (!empty($settings['bgColor'])) {
                    echo '.mickeys-menu-wrapper { background-color: var(--bg-body); }';
                    echo 'body { background-color: var(--bg-body); }';
                }

                // Width constraints - apply to main containers
                echo '.menu-accordion { max-width: var(--container-max-width) !important; margin: 0 auto; }';
                echo '.header-content { max-width: var(--container-max-width) !important; margin: 0 auto; }';
                echo '.banner-slider { max-width: var(--container-max-width) !important; margin: 0 auto; }';
                echo '.footer .container { max-width: var(--container-max-width) !important; margin: 0 auto; }';
                echo '.footer-grid { max-width: var(--container-max-width) !important; margin: 0 auto; }';

                // Footer
                echo 'footer h3 { color: var(--footer-header-color) !important; }';
                echo 'footer p, footer a, footer li { color: var(--footer-text-color) !important; }';
                echo 'footer .social-link { color: var(--footer-text-color) !important; border-color: var(--footer-text-color) !important; }';

                // Typography
                echo 'h1, h2.category-title { letter-spacing: var(--header-spacing); }';
                echo 'h2.category-title { font-size: var(--header-font-size); }';

                echo '</style>';
            }
        }
    }
}

new MickeysQRMenu();
