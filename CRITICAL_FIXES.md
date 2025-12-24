# 🚨 ACİL DÜZELTME LİSTESİ - QR Menu Pro

## KRİTİK GÜVENLİK AÇIKLARI (ŞİMDİ DÜZELTİLMELİ!)

### ✅ Düzeltme Checklist

---

## 1. ❌ CSRF Koruması Eksik (frontend-view.php)

**Dosya:** `menu-qr-pro.php` - Line 143-156

### 🔴 Sorun:
```php
public function handle_get_data() {
    // Optionally verify nonce for frontend read-only if strict
    // check_ajax_referer('mickey_nonce', 'nonce');  ❌ YORUM SATIRI!
```

### ✅ Çözüm:
```php
public function handle_get_data() {
    // Frontend için de nonce kontrolü ekle
    check_ajax_referer('mickey_nonce', 'nonce');
    
    $key = sanitize_text_field($_GET['key']);
    
    if ($key) {
        $data = get_option('mickey_' . $key, null);
        wp_send_json_success($data);
    } else {
        wp_send_json_error('Missing key');
    }
}
```

---

## 2. ❌ JSON Validation Eksik

**Dosya:** `menu-qr-pro.php` - Line 122-140

### 🔴 Sorun:
```php
public function handle_save_data() {
    check_ajax_referer('mickey_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }
    
    $key = sanitize_text_field($_POST['key']);
    $value = wp_unslash($_POST['value']); // ❌ Doğrulama yok!
    
    if ($key && $value) {
        update_option('mickey_' . $key, $value);  // ❌ Tehlikeli!
        wp_send_json_success('Saved');
    }
}
```

### ✅ Çözüm:
```php
public function handle_save_data() {
    check_ajax_referer('mickey_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }
    
    $key = sanitize_text_field($_POST['key']);
    $value = wp_unslash($_POST['value']);
    
    // JSON Validasyonu
    if (!empty($value)) {
        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error('Invalid JSON: ' . json_last_error_msg());
            return;
        }
        
        // Sanitize işlemi (array içindeki değerler için)
        $value = $this->sanitize_menu_data($decoded);
        
        // Tekrar JSON'a çevir
        $value = wp_json_encode($value);
    }
    
    if ($key && $value) {
        update_option('mickey_' . $key, $value);
        wp_send_json_success('Saved');
    } else {
        wp_send_json_error('Missing data');
    }
}

// Yeni helper fonksiyon ekle
private function sanitize_menu_data($data) {
    if (is_array($data)) {
        return array_map(array($this, 'sanitize_menu_data'), $data);
    }
    
    if (is_string($data)) {
        return sanitize_text_field($data);
    }
    
    return $data;
}
```

---

## 3. ❌ Duplicate Footer Tag

**Dosya:** `frontend-view.php` - Line 148-149

### 🔴 Sorun:
```php
<footer class="footer">
    <footer class="footer">  <!-- ❌ DUPLICATE! -->
        <div class="container"...>
```

### ✅ Çözüm:
```php
<footer class="footer">
    <div class="container" style="display: flex; flex-direction: column; align-items: center;">
```

**İşlem:** Line 149'daki duplicate `<footer>` tag'ini sil.

---

## 4. ❌ Cache Busting Performans Sorunu

**Dosya:** `menu-qr-pro.php` - Line 42, 60

### 🔴 Sorun:
```php
$version = time(); // ❌ Her request'te farklı versiyonu zorluyor
```

### ✅ Çözüm:
```php
// Plugin başında constant tanımla
if (!defined('MICKEYS_QR_MENU_VERSION')) {
    define('MICKEYS_QR_MENU_VERSION', '2.0.0');
}

// Enqueue fonksiyonlarında kullan
public function enqueue_frontend_assets() {
    $version = MICKEYS_QR_MENU_VERSION;
    
    // Development modda time() kullan
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $version = time();
    }
    
    wp_register_style('mickeys-style', MICKEYS_QR_MENU_URL . 'style.css', array(), $version);
    wp_register_script('mickeys-menu-data', MICKEYS_QR_MENU_URL . 'menu-data.js', array(), $version, true);
    wp_register_script('mickeys-app', MICKEYS_QR_MENU_URL . 'script.js', array('mickeys-menu-data', 'jquery'), $version, true);
    
    wp_localize_script('mickeys-app', 'mickeyData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mickey_nonce')
    ));
}
```

