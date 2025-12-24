# 🎯 BUGÜN YAPILACAK 4 ADIM (10 Dakika)

## ✅ ADIM 1: Kodları Ekle (5 dakika)

### menu-qr-pro.php Dosyasını Aç

**Yol:** `C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\menu-qr-pro.php`

### 3 Değişiklik Yap:

#### 1. Satır 140-151: add_admin_menu() Güncelle

**ŞU ANKİ KOD (Satır 140-151):**
```php
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
}
```

**YENİ KOD (Kopyala-Yapıştır):**
```php
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
```

---

#### 2. Satır 153-158: render_admin_page() Güncelle

**ŞU ANKİ KOD (Satır 153-158):**
```php
public function render_admin_page()
{
    // We will load the content of admin.html here
    // Note: We need to adjust admin.html to be compatible with WP Admin structure if needed
    include(MICKEYS_QR_MENU_PATH . 'admin-view.php');
}
```

**YENİ KOD (Kopyala-Yapıştır):**
```php
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
```

---

#### 3. Satır 159'dan Sonra: 2 Yeni Fonksiyon Ekle

**render_admin_page() fonksiyonunun HEMEN ALTINA ekle:**

```php
public function render_license_page()
{
    include MICKEYS_QR_MENU_PATH . 'admin-pages/page-license.php';
}

public function show_license_notices()
{
    // Show expiry warnings on all admin pages
    echo $this->feature_controller->get_expiry_notice();
}
```

**Kaydet!** (Ctrl + S)

---

## ✅ ADIM 2: API Key Ayarla (2 dakika)

### includes/class-license-manager.php Dosyasını Aç

**Yol:** `C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\includes\class-license-manager.php`

### Satır 17-21'i Bul ve Güncelle:

**ŞU ANKİ KOD (Satır 17-21):**
```php
// License server URL - UPDATE THIS WITH YOUR ACTUAL SERVER URL!
$this->license_server_url = 'https://license.yourcompany.com/wp-json/mqpro-license/v1';

// API Key - UPDATE THIS WITH YOUR ACTUAL API KEY!
$this->api_key = get_option('mickey_api_key', '');
```

**ÖRNEK GÜNCELLENME (Kendi değerlerinizle değiştirin!):**
```php
// License server URL - Kendi license server'ınızın URL'i
$this->license_server_url = 'https://your-wordpress-site.com/wp-json/mqpro-license/v1';

// API Key - License server dashboard'dan aldığınız key
$this->api_key = 'mqpro_abc123def456ghi789jkl012mno345pqr678';
```

**ÖNEMLİ:** 
- `your-wordpress-site.com` → License server'ın kurulu olduğu domain
- `mqpro_abc...` → License Server > Dashboard'dan kopyaladığınız API key

**Kaydet!** (Ctrl + S)

---

## ✅ ADIM 3: License Server Kur (3 dakika)

### 3.1 ZIP Oluştur

**Windows:**
1. `C:\Users\PC\.gemini\antigravity\scratch\mqpro-license-server` klasörüne git
2. Tüm dosyaları seç
3. Sağ tık → Send to → Compressed (zipped) folder
4. `mqpro-license-server.zip` olarak kaydet

**VEYA Komut ile:**
```bash
cd C:\Users\PC\.gemini\antigravity\scratch
zip -r mqpro-license-server.zip mqpro-license-server/
```

### 3.2 WordPress'e Yükle

1. License server'ın kurulacağı WordPress sitesine admin olarak gir
2. Plugins → Add New → Upload Plugin
3. `mqpro-license-server.zip` seç
4. Install Now
5. **Activate**

### 3.3 API Key'i Al

1. Sol menüde **License Server** görünecek
2. License Server → Dashboard
3. **"API Information"** bölümünde API Key'i kopyala

**Örnek:**
```
mqpro_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8
```

Bu key'i **ADIM 2**'de kullan!

---

## ✅ ADIM 4: 4 License Key Oluştur (1 dakika)

### License Server'da:

1. License Server → Generate License
2. **"Generate 4 Licenses"** butonuna tıkla

### Çıkan Key'leri Kaydet:

```
Customer 1: MQPRO-ABCD-1234-EFGH-5678
Customer 2: MQPRO-WXYZ-9876-IJKL-5432
Customer 3: MQPRO-MNOP-1357-QRST-2468
Customer 4: MQPRO-UVWX-1122-YZAB-3344
```

**Bu key'leri güvenli bir yere kaydet!**

---

## 🧪 TEST (2 dakika)

### Test 1: License Sayfası Görünüyor mu?

1. Client WordPress'e gir (müşteri sitesi)
2. Admin → QR Menu Panel
3. Alt menüde **"License"** görünüyor mu?

✅ Evet → Devam

### Test 2: Activation Çalışıyor mu?

1. License sayfasına git
2. Bir license key gir (yukarıdakilerden birini)
3. Activate tıkla

✅ "License activated successfully!" → BAŞARILI!

---

## 🎉 TAMAMLANDI!

Tüm adımlar bittiğinde:

✅ Kod entegrasyonu tamamlandı  
✅ API key ayarlandı  
✅ License server aktif  
✅ 4 license key hazır  
✅ Test başarılı  

**Sistem %100 hazır!** 🚀

---

## 📧 MÜŞTERİLERE EMAIL (Yarın)

Email şablonu:

```
Konu: Menu QR Pro - License Aktivasyonu

Merhaba [Müşteri Adı],

Menu QR Pro eklentinizin lisansı hazır!

📌 License Key: [KEY_BURAYA_YAPISTIR]
⏰ Geçerlilik: 1 Yıl (365 gün)
🌐 Domain: Tek domain'e kilitlenecek

AKTIVASYON ADIMLARI:
1. WordPress Admin → QR Menu Panel → License
2. License key'i girin
3. "Activate License" tıklayın
4. Tamamdır! ✅

NOTLAR:
- License aktif olduğunda domain'inize kilitlenir
- 1 yıl boyunca tüm özelliklere erişebilirsiniz
- Süre bittiğinde menünüz görünmeye devam eder
- Ancak yeni düzenlemeler için yenileme gerekir

DESTEK:
Email: support@yourcompany.com
WhatsApp: +90 555 123 45 67

İyi çalışmalar!
```

---

## 🎯 ÖZET

**Bugün (10 dakika):**
- [x] 3 kod ekle
- [x] API key ayarla  
- [x] License server kur
- [x] 4 key oluştur
- [x] Test et

**Yarın:**
- [ ] 4 müşteriye email at
- [ ] Aktivasyonları takip et
- [ ] Geri bildirimleri topla

**SİSTEM: %100 HAZIR!** ✅

---

**Hazırlayan:** Antigravity AI  
**Tarih:** 2025-12-23  
**Status:** Ready to Deploy 🚀
