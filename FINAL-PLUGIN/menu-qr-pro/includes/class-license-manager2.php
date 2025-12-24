<?php
/**
 * License Manager (Client Side) - Firebase Version
 * 
 * Handles license validation, activation, and communication with Firebase license server
 *
 * @package Menu_QR_Pro
 */

class Mickey_License_Manager
{

    private $license_server_url;
    private $api_key;
    private $cache_duration = 604800; // 7 days in seconds
    private $grace_period = 1209600; // 14 days in seconds

    public function __construct()
    {
        // Firebase Functions URL - luiff-qr-menu-for-wp projesi
        // Deploy sonrası bu URL aktif olacak
        $this->license_server_url = 'https://us-central1-luiff-qr-menu-for-wp.cloudfunctions.net/api';

        // API Key - Firebase config ile ayarlanacak
        // firebase functions:config:set mqpro.apikey="YOUR_KEY"
        $this->api_key = get_option('mickey_firebase_api_key', 'mqpro_temp_key_will_be_updated');

        // Schedule daily heartbeat check
        if (!wp_next_scheduled('mickey_license_heartbeat')) {
            wp_schedule_event(time(), 'daily', 'mickey_license_heartbeat');
        }

        add_action('mickey_license_heartbeat', array($this, 'heartbeat_check'));
    }

    /**
     * Check if license is valid
     */
    public function is_valid()
    {
        $license_data = $this->get_license_info();

        if (!$license_data) {
            return false;
        }

        // Check if active and not expired
        if ($license_data['status'] === 'active' && $license_data['valid']) {
            return true;
        }

        // Check grace period
        if ($this->is_in_grace_period()) {
            return true;
        }

        return false;
    }

    /**
     * Get cached license information
     */
    public function get_license_info()
    {
        $cache_key = 'mickey_license_cache';
        $cached = get_transient($cache_key);

        if ($cached && is_array($cached)) {
            return $cached;
        }

        // No cache, validate remotely
        return $this->validate_remote();
    }

    /**
     * Validate license with Firebase
     */
    public function validate_remote()
    {
        $license_key = get_option('mickey_license_key');
        $domain = $this->get_domain();

        if (empty($license_key)) {
            return false;
        }

        $response = wp_remote_post($this->license_server_url . '/validate', array(
            'headers' => array(
                'X-API-Key' => $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'license_key' => $license_key,
                'domain' => $domain,
                'plugin_version' => MICKEYS_QR_MENU_VERSION,
                'wp_version' => get_bloginfo('version'),
                'php_version' => phpversion()
            )),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            // Network error - use cached data if available
            error_log('Mickey License: Validation failed - ' . $response->get_error_message());
            return $this->get_cached_license();
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['data'])) {
            // Cache the response
            set_transient('mickey_license_cache', $data['data'], $this->cache_duration);
            update_option('mickey_license_last_check', current_time('mysql'));

            return $data['data'];
        }

        return false;
    }

    /**
     * Activate license
     */
    public function activate_license($license_key, $customer_email = '')
    {
        $domain = $this->get_domain();

        $response = wp_remote_post($this->license_server_url . '/activate', array(
            'headers' => array(
                'X-API-Key' => $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'license_key' => $license_key,
                'domain' => $domain,
                'customer_email' => $customer_email,
                'plugin_version' => MICKEYS_QR_MENU_VERSION
            )),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => $response->get_error_message()
            );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['success']) && $data['success']) {
            // Save license key
            update_option('mickey_license_key', $license_key);
            update_option('mickey_license_activated_at', current_time('mysql'));

            // Clear cache
            delete_transient('mickey_license_cache');

            // Fetch fresh license data
            $this->validate_remote();

            return array(
                'success' => true,
                'message' => __('License activated successfully!', 'menu-qr-pro')
            );
        }

        return array(
            'success' => false,
            'message' => isset($data['error']['message']) ? $data['error']['message'] : __('Activation failed', 'menu-qr-pro')
        );
    }

    /**
     * Deactivate license
     */
    public function deactivate_license()
    {
        $license_key = get_option('mickey_license_key');
        $domain = $this->get_domain();

        if (empty($license_key)) {
            return false;
        }

        $response = wp_remote_post($this->license_server_url . '/deactivate', array(
            'headers' => array(
                'X-API-Key' => $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'license_key' => $license_key,
                'domain' => $domain
            )),
            'timeout' => 15
        ));

        if (!is_wp_error($response)) {
            // Clear local data
            delete_option('mickey_license_key');
            delete_option('mickey_license_activated_at');
            delete_transient('mickey_license_cache');

            return true;
        }

        return false;
    }

    /**
     * Daily heartbeat check
     */
    public function heartbeat_check()
    {
        $this->validate_remote();
    }

    /**
     * Check if in grace period
     */
    public function is_in_grace_period()
    {
        $last_check = get_option('mickey_license_last_check');

        if (!$last_check) {
            return false;
        }

        $time_since_check = time() - strtotime($last_check);

        return $time_since_check <= $this->grace_period;
    }

    /**
     * Get current domain
     */
    private function get_domain()
    {
        $domain = $_SERVER['HTTP_HOST'];
        // Remove www
        $domain = str_replace('www.', '', $domain);
        return $domain;
    }

    /**
     * Get cached license (fallback)
     */
    private function get_cached_license()
    {
        return get_transient('mickey_license_cache');
    }

    /**
     * Get days remaining
     */
    public function get_days_remaining()
    {
        $license_data = $this->get_license_info();

        if (!$license_data || !isset($license_data['days_remaining'])) {
            return 0;
        }

        return intval($license_data['days_remaining']);
    }

    /**
     * Show expiry warning
     */
    public function should_show_expiry_warning()
    {
        $days = $this->get_days_remaining();
        return $days <= 30 && $days > 0;
    }

    /**
     * Is expired
     */
    public function is_expired()
    {
        $license_data = $this->get_license_info();

        if (!$license_data) {
            return true; // No license = expired
        }

        return !$license_data['valid'] || $license_data['status'] !== 'active';
    }
}
