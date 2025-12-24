# 🔧 ALTERNATĐF: Direkt FTP Yükleme

## ⚠️ ZIP Sorunu Devam Ediyor

WordPress ZIP okuyamıyor. En hızlı çözüm: **FTP ile direkt yükleyin!**

---

## 🚀 ÇÖZÜM 1: FTP ile Yükleme (ÖNERİLEN)

### Adım 1: Klasörü Hazırlayın

Tüm dosyaları kopyalayın:
```
Kaynak: C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\
Hedef: wp-content/plugins/menu-qr-pro/
```

### Adım 2: FTP Programa Bağlanın

**FileZilla, WinSCP veya benzeri:**
- Host: yoursite.com
- Username: FTP kullanıcı adınız
- Password: FTP şifreniz
- Port: 21

### Adım 3: Dosyaları Yükleyin

```
/wp-content/plugins/menu-qr-pro/
  ├── menu-qr-pro.php          ✅
  ├── admin-view.php           ✅
  ├── frontend-view.php        ✅
  ├── style.css                ✅
  ├── script.js                ✅
  ├── includes/                ✅
  ├── admin-pages/             ✅
  ├── languages/               ✅
  ├── assets/                  ✅
  └── ...
```

**YÜKLEMEYIN (gereksiz):**
- ❌ *.md dosyaları
- ❌ build-*.js
- ❌ .git/
- ❌ tests/
- ❌ *.zip

### Adım 4: Aktive Edin

1. WordPress Admin → Plugins
2. "Menu QR Pro" görünecek
3. **Activate** tıklayın

✅ Çalışacak!

---

## 🚀 ÇÖZÜM 2: WordPress Local üzerinden

Eğer local WordPress kullanıyorsanız:

### XAMPP/WAMP:
```
C:\xampp\htdocs\wordpress\wp-content\plugins\menu-qr-pro\
```

### Local by Flywheel:
```
~\Local Sites\yoursite\app\public\wp-content\plugins\menu-qr-pro\
```

**Tüm klasörü kopyalayın!**

---

## 🚀 ÇÖZÜM 3: Manuel ZIP (Son Deneme)

ZIP'i manuel oluştur:

### Windows:

1. Bu klasörü aç:
   ```
   C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\
   ```

2. Gerekli dosyaları seç:
   - menu-qr-pro.php
   - admin-view.php
   - frontend-view.php
   - *.css, *.js
   - includes/ klasörü
   - admin-pages/ klasörü
   - languages/ klasörü
   - assets/ klasörü
   - uninstall.php

3. Sağ tık → Send to → Compressed (zipped) folder

4. Zip adını değiştir: `menu-qr-pro.zip`

5. Zip'i aç ve içine `menu-qr-pro` klasörü oluştur

6. Tüm dosyaları bu klasöre taşı

7. WordPress'e yükle

---

## ✅ HANGİSİNİ ÖNERİRİM?

### En Hızlı: FTP (2 dakika)
- Direkt kopyala
- %100 çalışır
- Sorun yaşama riski yok

### En Kolay: Local Copy (30 saniye)
- Eğer local WordPress varsa

### Manuel ZIP: (Son seçenek)
- Eğer mutlaka ZIP gerekiyorsa

---

## 🎯 BEN FTP İLE YÜKLEYEYİM Mİ?

Eğer FTP bilgilerinizi verirseniz ben yükleyebilirim!

VEYA sizde deneyip sonucu söyleyin! 😊

**Hangi yöntemi tercih edersiniz?**