---

## 5. ❌ External CDN Riski

**Dosya:** `menu-qr-pro.php` - Line 62

### 🔴 Sorun:
```php
wp_enqueue_script('xlsx', 
    'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js', 
    array(), '0.18.5', true);
```

**Riskler:**
- CDN çökerse Excel özellikleri çalışmaz
- MITM saldırısı riski
- GDPR uyumluluk sorunu

### ✅ Çözüm:

**Adım 1:** XLSX kütüphanesini indir
```bash
# Plugin klasöründe assets klasörü oluştur
mkdir assets/js
cd assets/js

# XLSX kütüphanesini indir
curl -o xlsx.full.min.js https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js
```

**Adım 2:** Kodu güncelle
```php
public function enqueue_admin_assets($hook) {
    if ($hook != 'toplevel_page_mickeys-qr-admin') {
        return;
    }
    
    $version = MICKEYS_QR_MENU_VERSION;
    
    wp_enqueue_style('mickeys-admin-style', MICKEYS_QR_MENU_URL . 'admin-style.css', array(), $version);
    
    // Local XLSX kullan
    wp_enqueue_script('xlsx', 
        MICKEYS_QR_MENU_URL . 'assets/js/xlsx.full.min.js', 
        array(), '0.18.5', true);
    
    wp_enqueue_media();
    
    wp_enqueue_script('mickeys-menu-data', MICKEYS_QR_MENU_URL . 'menu-data.js', array(), $version, true);
    wp_enqueue_script('mickeys-admin-script', MICKEYS_QR_MENU_URL . 'admin-script.js', 
        array('jquery', 'jquery-ui-sortable', 'xlsx', 'mickeys-menu-data'), $version, true);
    
    wp_localize_script('mickeys-admin-script', 'mickeyAdminData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mickey_admin_nonce')
    ));
}
```

---

## 6. ❌ Console.log Production'da

**Dosya:** `admin-script.js`, `script.js`

### 🔴 Sorun:
```javascript
console.log('admin-script.js loaded!');  // ❌ Production'da olmamalı
console.log('Nav clicked, section:', this.dataset.section);  // ❌
```

### ✅ Çözüm:

**admin-script.js başına ekle:**
```javascript
// Debug mode
const MICKEY_DEBUG = (typeof mickeyAdminData !== 'undefined' && mickeyAdminData.debug) || false;

function debugLog(...args) {
    if (MICKEY_DEBUG) {
        console.log('[Mickey QR Menu]', ...args);
    }
}

// Tüm console.log() yerine debugLog() kullan
debugLog('admin-script.js loaded!');
```

**PHP tarafına debug flag ekle:**
```php
wp_localize_script('mickeys-admin-script', 'mickeyAdminData', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('mickey_admin_nonce'),
    'debug' => (defined('WP_DEBUG') && WP_DEBUG)
));
```

---

## 7. ❌ Error Handling Eksik

### ✅ Çözüm: Try-Catch Ekle

```php
public function handle_save_data() {
    try {
        check_ajax_referer('mickey_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            throw new Exception('Unauthorized access');
        }
        
        $key = sanitize_text_field($_POST['key']);
        $value = wp_unslash($_POST['value']);
        
        if (empty($key) || empty($value)) {
            throw new Exception('Missing required parameters');
        }
        
        // JSON Validasyonu
        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON: ' . json_last_error_msg());
        }
        
        // Sanitize
        $value = $this->sanitize_menu_data($decoded);
        $value = wp_json_encode($value);
        
        // Save
        $result = update_option('mickey_' . $key, $value);
        
        if ($result === false) {
            throw new Exception('Failed to save data');
        }
        
        wp_send_json_success('Data saved successfully');
        
    } catch (Exception $e) {
        // Log hatayı
        error_log('Mickey QR Menu Error: ' . $e->getMessage());
        
        // Kullanıcıya göster
        wp_send_json_error($e->getMessage());
    }
}
```

