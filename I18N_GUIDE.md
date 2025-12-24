# 🌍 i18n (Internationalization) Guide - Menu QR Pro

## ✅ i18n Desteği Eklendi!

Menu QR Pro artık tam çok dilli destek içeriyor. WordPress'in yerleşik i18n sistemini kullanarak plugin kolayca farklı dillere çevrilebilir.

---

## 📁 Dosya Yapısı

```
qrmenuforwp/
├── languages/
│   ├── menu-qr-pro.pot          # Translation template (ana şablon)
│   ├── menu-qr-pro-tr_TR.po     # Türkçe çeviri
│   ├── menu-qr-pro-en_US.po     # İngilizce çeviri
│   └── (gelecekte eklenecek diller)
├── menu-qr-pro.php              # Ana plugin dosyası (i18n fonksiyonları içerir)
└── ...
```

---

## 🔧 Teknik Detaylar

### 1. Text Domain
```php
Text Domain: menu-qr-pro
Domain Path: /languages
```

### 2. Load Function
```php
public function load_textdomain() {
    load_plugin_textdomain(
        'menu-qr-pro',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
}
```

### 3. Kullanılan i18n Fonksiyonları

| Fonksiyon | Kullanım | Örnek |
|-----------|----------|-------|
| `__()` | String return eder | `__('Menu Admin', 'menu-qr-pro')` |
| `_e()` | String echo eder | `_e('Save', 'menu-qr-pro')` |
| `esc_html__()` | Escaped return | `esc_html__('Title', 'menu-qr-pro')` |
| `esc_html_e()` | Escaped echo | `esc_html_e('Label', 'menu-qr-pro')` |
| `sprintf()` | Placeholder'lı | `sprintf(__('Invalid JSON: %s', 'menu-qr-pro'), $error)` |

---

## 🌐 Mevcut Diller

### ✅ Türkçe (tr_TR)
- **Dosya:** `menu-qr-pro-tr_TR.po`
- **Durum:** Tam çeviri ✅
- **String Sayısı:** 80+

### ✅ İngilizce (en_US)
- **Dosya:** `menu-qr-pro-en_US.po`
- **Durum:** Tam çeviri ✅
- **String Sayısı:** 80+

---

## 📝 Yeni Dil Ekleme

### Adım 1: .po Dosyası Oluştur
```bash
# Örnek: Almanca eklemek için
cp languages/menu-qr-pro.pot languages/menu-qr-pro-de_DE.po
```

### Adım 2: Poedit Kullan
1. [Poedit](https://poedit.net/) indir ve kur
2. `menu-qr-pro-de_DE.po` dosyasını Poedit ile aç
3. Tüm string'leri Almanca'ya çevir
4. Kaydet

### Adım 3: .mo Dosyası Oluştur
Poedit otomatik olarak `.mo` dosyası oluşturur. Manuel oluşturmak için:

```bash
msgfmt menu-qr-pro-de_DE.po -o menu-qr-pro-de_DE.mo
```

### Adım 4: Test Et
WordPress'i Almanca yap ve plugin'i test et:
```php
// wp-config.php
define('WPLANG', 'de_DE');
```

---

## 🛠️ Geliştirici Rehberi

### String'leri Çevrilebilir Hale Getirme

#### ❌ YANLIŞ (Hardcoded)
```php
echo 'Ürün başarıyla eklendi';
$title = 'Yeni Ürün Ekle';
```

#### ✅ DOĞRU (i18n)
```php
echo __('Product added successfully', 'menu-qr-pro');
$title = __('Add New Product', 'menu-qr-pro');
```

### Placeholder Kullanımı

#### Tek Placeholder
```php
sprintf(
    __('Invalid JSON: %s', 'menu-qr-pro'),
    json_last_error_msg()
)
```

#### Birden Fazla Placeholder
```php
sprintf(
    __('%d products found in %s category', 'menu-qr-pro'),
    $count,
    $category_name
)
```

### Çoğul (Plural) Kullanımı
```php
sprintf(
    _n(
        '%s product',
        '%s products',
        $count,
        'menu-qr-pro'
    ),
    $count
)
```

---

## 📊 .pot Dosyası Güncelleme

Plugin'e yeni string eklendiğinde `.pot` dosyasını güncellemen gerekir.

### Manuel Güncelleme
```bash
# WP-CLI kullanarak
wp i18n make-pot . languages/menu-qr-pro.pot

# Veya Poedit kullanarak
# Catalog > Update from sources
```

### Otomatik Güncelleme (Önerilen)
Build script'ine ekle:
```bash
#!/bin/bash
# build.sh

# Generate .pot file
wp i18n make-pot . languages/menu-qr-pro.pot

# Update .po files
msgmerge --update languages/menu-qr-pro-tr_TR.po languages/menu-qr-pro.pot
msgmerge --update languages/menu-qr-pro-en_US.po languages/menu-qr-pro.pot
```

---

## 🧪 Test Etme

### Farklı Dillerde Test
```php
// wp-config.php içinde dili değiştir

// Türkçe
define('WPLANG', 'tr_TR');

// İngilizce
define('WPLANG', 'en_US');

// Almanca
define('WPLANG', 'de_DE');
```

### Eksik Çeviri Kontrolü
```bash
# WP-CLI ile
wp i18n list-language-packs menu-qr-pro

# Manuel olarak
# Poedit'te View > Show untranslated strings
```

---

## 📋 Checklist: Yeni String Eklerken

- [ ] `__()` veya `_e()` kullan
- [ ] Text domain olarak `'menu-qr-pro'` kullan
- [ ] Placeholder varsa `sprintf()` kullan
- [ ] `.pot` dosyasını güncelle
- [ ] Tüm `.po` dosyalarını güncelle
- [ ] `.mo` dosyalarını oluştur
- [ ] Farklı dillerde test et

---

## 🌍 Desteklenmesi Planlanan Diller

- [x] Türkçe (tr_TR) ✅
- [x] İngilizce (en_US) ✅
- [ ] Almanca (de_DE)
- [ ] Fransızca (fr_FR)
- [ ] İspanyolca (es_ES)
- [ ] İtalyanca (it_IT)
- [ ] Portekizce (pt_BR)
- [ ] Rusça (ru_RU)
- [ ] Arapça (ar)
- [ ] Çince (zh_CN)

---

## 🔗 Kaynaklar

- [WordPress i18n](https://developer.wordpress.org/plugins/internationalization/)
- [Poedit](https://poedit.net/)
- [WP-CLI i18n](https://developer.wordpress.org/cli/commands/i18n/)
- [WordPress Language Codes](https://wpastra.com/docs/complete-list-wordpress-locale-codes/)

---

## ❓ SSS (Frequently Asked Questions)

### Çeviriler neden görünmüyor?
1. `.mo` dosyası oluşturuldu mu?
2. `languages/` klasörü doğru yerde mi?
3. Text domain doğru mu (`menu-qr-pro`)?
4. WordPress dili doğru ayarlanmış mı?

### Yeni string ekledim, nasıl çeviririm?
1. `.pot` dosyasını güncelle
2. `.po` dosyalarını merge et
3. Poedit ile çevir
4. `.mo` dosyalarını oluştur

### RTL (sağdan sola) dil desteği?
WordPress otomatikman RTL desteği sağlar. Arapça, İbranice gibi diller için:
```css
body.rtl {
    direction: rtl;
}
```

---

**Hazırlayan:** Antigravity AI  
**Tarih:** 2025-12-23  
**Versiyon:** 2.0.0
