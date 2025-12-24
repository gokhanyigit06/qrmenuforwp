# 🚀 403 BYPASS ÇÖZÜMÜ - REST API

## 🔴 SORUN
admin-ajax.php sürekli 403 veriyor.

## ✅ ÇÖZÜM
WordPress REST API kullanacağız (genelde engellenmez)

---

## 📝 KURULUM

### 1. REST API Dosyasını Yükle

**FTP ile:**

```
Kaynak: C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\includes\class-rest-api.php

Hedef: /wp-content/plugins/menu-qr-pro/includes/class-rest-api.php
```

### 2. menu-qr-pro.php'ye Ekle

**FTP ile menu-qr-pro.php dosyasını aç**

**Satır 26'dan sonra (class-update-manager.php'den sonra) ekle:**

```php
require_once MICKEYS_QR_MENU_PATH . 'includes/class-rest-api.php';
```

Kaydet!

### 3. Sayfayı Yenile

WordPress admin'i yenile (F5)

---

## 🧪 TEST

Console'da:

```javascript
// REST API test
fetch('/wp-json/mqpro/v1/get/products', {
  headers: {
    'X-WP-Nonce': mickeyAdminData.nonce
  }
})
.then(r => r.json())
.then(d => console.log('REST API Result:', d))
```

**Beklenen:**
```javascript
REST API Result: {success: true, data: "..."}
```

---

## 🎯 EĞER REST API DE ENGELLENĐRSE

Son çare: **Hosting desteğine danışın**

**Mesaj:**
```
admin-ajax.php ve REST API (/wp-json/) 
her ikisi de 403 hatası veriyor.

Tüm firewall/WAF kurallarını devre dışı 
bırakabilir misiniz?

Site: mickeys.thervz.co
```

---

## 📂 DOSYALAR HAZIR

**1. REST API:**
```
C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\includes\class-rest-api.php
```

**2. Ekleme yapılacak:**
```
/wp-content/plugins/menu-qr-pro/menu-qr-pro.php
Satır 26'dan sonra require_once ekle
```

---

**Önce REST API dosyasını yükleyin ve menu-qr-pro.php'ye require ekleyin!**

Sonra test edin ve sonucu bildirin! 🔧
