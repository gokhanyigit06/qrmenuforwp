/**
 * Admin Panel i18n Support
 * Translates admin interface strings based on WordPress locale
 */

(function () {
    'use strict';

    // Get current locale from PHP (passed via wp_localize_script)
    const currentLocale = (typeof mickeyAdminData !== 'undefined' && mickeyAdminData.locale)
        ? mickeyAdminData.locale
        : 'tr_TR';

    // Translation strings
    const translations = {
        'tr_TR': {
            // Sidebar navigation
            'product_management': 'Ürün Yönetimi',
            'categories': 'Kategoriler',
            'allergens': 'Alerjenler',
            'banner_management': 'Banner Yönetimi',
            'excel_operations': 'Excel İşlemleri',
            'price_change': 'Fiyat Değişimi',
            'visual_settings': 'Görsel Düzenlemeler',

            // Product section
            'add_new_product': 'Yeni Ürün Ekle',
            'search_product': 'Ürün ara...',
            'all_categories': 'Tüm Kategoriler',
            'product_name': 'Ürün Adı',
            'category': 'Kategori',
            'price': 'Fiyat',
            'tags': 'Etiketler',
            'actions': 'İşlemler',

            // Buttons
            'save': 'Kaydet',
            'cancel': 'İptal',
            'delete': 'Sil',
            'edit': 'Düzenle',
            'apply': 'Uygula',
            'preview': 'Önizle',

            // Messages
            'confirm_delete': 'Silmek istediğinizden emin misiniz?',
            'saved_successfully': 'Başarıyla kaydedildi',
            'delete_success': 'Başarıyla silindi',
        },
        'en_US': {
            // Sidebar navigation
            'product_management': 'Product Management',
            'categories': 'Categories',
            'allergens': 'Allergens',
            'banner_management': 'Banner Management',
            'excel_operations': 'Excel Operations',
            'price_change': 'Price Change',
            'visual_settings': 'Visual Settings',

            // Product section
            'add_new_product': 'Add New Product',
            'search_product': 'Search product...',
            'all_categories': 'All Categories',
            'product_name': 'Product Name',
            'category': 'Category',
            'price': 'Price',
            'tags': 'Tags',
            'actions': 'Actions',

            // Buttons
            'save': 'Save',
            'cancel': 'Cancel',
            'delete': 'Delete',
            'edit': 'Edit',
            'apply': 'Apply',
            'preview': 'Preview',

            // Messages
            'confirm_delete': 'Are you sure you want to delete?',
            'saved_successfully': 'Saved successfully',
            'delete_success': 'Deleted successfully',
        }
    };

    /**
     * Get translated string
     * @param {string} key Translation key
     * @returns {string} Translated string or key if not found
     */
    window.__(key) {
        const locale = currentLocale;

        if (translations[locale] && translations[locale][key]) {
            return translations[locale][key];
        }

        // Fallback to Turkish
        if (translations['tr_TR'] && translations['tr_TR'][key]) {
            return translations['tr_TR'][key];
        }

        // Return key if translation not found
        return key;
    };

    /**
     * Translate page on load
     */
    function translatePage() {
        // This would require data attributes on HTML elements
        // For now, translations are done via JavaScript functions
        debugLog('i18n loaded for locale:', currentLocale);
    }

    // Auto-translate on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', translatePage);
    } else {
        translatePage();
    }

})();
