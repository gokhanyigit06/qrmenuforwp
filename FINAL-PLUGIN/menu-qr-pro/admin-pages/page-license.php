<?php
/**
 * License Page (Client Admin)
 */

if (!defined('ABSPATH'))
    exit;

global $mickey_license_manager;

// Handle activation
if (isset($_POST['activate_license']) && check_admin_referer('mickey_activate_license')) {
    $license_key = sanitize_text_field($_POST['license_key']);
    $customer_email = sanitize_email($_POST['customer_email']);

    $result = $mickey_license_manager->activate_license($license_key, $customer_email);

    if ($result['success']) {
        echo '<div class="notice notice-success"><p>' . esc_html($result['message']) . '</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>' . esc_html($result['message']) . '</p></div>';
    }
}

// Handle deactivation
if (isset($_POST['deactivate_license']) && check_admin_referer('mickey_deactivate_license')) {
    if ($mickey_license_manager->deactivate_license()) {
        echo '<div class="notice notice-success"><p>' . __('License deactivated successfully', 'menu-qr-pro') . '</p></div>';
    }
}

$license_info = $mickey_license_manager->get_license_info();
$is_active = $license_info && $license_info['valid'];
$license_key = get_option('mickey_license_key', '');
?>

<div class="wrap">
    <h1><?php _e('License & Package Information', 'menu-qr-pro'); ?></h1>

    <?php if ($is_active): ?>
        <!-- Active License -->
        <div class="mickey-license-active"
            style="background: #fff; padding: 30px; margin: 20px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <span style="font-size: 48px; margin-right: 20px;">✅</span>
                <div>
                    <h2 style="margin: 0; color: #46b450;"><?php _e('License Active', 'menu-qr-pro'); ?></h2>
                    <p style="margin: 5px 0 0; font-size: 18px; color: #666;">
                        <?php printf(__('%d days remaining', 'menu-qr-pro'), $license_info['days_remaining']); ?>
                    </p>
                </div>
            </div>

            <!-- Progress Bar -->
            <?php
            $total_days = 365; // Yearly license
            $remaining = $license_info['days_remaining'];
            $percentage = min(100, ($remaining / $total_days) * 100);
            $color = $percentage > 50 ? '#46b450' : ($percentage > 25 ? '#ffb900' : '#dc3232');
            ?>
            <div style="background: #e0e0e0; height: 20px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                <div
                    style="background: <?php echo $color; ?>; height: 100%; width: <?php echo $percentage; ?>%; transition: width 0.3s;">
                </div>
            </div>

            <!-- License Details -->
            <table class="form-table" style="margin-top: 20px;">
                <tr>
                    <th style="width: 200px;"><?php _e('License Key', 'menu-qr-pro'); ?>:</th>
                    <td><code
                            style="font-size: 14px; background: #f5f5f5; padding: 5px 10px;"><?php echo esc_html($license_key); ?></code>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Domain', 'menu-qr-pro'); ?>:</th>
                    <td><?php echo esc_html($license_info['domain']); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Package Type', 'menu-qr-pro'); ?>:</th>
                    <td><?php echo esc_html(ucfirst($license_info['package_type'])); ?>
                        <?php _e('Package', 'menu-qr-pro'); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Expiry Date', 'menu-qr-pro'); ?>:</th>
                    <td><?php echo esc_html(date('d F Y', strtotime($license_info['expires_at']))); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Customer ID', 'menu-qr-pro'); ?>:</th>
                    <td><?php echo esc_html($license_info['customer_id']); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Status', 'menu-qr-pro'); ?>:</th>
                    <td>
                        <span
                            style="display: inline-block; padding: 5px 15px; background: #46b450; color: #fff; border-radius: 3px;">
                            <?php echo esc_html(ucfirst($license_info['status'])); ?>
                        </span>
                    </td>
                </tr>
            </table>

            <!-- Actions -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <?php if ($license_info['days_remaining'] <= 30): ?>
                    <a href="mailto:support@yourcompany.com?subject=License Renewal - <?php echo urlencode($license_key); ?>"
                        class="button button-primary button-large">
                        <?php _e('Renew License', 'menu-qr-pro'); ?>
                    </a>
                <?php endif; ?>

                <form method="post" style="display: inline-block; margin-left: 10px;">
                    <?php wp_nonce_field('mickey_deactivate_license'); ?>
                    <button type="submit" name="deactivate_license" class="button button-secondary"
                        onclick="return confirm('<?php _e('Are you sure you want to deactivate this license?', 'menu-qr-pro'); ?>');">
                        <?php _e('Deactivate License', 'menu-qr-pro'); ?>
                    </button>
                </form>
            </div>
        </div>

    <?php else: ?>
        <!-- Activate License -->
        <div class="mickey-license-inactive"
            style="background: #fff; padding: 30px; margin: 20px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 600px;">
            <h2><?php _e('Activate License', 'menu-qr-pro'); ?></h2>
            <p><?php _e('Please enter your license key to activate Menu QR Pro.', 'menu-qr-pro'); ?></p>

            <form method="post" action="">
                <?php wp_nonce_field('mickey_activate_license'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="license_key"><?php _e('License Key', 'menu-qr-pro'); ?> *</label>
                        </th>
                        <td>
                            <input type="text" name="license_key" id="license_key" class="regular-text"
                                placeholder="MQPRO-XXXX-XXXX-XXXX-XXXX" required
                                style="font-family: monospace; font-size: 14px;">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="customer_email"><?php _e('Your Email', 'menu-qr-pro'); ?></label>
                        </th>
                        <td>
                            <input type="email" name="customer_email" id="customer_email" class="regular-text"
                                value="<?php echo esc_attr(get_option('admin_email')); ?>">
                            <p class="description"><?php _e('Optional - for support purposes', 'menu-qr-pro'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Domain', 'menu-qr-pro'); ?>:</th>
                        <td>
                            <strong><?php echo esc_html($_SERVER['HTTP_HOST']); ?></strong>
                            <p class="description"><?php _e('License will be locked to this domain', 'menu-qr-pro'); ?></p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="activate_license" class="button button-primary button-large"
                        value="<?php _e('Activate License', 'menu-qr-pro'); ?>">
                </p>
            </form>
        </div>
    <?php endif; ?>

    <!-- Support Information -->
    <div class="mickey-support-info"
        style="background: #fff; padding: 30px; margin: 20px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2><?php _e('Technical Support', 'menu-qr-pro'); ?></h2>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px;">
            <div style="padding: 20px; background: #f8f9fa; border-radius: 5px; text-align: center;">
                <span style="font-size: 32px;">📧</span>
                <h3 style="margin: 10px 0 5px;">Email</h3>
                <a href="mailto:support@yourcompany.com">support@yourcompany.com</a>
            </div>

            <div style="padding: 20px; background: #f8f9fa; border-radius: 5px; text-align: center;">
                <span style="font-size: 32px;">💬</span>
                <h3 style="margin: 10px 0 5px;">WhatsApp</h3>
                <a href="https://wa.me/905551234567" target="_blank">+90 555 123 45 67</a>
            </div>

            <div style="padding: 20px; background: #f8f9fa; border-radius: 5px; text-align: center;">
                <span style="font-size: 32px;">📚</span>
                <h3 style="margin: 10px 0 5px;">Documentation</h3>
                <a href="https://yourcompany.com/docs" target="_blank"><?php _e('View Docs', 'menu-qr-pro'); ?></a>
            </div>
        </div>

        <?php if ($is_active): ?>
            <div
                style="margin-top: 30px; padding: 20px; background: #e7f5fe; border-left: 4px solid #0073aa; border-radius: 3px;">
                <p style="margin: 0;">
                    <strong><?php _e('Need help?', 'menu-qr-pro'); ?></strong><br>
                    <?php _e('As a licensed customer, you have access to priority support. Contact us anytime!', 'menu-qr-pro'); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Package Information -->
    <?php if (!$is_active): ?>
        <div class="mickey-package-info"
            style="background: #fff; padding: 30px; margin: 20px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2><?php _e('Need a License?', 'menu-qr-pro'); ?></h2>
            <p><?php _e('Contact us to purchase Menu QR Pro license:', 'menu-qr-pro'); ?></p>

            <div style="margin: 20px 0;">
                <a href="mailto:sales@yourcompany.com?subject=Menu QR Pro License"
                    class="button button-primary button-large">
                    <?php _e('Purchase License', 'menu-qr-pro'); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>