---

## 8. ❌ Text Domain Tutarsızlığı

**Dosya:** `menu-qr-pro.php` - Line 7

### 🔴 Sorun:
```php
/**
 * Plugin Name: Menu QR Pro
 * Text Domain: mickeys-qr-menu  ❌ Plugin slug ile uyumsuz
 */
```

### ✅ Çözüm:
```php
/**
 * Plugin Name: Menu QR Pro
 * Plugin URI: https://yourwebsite.com/menu-qr-pro
 * Description: A dynamic QR Menu & Admin Panel plugin.
 * Version: 2.0.0
 * Author: Luiff Dev
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: menu-qr-pro
 * Domain Path: /languages
 */
```

---

## 9. ❌ Uninstall Hook Eksik

### ✅ Çözüm: uninstall.php Oluştur

**Dosya:** `uninstall.php`
```php
<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('mickey_mickeys_menu_data');
delete_option('mickey_mickeys_categories');
delete_option('mickey_mickeys_allergens');
delete_option('mickey_mickeys_banners');
delete_option('mickey_theme_settings');

// Delete transients (eğer kullanılıyorsa)
delete_transient('mickey_menu_cache');

// Clear any cached data
wp_cache_flush();
```

---

## 10. ❌ i18n Desteği Eksik

### ✅ Çözüm:

**Adım 1: Plugin başına load_plugin_textdomain ekle**
```php
class MickeysQRMenu {
    
    public function __construct() {
        // Load text domain
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // ... diğer hooklar
    }
    
    public function load_textdomain() {
        load_plugin_textdomain(
            'menu-qr-pro',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }
}
```

**Adım 2: Hardcoded string'leri çevir**
```php
// ÖNCESİ
add_menu_page(
    'Mickey\'s Menu Admin',
    'QR Menu Panel',
    ...
);

// SONRASI
add_menu_page(
    __('Menu Admin', 'menu-qr-pro'),
    __('QR Menu Panel', 'menu-qr-pro'),
    ...
);
```

---

## ✅ HIZLI DÜZELTME SIRASI (Öncelik Sırasına Göre)

1. ✅ **Nonce kontrolünü aktif et** (5 dk)
2. ✅ **JSON validasyonu ekle** (15 dk)
3. ✅ **Duplicate footer tag'i düzelt** (1 dk)
4. ✅ **Cache busting'i düzelt** (10 dk)
5. ✅ **XLSX'i local yap** (10 dk)
6. ✅ **Console.log temizle** (20 dk)
7. ✅ **Error handling ekle** (30 dk)
8. ✅ **Text domain düzelt** (5 dk)
9. ✅ **Uninstall hook ekle** (10 dk)
10. ✅ **i18n desteği ekle** (1 saat)

**Toplam Süre: ~3 saat**

---

## 📝 Test Checklist

Düzeltmelerden sonra:

- [ ] Admin panelde ürün ekleme/düzenleme çalışıyor mu?
- [ ] Frontend menü görüntüleniyor mu?
- [ ] AJAX requestler güvenli mi?
- [ ] Cache doğru çalışıyor mu?
- [ ] Excel import/export çalışıyor mu?
- [ ] Console'da hata var mı?
- [ ] Different user role'ler test edildi mi?
- [ ] XSS testi yapıldı mı?
- [ ] CSRF testi yapıldı mı?

---

**Rapor Hazırlayan:** Antigravity AI  
**Tarih:** 2025-12-23  
**Durum:** Acil düzeltme gerekli
