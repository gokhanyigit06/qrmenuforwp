# 🔐 Menu QR Pro v3.0.0 - License System Setup Guide

## 📋 OVERVIEW

Menu QR Pro artık profesyonel bir license yönetim sistemi ile geliyor!

**Özellikler:**
- ✅ Remote license server (tam kontrol)
- ✅ Domain locking (1 domain per license)
- ✅ Expiry tracking (yıllık paket)
- ✅ Read-only mode (süre bitince menü açık kalır, düzenleme kapalı)
- ✅ Update control (lisanssız kullanıcılar update alamaz)
- ✅ Support ticket sistemi (gelecek)

---

## 🚀 KURULUM ADIMLARİ

### 1️⃣ LICENSE SERVER KURULUMU

#### Adım 1: WordPress Sitenize License Server Plugin'ini Yükleyin

```bash
# License server plugin klasörü:
mqpro-license-server/

# ZIP olarak paketleyin:
cd C:\Users\PC\.gemini\antigravity\scratch
zip -r mqpro-license-server.zip mqpro-license-server/
```

**Yükleme:**
1. WordPress Admin → Plugins → Add New → Upload
2. `mqpro-license-server.zip` yükleyin
3. Activate edin

#### Adım 2: API Key Alın

Plugin aktif olduktan sonra:
1. WordPress Admin → License Server → Dashboard
2. **API Key**'i kopyalayın
3. Güvenli bir yere kaydedin

**Örnek API Key:**
```
mqpro_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
```

#### Adım 3: 4 License Key Oluşturun

1. WordPress Admin → License Server → Generate License
2. **"Generate 4 Licenses"** butonuna tıklayın
3. Oluşturulan 4 license key'i kaydedin:

```
CUST001: MQPRO-XXXX-XXXX-XXXX-XXXX
CUST002: MQPRO-YYYY-YYYY-YYYY-YYYY
CUST003: MQPRO-ZZZZ-ZZZZ-ZZZZ-ZZZZ
CUST004: MQPRO-WWWW-WWWW-WWWW-WWWW
```

---

### 2️⃣ CLIENT PLUGIN AYARLARI

#### Adım 1: menu-qr-pro.php'ye Kalan Kodları Ekleyin

`menu-qr-pro.php` dosyasını açın ve `add_admin_menu()` fonksiyonuna ekleyin:

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
    
    // 👇 BU KISMI EKLEYİN
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

#### Adım 2: Rendering Fonksiyonlarını Güncelleyin

`render_admin_page()` fonksiyonunu bulun ve değiştirin:

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

#### Adım 3: Yeni Fonksiyonları Ekleyin

`render_admin_page()` fonksiyonundan SONRA ekleyin:

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

#### Adım 4: License Server URL ve API Key Ayarlayın

`includes/class-license-manager.php` dosyasını açın:

**Satır 17-18'i değiştirin:**

```php
// LICENSE SERVER URL - BURAYA KENDİ SERVER URL'İNİZİ YAZIN!
$this->license_server_url = 'https://YOUR-LICENSE-SERVER.com/wp-json/mqpro-license/v1';

// API KEY - BURAYA LICENSE SERVER'DAN ALDIĞINIZ API KEY'İ YAZIN!
$this->api_key = 'mqpro_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6'; // Kendi API key'iniz
```

**VEYA** WordPress options'a kaydedin:

```php
// WordPress Admin → Settings → General sayfasından veya
update_option('mickey_api_key', 'mqpro_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6');
```

---

### 3️⃣ MÜŞTERİLERE ACTIVATION

#### 4 Müşteriye Email Şablonu

```
Konu: Menu QR Pro - License Aktivasyon

Merhaba,

Menu QR Pro lisansınız hazır! Aşağıdaki adımları takip ederek aktive edebilirsiniz:

1. WordPress Admin Panel → QR Menu Panel → License
2. Aşağıdaki license key'i girin:

   License Key: MQPRO-XXXX-XXXX-XXXX-XXXX

3. "Activate License" butonuna tıklayın

✅ Aktif olduğunda:
- Domain'inize kilitlenecek
- 1 yıl geçerli olacak
- Tüm özelliklere erişebileceksiniz

⚠️ Not: 
- License sadece 1 domain'de çalışır
- Süre bitiminde menünüz görünmeye devam eder
- Ancak değişiklik yapamazsınız (yenileme gerekir)

Destek: support@yourcompany.com

Teşekkürler!
```

