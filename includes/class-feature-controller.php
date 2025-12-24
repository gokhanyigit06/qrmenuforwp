<?php
/**
 * Feature Controller
 * 
 * Controls feature access based on license status
 *
 * @package Menu_QR_Pro
 */

class Mickey_Feature_Controller
{

    private $license_manager;

    public function __construct($license_manager)
    {
        $this->license_manager = $license_manager;
    }

    /**
     * Check if user can edit products
     */
    public function can_edit_products()
    {
        return $this->license_manager->is_valid();
    }

    /**
     * Check if user can edit categories
     */
    public function can_edit_categories()
    {
        return $this->license_manager->is_valid();
    }

    /**
     * Check if user can edit allergens
     */
    public function can_edit_allergens()
    {
        return $this->license_manager->is_valid();
    }

    /**
     * Check if user can edit banners
     */
    public function can_edit_banners()
    {
        return $this->license_manager->is_valid();
    }

    /**
     * Check if user can manage settings
     */
    public function can_manage_settings()
    {
        return $this->license_manager->is_valid();
    }

    /**
     * Check if user can import/export
     */
    public function can_import_export()
    {
        return $this->license_manager->is_valid();
    }

    /**
     * Check if can access updates
     */
    public function can_access_updates()
    {
        return $this->license_manager->is_valid();
    }

    /**
     * Check if license is expired
     */
    public function is_expired()
    {
        return $this->license_manager->is_expired();
    }

    /**
     * Get expiry notice HTML
     */
    public function get_expiry_notice()
    {
        if (!$this->license_manager->is_valid()) {
            $days = $this->license_manager->get_days_remaining();

            if ($days <= 0) {
                // Expired
                return $this->render_expired_notice();
            } elseif ($days <= 7) {
                // Urgent warning
                return $this->render_urgent_warning($days);
            } elseif ($days <= 30) {
                // Warning
                return $this->render_warning($days);
            }
        }

        return '';
    }

    /**
     * Render expired notice
     */
    private function render_expired_notice()
    {
        ob_start();
        ?>
        <div class="notice notice-error" style="border-left-color: #dc3232;">
            <h3><?php _e('License Expired - Read-Only Mode', 'menu-qr-pro'); ?></h3>
            <p>
                <?php _e('Your license has expired. The menu is still visible to customers, but you cannot make changes.', 'menu-qr-pro'); ?>
            </p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=mickeys-qr-license'); ?>" class="button button-primary">
                    <?php _e('Renew License', 'menu-qr-pro'); ?>
                </a>
                <a href="mailto:support@yourcompany.com" class="button">
                    <?php _e('Contact Support', 'menu-qr-pro'); ?>
                </a>
            </p>
        </div>
        <style>
            /* Disable editing */
            .mickeys-admin-wrapper input:not([type="button"]):not([type="submit"]),
            .mickeys-admin-wrapper textarea,
            .mickeys-admin-wrapper select,
            .mickeys-admin-wrapper button:not(.renewal-button) {
                opacity: 0.6;
                pointer-events: none;
                cursor: not-allowed !important;
            }
        </style>
        <?php
        return ob_get_clean();
    }

    /**
     * Render urgent warning
     */
    private function render_urgent_warning($days)
    {
        ob_start();
        ?>
        <div class="notice notice-warning is-dismissible" style="border-left-color: #ffb900;">
            <h3><?php _e('License Expiring Soon!', 'menu-qr-pro'); ?></h3>
            <p>
                <?php printf(__('Your license expires in <strong>%d days</strong>. Renew now to avoid interruption.', 'menu-qr-pro'), $days); ?>
            </p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=mickeys-qr-license'); ?>" class="button button-primary">
                    <?php _e('Renew Now', 'menu-qr-pro'); ?>
                </a>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render warning
     */
    private function render_warning($days)
    {
        ob_start();
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <?php printf(__('Your license expires in %d days.', 'menu-qr-pro'), $days); ?>
                <a
                    href="<?php echo admin_url('admin.php?page=mickeys-qr-license'); ?>"><?php _e('Renew now', 'menu-qr-pro'); ?></a>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Block admin page if expired
     */
    public function block_if_expired()
    {
        if ($this->is_expired()) {
            ?>
            <div class="wrap">
                <h1><?php _e('License Expired', 'menu-qr-pro'); ?></h1>
                <?php echo $this->get_expiry_notice(); ?>

                <div style="background: #f8f9fa; padding: 30px; text-align: center; margin-top: 20px;">
                    <h2><?php _e('Your Menu is Still Live!', 'menu-qr-pro'); ?></h2>
                    <p><?php _e('Customers can still view your menu. However, you cannot make changes until you renew your license.', 'menu-qr-pro'); ?>
                    </p>

                    <div style="margin-top: 30px;">
                        <a href="<?php echo admin_url('admin.php?page=mickeys-qr-license'); ?>"
                            class="button button-primary button-hero">
                            <?php _e('Renew License', 'menu-qr-pro'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            return true; // Page was blocked
        }

        return false; // Not blocked
    }
}
