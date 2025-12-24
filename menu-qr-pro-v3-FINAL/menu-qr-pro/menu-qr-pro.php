<?php
/**
 * Plugin Name: Menu QR Pro
 * Plugin URI: https://yourwebsite.com/menu-qr-pro
 * Description: A dynamic QR Menu & Admin Panel plugin with license management.
 * Version: 3.0.0
 * Author: Luiff Dev
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: menu-qr-pro
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MICKEYS_QR_MENU_PATH', plugin_dir_path(__FILE__));
define('MICKEYS_QR_MENU_URL', plugin_dir_url(__FILE__));
define('MICKEYS_QR_MENU_VERSION', '3.0.0');

// Load license system classes
require_once MICKEYS_QR_MENU_PATH . 'includes/class-license-manager.php';
require_once MICKEYS_QR_MENU_PATH . 'includes/class-feature-controller.php';
require_once MICKEYS_QR_MENU_PATH . 'includes/class-update-manager.php';

// Load REST API (alternative to admin-ajax for 403 issues)
require_once MICKEYS_QR_MENU_PATH . 'includes/class-rest-api.php';

class MickeysQRMenu
{
    private $license_manager;
    private $feature_controller;
    private $update_manager;

    public function __construct()
    {
        // Initialize license system
        $this->license_manager = new Mickey_License_Manager();
        $this->feature_controller = new Mickey_Feature_Controller($this->license_manager);
        $this->update_manager = new Mickey_Update_Manager($this->license_manager);

        // Make license manager globally accessible
        global $mickey_license_manager;
        $mickey_license_manager = $this->license_manager;

        // Load text domain for translations
        add_action('plugins_loaded', array($this, 'load_textdomain'));

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

        // Admin notices
        add_action('admin_notices', array($this, 'show_license_notices'));
    }

    /**
     * Load plugin textdomain for translations
     */
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'menu-qr-pro',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    public function enqueue_frontend_assets()
    {
        // Use plugin version, or time() in debug mode
        $version = MICKEYS_QR_MENU_VERSION;
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $version = time();
        }

        wp_register_style('mickeys-style', MICKEYS_QR_MENU_URL . 'style.css', array(), $version);
        wp_register_script('mickeys-menu-data', MICKEYS_QR_MENU_URL . 'menu-data.js', array(), $version, true);
        wp_register_script(
            'mickeys-app',
            MICKEYS_QR_MENU_URL . 'script.js',
            array('mickeys-menu-data', 'jquery'),
            $version,
            true
        );

        // Pass AJAX URL to script
        wp_localize_script('mickeys-app', 'mickeyData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mickey_nonce'),
            'debug' => (defined('WP_DEBUG') && WP_DEBUG)
        ));
    }

    public function enqueue_admin_assets($hook)
    {
        if ($hook != 'toplevel_page_mickeys-qr-admin') {
            return;
        }

        // Use plugin version, or time() in debug mode
        $version = MICKEYS_QR_MENU_VERSION;
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $version = time();
        }

        wp_enqueue_style('mickeys-admin-style', MICKEYS_QR_MENU_URL . 'admin-style.css', array(), $version);

        // Use local XLSX library (fallback to CDN if not exists)
        $xlsx_path = MICKEYS_QR_MENU_PATH . 'assets/js/xlsx.full.min.js';
        if (file_exists($xlsx_path)) {
            wp_enqueue_script('xlsx', MICKEYS_QR_MENU_URL . 'assets/js/xlsx.full.min.js', array(), '0.18.5', true);
        } else {
            // Fallback to CDN if local file doesn't exist
            wp_enqueue_script(
                'xlsx',
                'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js',
                array(),
                '0.18.5',
                true
            );
        }

        // Enqueue WP Media Uploader
        wp_enqueue_media();

        wp_enqueue_script('mickeys-menu-data', MICKEYS_QR_MENU_URL . 'menu-data.js', array(), $version, true);
        wp_enqueue_script('mickeys-admin-script', MICKEYS_QR_MENU_URL . 'admin-script.js', array(
            'jquery',
            'jquery-ui-sortable',
            'xlsx',
            'mickeys-menu-data'
        ), $version, true);

        wp_localize_script('mickeys-admin-script', 'mickeyAdminData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url('mqpro/v1/'),  // REST API fallback for 403 issues
            'nonce' => wp_create_nonce('mickey_admin_nonce'),
            'rest_nonce' => wp_create_nonce('wp_rest'),  // REST API nonce
            'debug' => (defined('WP_DEBUG') && WP_DEBUG),
            'locale' => get_locale()
        ));
    }

    public function add_admin_menu()
    {
        add_menu_page(
            __('Menu Admin', 'menu-qr-pro'),
            __('QR Menu Panel', 'menu-qr-pro'),
            'manage_options',
            'mickeys-qr-admin',
            array($this, 'render_admin_page'),
            'dashicons-food',
            6
        );

        // License submenu
        add_submenu_page(
            'mickeys-qr-admin',
            __('License & Support', 'menu-qr-pro'),
            __('License', 'menu-qr-pro'),
            'manage_options',
            'mickeys-qr-license',
            array($this, 'render_license_page')
        );
    }

    public function render_admin_page()
    {
        // Check if license expired - show read-only mode
        if ($this->feature_controller->block_if_expired()) {
            return; // Expired page already shown
        }

        // Show expiry warnings if needed
        echo $this->feature_controller->get_expiry_notice();

        // Show normal admin page
        include(MICKEYS_QR_MENU_PATH . 'admin-view.php');
    }

    public function render_license_page()
    {
        include MICKEYS_QR_MENU_PATH . 'admin-pages/page-license.php';
    }

    public function show_license_notices()
    {
        // Show expiry warnings on all admin pages
        echo $this->feature_controller->get_expiry_notice();
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
        try {
            check_ajax_referer('mickey_admin_nonce', 'nonce');

            if (!current_user_can('manage_options')) {
                throw new Exception(__('Unauthorized access', 'menu-qr-pro'));
            }

            $key = sanitize_text_field($_POST['key']);
            $value = wp_unslash($_POST['value']);

            if (empty($key) || empty($value)) {
                throw new Exception('Missing required parameters');
            }

            // JSON Validation
            $decoded = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON: ' . json_last_error_msg());
            }

            // Sanitize the data recursively
            $sanitized = $this->sanitize_menu_data($decoded);

            // Re-encode to ensure clean JSON
            $value = wp_json_encode($sanitized);

            // Save to database
            $result = update_option('mickey_' . $key, $value);

            if ($result === false && get_option('mickey_' . $key) !== $value) {
                throw new Exception('Failed to save data');
            }

            wp_send_json_success('Data saved successfully');

        } catch (Exception $e) {
            // Log error for debugging
            error_log('Mickey QR Menu Save Error: ' . $e->getMessage());

            // Return error to user
            wp_send_json_error($e->getMessage());
        }
    }

    // AJAX: Get Data
    public function handle_get_data()
    {
        try {
            // Verify nonce for security (even for read-only operations)
            check_ajax_referer('mickey_nonce', 'nonce');

            $key = sanitize_text_field($_GET['key']);

            if (empty($key)) {
                throw new Exception('Missing key parameter');
            }

            // Get data from database
            $data = get_option('mickey_' . $key, null);

            wp_send_json_success($data);

        } catch (Exception $e) {
            // Log error for debugging
            error_log('Mickey QR Menu Get Error: ' . $e->getMessage());

            // Return error to user
            wp_send_json_error($e->getMessage());
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

                        echo '
<link rel="preconnect" href="https://fonts.googleapis.com">';
                        echo '
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
                        echo '
<link href="' . esc_url($base_url) . '" rel="stylesheet">';
                    }
                }

                echo '<style id="mickeys-dynamic-styles">
    ';
                echo '.mqrp-menu-wrapper {';

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

                // Category Styling
                $catSpacing = isset($settings['categorySpacing']) ? $settings['categorySpacing'] : '10';
                $catPaddingConf = isset($settings['categoryPadding']) ? $settings['categoryPadding'] : 'medium';
                $catPadding = '1.5rem'; // medium
                if ($catPaddingConf === 'small')
                    $catPadding = '1rem';
                if ($catPaddingConf === 'large')
                    $catPadding = '2rem';

                echo '--category-spacing: ' . esc_attr($catSpacing) . 'px;';
                echo '--category-padding: ' . esc_attr($catPadding) . ';';

                // Category Header Height
                $catHeaderHeight = isset($settings['categoryHeaderHeight']) ? intval($settings['categoryHeaderHeight']) : 70;
                echo '--category-header-height: ' . esc_attr($catHeaderHeight) . 'px;';

                // Category Background Size
                $catBgSize = isset($settings['categoryBgSize']) ? $settings['categoryBgSize'] : '100% 100%';
                echo '--category-bg-size: ' . esc_attr($catBgSize) . ';';

                echo '}';

                // Apply Styles
                if (!empty($settings['bgColor'])) {
                    echo '.mqrp-menu-wrapper { background-color: var(--bg-body); }';
                    echo 'body { background-color: var(--bg-body); }';
                }

                // Width constraints - apply to main containers
                echo '.menu-accordion { max-width: var(--container-max-width) !important; margin: 0 auto; }';
                echo '.header-content { max-width: var(--container-max-width) !important; margin: 0 auto; }';
                echo '.banner-slider { max-width: var(--container-max-width) !important; margin: 0 auto; }';
                echo '.footer .container { max-width: var(--container-max-width) !important; margin: 0 auto; }';

                // Force Full Width Breakout
                if (isset($settings['forceFullWidth']) && $settings['forceFullWidth']) {
                    $breakoutCSS = 'width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw;';
                    echo '.mqrp-menu-wrapper { ' . $breakoutCSS . ' }';
                    // Reset max-width for internal content
                    echo '.mqrp-menu-wrapper > * { max-width: var(--container-max-width); margin-left: auto; margin-right: auto; }';
                    // Except header, hero-banner and footer which handle their own width
                    echo '.header, .hero-banner, .footer { width: 100vw; max-width: none; }';
                    // Menu accordion needs to respect container
                    echo '.menu-accordion { width: 100%; }';
                }

                echo '.footer-grid { max-width: var(--container-max-width) !important; margin: 0 auto; }';

                // View Mode
                $viewMode = isset($settings['viewMode']) ? $settings['viewMode'] : 'grid';
                echo '.mqrp-menu-wrapper { --view-mode: ' . esc_attr($viewMode) . '; }';
                echo '.menu-accordion[data-view="' . esc_attr($viewMode) . '"] { }';

                // Dark Mode
                $darkMode = isset($settings['darkMode']) ? $settings['darkMode'] : 'light';

                if ($darkMode === 'dark') {
                    echo '.mqrp-menu-wrapper { color-scheme: dark; }';
                } elseif ($darkMode === 'auto') {
                    echo '@media (prefers-color-scheme: dark) { .mqrp-menu-wrapper { color-scheme: dark; } }';
                }

                // Footer
                echo 'footer h3 { color: var(--footer-header-color) !important; }';
                echo 'footer p, footer a, footer li { color: var(--footer-text-color) !important; }';
                echo 'footer .social-link { color: var(--footer-text-color) !important; border-color: var(--footer-text-color) !important; }';

                // Typography
                echo 'h1, h2.category-title { letter-spacing: var(--header-spacing); }';
                echo 'h2.category-title { font-size: var(--header-font-size); }';

                echo '
</style>';
            }
        }
    }

    /**
     * Recursively sanitize menu data
     *
     * @param mixed $data Data to sanitize
     * @return mixed Sanitized data
     */
    private function sanitize_menu_data($data)
    {
        if (is_array($data)) {
            return array_map(array($this, 'sanitize_menu_data'), $data);
        }

        if (is_string($data)) {
            // Allow specific HTML tags in descriptions
            $allowed_tags = array(
                'br' => array(),
                'strong' => array(),
                'em' => array(),
                'b' => array(),
                'i' => array(),
            );
            return wp_kses($data, $allowed_tags);
        }

        // Return other types as-is (numbers, booleans, etc.)
        return $data;
    }
}

new MickeysQRMenu();