---

## 🧪 TEST

### Test Senaryoları

#### 1. Fresh Activation
```
1. License olmayan sitede admin panele gir
2. License sayfasına git
3. Geçerli bir key gir
4. Activate et
✅ Başarılı aktivasyon mesajı görmeli
```

#### 2. Domain Locking
```
1. Aktif license'ı başka bir domain'de aktive etmeyi dene
❌ "Already activated" hatası almalı
```

#### 3. Expiry (Simülasyon)
```
1. Database'de expires_at tarihini geçmişe al
2. Admin panele gir
✅ Read-only mode aktif olmalı
✅ "License Expired" uyarısı görmeli
❌ Ürün düzenleme kapalı olmalı
✅ Frontend menü görünür olmalı
```

#### 4. Update Blocking
```
1. License olmayan sitede
2. Plugins → Updates kontrol et
❌ Update görünmemeli
```

---

## 📊 CUSTOMER DASHBOARD

Müşteriler lisans sayfasında görecekler:

```
✅ License Active
245 days remaining
████████████░░░░ 67%

License Key:      MQPRO-ABCD-1234-EFGH-5678
Domain:           restaurant.com
Package Type:     Yearly Package
Expiry Date:      23 December 2025
Customer ID:      CUST001
Status:           Active

[Renew License] [Deactivate License]
```

---

## ⚙️ ADMİN İŞLEMLERİ

### License Yönetimi (Server)

**Dashboard:**
- Toplam licenses
- Active licenses
- Expired licenses
- Expiring soon (30 days)

**Actions:**
- Yeni license oluştur
- License'ı suspend et
- Activation loglarını gör
- Customer bilgilerini güncelle

### Licence Deactivate (Customer)

Müşteri kendi deactivate edebilir:
1. License sayfası
2. "Deactivate License"
3. Confirm
✅ License serbest kalır, başka domain'de kullanılabilir

---

## 🔧 DESTEK & TROUBLESHOOTING

### License aktive edilmiyor

**Sebep 1:** API connection hatası
```
Çözüm: License server URL doğru mu kontrol et
Dosya: includes/class-license-manager.php, Line 17
```

**Sebep 2:** API key yanlış
```
Çözüm: API key'i güncelle
Dosya: includes/class-license-manager.php, Line 21
```

**Sebep 3:** License zaten aktif başka domain'de
```
Çözüm: Server'dan deactivate et veya başka license ver
```

### Read-only mode çalışmıyor

```
Çözüm: feature_controller->block_if_expired() çağrılıyor mu?
Dosya: menu-qr-pro.php, render_admin_page()
```

### Update görünmüyor (licensed user için)

```
Çözüm: License server'da latest_version ayarla
Server Admin → License Server → Settings
Latest Version: 3.0.1
Download URL: https://your-server.com/downloads/menu-qr-pro-3.0.1.zip
```

---

## 📝 NOTLAR

### Önemli Dosyalar

**Client Plugin:**
```
menu-qr-pro.php                        # Main plugin (v3.0.0)
includes/class-license-manager.php      # License validation
includes/class-feature-controller.php   # Read-only mode
includes/class-update-manager.php       # Update control
admin-pages/page-license.php            # License UI
```

**License Server:**
```
mqpro-license-server.php               # Main server plugin
includes/class-api-handler.php          # REST API
includes/class-license-generator.php    # Key generator
admin/partials/dashboard.php            # Admin dashboard
```

### Database Tables (Server)

```sql
wp_mqpro_licenses          # License kayıtları
wp_mqpro_customers         # Customer bilgileri
wp_mqpro_activations       # Activation logları
wp_mqpro_support_tickets   # Destek ticketları (gelecek)
wp_mqpro_ticket_replies    # Ticket cevapları (gelecek)
```

---

## 🎯 SONUÇ

License system %95 hazır!

**Kalan:**
- menu-qr-pro.php'ye 3 satır kod ekleme (yukarıda gösterildi)
- License server URL ve API key configuration
- 4 license key oluşturma
- Müşterilere email gönderme

**Toplam Süre:** 15-20 dakika

---

## 📞 İLETİŞİM

Sorularınız için:
- Email: support@yourcompany.com
- WhatsApp: +90 555 123 45 67

**Hazırlayan:** Antigravity AI  
**Tarih:** 2025-12-23  
**Versiyon:** 3.0.0
