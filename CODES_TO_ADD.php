// ==========================================
// MENU-QR-PRO.PHP İÇİN EKLENMESİ GEREKEN KODLAR
// ==========================================

// 1️⃣ add_admin_menu() fonksiyonunu TAMAMEN değiştirin (Satır 140-151):

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

// ==========================================

// 2️⃣ render_admin_page() fonksiyonunu TAMAMEN değiştirin (Satır 153-158):

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

// ==========================================

// 3️⃣ render_admin_page() fonksiyonundan HEMEN SONRA ekleyin (Satır 159'dan sonra):

public function render_license_page()
{
include MICKEYS_QR_MENU_PATH . 'admin-pages/page-license.php';
}

public function show_license_notices()
{
// Show expiry warnings on all admin pages
echo $this->feature_controller->get_expiry_notice();
}

// ==========================================
// NOTLAR:
// - Kodları aynen kopyalayıp yapıştırın
// - Satır numaralarına dikkat edin
// - Kaydetmeyi unutmayın!
// ==========================================