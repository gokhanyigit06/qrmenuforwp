# 🧪 Menu QR Pro v3.0.0 - Test Checklist

**ZIP Dosyası:** `menu-qr-pro-v3.0.0-test.zip`  
**Lokasyon:** `C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\`

---

## 📋 TEST ADIMLARI

### 1️⃣ Plugin Kurulumu (5 dakika)

#### A. WordPress Admin'e Giriş
- [ ] WordPress test sitesine admin olarak giriş yap
- [ ] URL: `http://yoursite.local/wp-admin`

#### B. Plugin Yükle
- [ ] Plugins → Add New
- [ ] Upload Plugin
- [ ] `menu-qr-pro-v3.0.0-test.zip` seç
- [ ] Install Now
- [ ] Activate

**Beklenen:** ✅ "Plugin activated" mesajı

#### C. İlk Kontrol
- [ ] Sol menüde **"QR Menu Panel"** görünüyor mu?
- [ ] Alt menüde **"License"** görünüyor mu?

---

### 2️⃣ Admin Panel Testi (10 dakika)

#### A. Dashboard
- [ ] QR Menu Panel → Dashboard
- [ ] Sayfa yükleniyor mu?
- [ ] Sidebar menüler görünüyor mu?

#### B. Ürün Yönetimi
- [ ] Ürün Yönetimi'ne tıkla
- [ ] "Yeni Ürün Ekle" butonu çalışıyor mu?
- [ ] Ürün ekle:
  - İsim: "Test Burger"
  - Fiyat: "150 TL"
  - Açıklama: "Test ürün"
  - Kategori seç
- [ ] Kaydet
- [ ] Ürün listede görünüyor mu?

#### C. Kategori Yönetimi
- [ ] Kategoriler'e tıkla
- [ ] Yeni kategori ekle:
  - İsim: "Test Kategori"
  - Sıra: "1"
- [ ] Kaydet
- [ ] Kategori listede görünüyor mu?

#### D. Tema Ayarları
- [ ] Tema Ayarları'na tıkla
- [ ] Renk değiştir
- [ ] Font değiştir
- [ ] Kaydet
- [ ] Ayarlar kaydoluyor mu?

---

### 3️⃣ Frontend Testi (5 dakika)

#### A. Shortcode Ekle
- [ ] Sayfalar → Yeni Sayfa
- [ ] Başlık: "QR Menu Test"
- [ ] İçerik: `[mickeys_qr_menu]`
- [ ] Yayınla

#### B. Sayfa Görünümü
- [ ] Sayfayı aç (frontend)
- [ ] Menü görünüyor mu?
- [ ] Eklediğiniz ürün var mı?
- [ ] Eklediğiniz kategori var mı?
- [ ] Renkler doğru mu?

#### C. Responsive Test
- [ ] Mobil görünüm (F12 → Device toolbar)
- [ ] Tablet görünüm
- [ ] Desktop görünüm
- [ ] Hepsi düzgün görünüyor mu?

---

### 4️⃣ i18n Testi (3 dakika)

#### A. Dil Değiştir
- [ ] Settings → General
- [ ] Site Language → **Türkçe** seç
- [ ] Save

#### B. Kontrol
- [ ] QR Menu Panel'e git
- [ ] Menüler Türkçe görünüyor mu?
  - "Ürün Yönetimi", "Kategoriler" vs.

#### C. İngilizce
- [ ] Site Language → **English** seç
- [ ] QR Menu Panel'e git
- [ ] Menüler İngilizce görünüyor mu?

---

### 5️⃣ License Sayfası Testi (3 dakika)

#### A. License Page
- [ ] QR Menu Panel → License
- [ ] Sayfa açılıyor mu?
- [ ] "Activate License" formu var mı?

#### B. (Opsiyonel) Fake License Dene
- [ ] License key: `MQPRO-TEST-TEST-TEST-TEST`
- [ ] Activate tıkla
- [ ] Hata mesajı alıyor musunuz?

**Beklenen:** ❌ "License key not found" veya benzeri hata

**Not:** Firebase deploy olmadığı için license aktive olmayacak - bu normal!

---

### 6️⃣ Güvenlik Testi (2 dakika)

#### A. AJAX Güvenlik
- [ ] Browser Console aç (F12)
- [ ] Network tab
- [ ] Ürün kaydet
- [ ] Request'te `nonce` parametresi var mı?

#### B. XSS Test
- [ ] Ürün açıklamasına HTML koy: `<script>alert('test')</script>`
- [ ] Kaydet
- [ ] Frontend'de görüntüle
- [ ] Script çalışıyor mu?

**Beklenen:** ❌ Script çalışmamalı (sanitized olmalı)

---

### 7️⃣ Performance Testi (2 dakika)

#### A. Sayfa Hızı
- [ ] Frontend sayfayı aç
- [ ] F12 → Network → Reload
- [ ] Load time: ____ ms
- [ ] Request count: ____

**Beklenen:** < 2 saniye, < 20 request

#### B. Admin Hızı
- [ ] Admin panel'e git
- [ ] Ürün yönetimi
- [ ] Load time: ____ ms

**Beklenen:** < 3 saniye

---

## 🐛 SORUN BULDUYSANIZ

### Log Kontrol
```php
// wp-config.php'ye ekleyin
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// Log: wp-content/debug.log
```

### Browser Console
- F12 → Console
- Herhangi bir JavaScript hatası var mı?

---

## ✅ TEST SONUÇLARI

### Başarılı Olanlar:
- [ ] Plugin kurulumu
- [ ] Admin panel
- [ ] Ürün ekleme
- [ ] Kategori ekleme
- [ ] Tema ayarları
- [ ] Frontend görünüm
- [ ] Responsive
- [ ] i18n (TR/EN)
- [ ] License sayfası
- [ ] Güvenlik (nonce, XSS)
- [ ] Performance

### Sorunlar:
```
1. ____________________
2. ____________________
3. ____________________
```

---

## 🎯 ÖNERİLER

Test sonrası:

### Hepsi ✅ ise:
- ✅ Plugin production-ready!
- ✅ Müşterilere gönderilebilir!

### Sorun varsa:
- 📝 Sorunları listeleyin
- 🔧 Birlikte düzeltelim

---

**Test etmeye başlayabilirsiniz!** 🚀

**ZIP Lokasyonu:**
```
C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\menu-qr-pro-v3.0.0-test.zip
```
