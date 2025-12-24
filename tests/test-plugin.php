<?php
/**
 * Class PluginTest
 *
 * @package Menu_QR_Pro
 */

/**
 * Plugin test case
 */
class PluginTest extends WP_UnitTestCase
{

    /**
     * Test if plugin is loaded
     */
    public function test_plugin_loaded()
    {
        $this->assertTrue(class_exists('MickeysQRMenu'));
    }

    /**
     * Test plugin constants
     */
    public function test_plugin_constants()
    {
        $this->assertTrue(defined('MICKEYS_QR_MENU_PATH'));
        $this->assertTrue(defined('MICKEYS_QR_MENU_URL'));
        $this->assertTrue(defined('MICKEYS_QR_MENU_VERSION'));
        $this->assertEquals('2.0.1', MICKEYS_QR_MENU_VERSION);
    }

    /**
     * Test text domain loading
     */
    public function test_textdomain_loaded()
    {
        $this->assertTrue(is_textdomain_loaded('menu-qr-pro'));
    }

    /**
     * Test shortcode registration
     */
    public function test_shortcode_exists()
    {
        $this->assertTrue(shortcode_exists('mickeys_qr_menu'));
    }

    /**
     * Test AJAX actions registered
     */
    public function test_ajax_actions()
    {
        $this->assertTrue(has_action('wp_ajax_mickey_save_data'));
        $this->assertTrue(has_action('wp_ajax_mickey_get_data'));
        $this->assertTrue(has_action('wp_ajax_nopriv_mickey_get_data'));
    }

    /**
     * Test admin menu added
     */
    public function test_admin_menu()
    {
        global $menu;
        set_current_screen('dashboard');

        $this->assertTrue(is_admin());
        // Admin menu tests would go here
    }
}
