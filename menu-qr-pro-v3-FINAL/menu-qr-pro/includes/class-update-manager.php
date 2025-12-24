<?php
/**
 * Update Manager
 * 
 * Handles plugin updates with license verification
 *
 * @package Menu_QR_Pro
 */

class Mickey_Update_Manager
{

    private $license_manager;
    private $plugin_slug;
    private $plugin_file;
    private $version;
    private $update_url;
    private $api_key;

    public function __construct($license_manager)
    {
        $this->license_manager = $license_manager;
        $this->plugin_slug = 'menu-qr-pro';
        $this->plugin_file = 'menu-qr-pro/menu-qr-pro.php';
        $this->version = MICKEYS_QR_MENU_VERSION;
        $this->update_url = 'https://license.yourcompany.com/wp-json/mqpro-license/v1/check-update';
        $this->api_key = get_option('mickey_api_key', '');

        // Hook into WordPress update system
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'plugin_info'), 10, 3);
    }

    /**
     * Check for plugin updates
     */
    public function check_update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        // Check if license is valid
        if (!$this->license_manager->is_valid()) {
            // Don't show updates for unlicensed users
            return $transient;
        }

        $license_key = get_option('mickey_license_key');

        if (empty($license_key)) {
            return $transient;
        }

        // Check remote for updates
        $remote = $this->request_update_info($license_key);

        if ($remote && isset($remote->update_available) && $remote->update_available) {
            // Compare versions
            if (version_compare($this->version, $remote->latest_version, '<')) {
                $transient->response[$this->plugin_file] = (object) array(
                    'slug' => $this->plugin_slug,
                    'new_version' => $remote->latest_version,
                    'package' => $remote->download_url,
                    'tested' => $remote->requires_wp,
                    'requires_php' => $remote->requires_php,
                );
            }
        }

        return $transient;
    }

    /**
     * Request update info from server
     */
    private function request_update_info($license_key)
    {
        $response = wp_remote_get($this->update_url, array(
            'headers' => array(
                'X-API-Key' => $this->api_key,
                'X-License-Key' => $license_key
            ),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if (isset($data->success) && $data->success) {
            return $data;
        }

        return false;
    }

    /**
     * Plugin information for update modal
     */
    public function plugin_info($result, $action, $args)
    {
        if ($action !== 'plugin_information') {
            return $result;
        }

        if ($args->slug !== $this->plugin_slug) {
            return $result;
        }

        $license_key = get_option('mickey_license_key');
        $remote = $this->request_update_info($license_key);

        if ($remote) {
            $result = (object) array(
                'name' => 'Menu QR Pro',
                'slug' => $this->plugin_slug,
                'version' => $remote->latest_version,
                'author' => 'Your Company',
                'homepage' => 'https://yourcompany.com',
                'requires' => $remote->requires_wp,
                'requires_php' => $remote->requires_php,
                'tested' => $remote->requires_wp,
                'download_link' => $remote->download_url,
                'sections' => array(
                    'description' => 'Professional QR Menu & Digital Menu solution for restaurants.',
                    'changelog' => $remote->changelog
                ),
            );
        }

        return $result;
    }

    /**
     * Show update notice for unlicensed users
     */
    public function show_unlicensed_notice()
    {
        if (!$this->license_manager->is_valid()) {
            ?>
            <div class="notice notice-warning">
                <p>
                    <strong><?php _e('Menu QR Pro Updates Disabled', 'menu-qr-pro'); ?></strong><br>
                    <?php _e('Please activate your license to receive updates.', 'menu-qr-pro'); ?>
                    <a
                        href="<?php echo admin_url('admin.php?page=mickeys-qr-license'); ?>"><?php _e('Activate License', 'menu-qr-pro'); ?></a>
                </p>
            </div>
            <?php
        }
    }
}
