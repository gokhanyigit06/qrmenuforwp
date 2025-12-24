# 🔧 ACİL ÇÖZÜM - License Kontrolünü Devre Dışı Bırak

## 🔴 SORUN

Plugin license expired diyor ve hiçbir değişiklik yapmaya izin vermiyor.

## ✅ HIZLI ÇÖZÜM (2 Dakika)

### Adım 1: FTP ile Bağlan

FileZilla veya dosya yöneticisi ile:
```
/wp-content/plugins/menu-qr-pro/includes/
```

### Adım 2: Dosyayı Değiştir

**class-license-manager.php** dosyasını **YEDEKLE** (class-license-manager-backup.php olarak kaydet)

### Adım 3: Yeni Dosyayı Yükle

**Kaynak:**
```
C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\includes\class-license-manager-dev.php
```

**Hedef:**
```
/wp-content/plugins/menu-qr-pro/includes/class-license-manager.php
```

(class-license-manager-dev.php → class-license-manager.php olarak yükle)

### Adım 4: Sayfayı Yenile

WordPress admin'i yenile (F5)

✅ Read-only mode kaybolacak!
✅ Tüm özellikler çalışacak!

---

## 🎯 NE DEĞİŞTİ?

**Eski sürüm:**
- License kontrolü yapar
- Firebase'e bağlanmaya çalışır
- Bulamazsa → Read-only mode

**Yeni sürüm (DEV MODE):**
- License kontrolünü pas geçer
- Her zaman "valid" döndürür
- Tüm özelliklere izin verir

---

## 🔄 PRODUCTION İÇİN

Firebase deploy'ı tamamladığınızda:

1. Yedeklediğiniz dosyayı geri yükleyin
2. VEYA class-license-manager.php içinde:
   - `return true;` satırlarını yorum satırı yapın
   - `/* PRODUCTION MODE */` bölümlerini açın

---

## 📋 ÖZET

**ŞİMDİ:**
1. FTP → /wp-content/plugins/menu-qr-pro/includes/
2. class-license-manager.php yedekle
3. class-license-manager-dev.php yükle (ismi değiştir)
4. Sayfayı yenile

**SONRA:**
- Plugin tam çalışacak
- License uyarısı olmayacak
- Test edebilirsiniz!

**Firebase hazır olunca:**
- Eski dosyayı geri yükle
- Production mode'a geç

---

**Dosya hazır lokasyonda! FTP'ye yükleyin:** 
```
C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp\includes\class-license-manager-dev.php
→
/wp-content/plugins/menu-qr-pro/includes/class-license-manager.php
```
