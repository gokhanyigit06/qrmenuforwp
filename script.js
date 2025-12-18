// Category configuration - with defaults
var categoryConfig = {
    starters: { name: 'Başlangıçlar', icon: '🍲', bgImage: 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800&q=80' },
    snacks: { name: 'Atıştırmalıklar', icon: '🍗', bgImage: 'https://images.unsplash.com/photo-1562967914-608f82629710?w=800&q=80' },
    salads: { name: 'Salatalar', icon: '🥗', bgImage: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80' },
    asian: { name: 'Asya Mutfağı', icon: '🍜', bgImage: 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=800&q=80' },
    pizza: { name: 'Pizza', icon: '🍕', bgImage: 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=800&q=80' },
    burgers: { name: 'Burgerler', icon: '🍔', bgImage: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&q=80' },
    pasta: { name: 'Makarna', icon: '🍝', bgImage: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=800&q=80' },
    mexican: { name: 'Meksika Mutfağı', icon: '🌮', bgImage: 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&q=80' },
    mains: { name: 'Ana Yemekler', icon: '🍖', bgImage: 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&q=80' },
    desserts: { name: 'Tatlılar', icon: '🍰', bgImage: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800&q=80' },
    drinks: { name: 'İçecekler', icon: '🍹', bgImage: 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?w=800&q=80' }
};

var currentLanguage = localStorage.getItem('mickey_lang') || 'tr';
var adminAllergens = [];
// menuData may already be defined in menu-data.js - don't redeclare
if (typeof menuData === 'undefined') { var menuData = []; }

// DOM Elements - will be set after DOMContentLoaded
var menuAccordion, itemModal, modalBody, modalClose, modalOverlay;
var allergenBtn, allergenModal, allergenClose, allergenOverlay;

// Group menu items by category
function groupByCategory() {
    var grouped = {};
    menuData.forEach(function (item) {
        if (!grouped[item.category]) {
            grouped[item.category] = [];
        }
        grouped[item.category].push(item);
    });
    return grouped;
}

// Render menu accordion
function renderMenuAccordion() {
    if (!menuAccordion) return;

    var groupedItems = groupByCategory();

    // Get categories that have items
    var categoriesWithItems = Object.keys(categoryConfig).filter(function (catKey) {
        return groupedItems[catKey] && groupedItems[catKey].length > 0;
    }).sort(function (a, b) {
        var orderA = categoryConfig[a].order !== undefined ? categoryConfig[a].order : 999;
        var orderB = categoryConfig[b].order !== undefined ? categoryConfig[b].order : 999;
        return orderA - orderB;
    });

    if (categoriesWithItems.length === 0) {
        menuAccordion.innerHTML = '<p style="text-align:center; padding: 2rem; color: #666;">Henüz menü eklenmemiş.</p>';
        return;
    }

    menuAccordion.innerHTML = categoriesWithItems.map(function (categoryKey) {
        var category = categoryConfig[categoryKey];
        if (!category) return ''; // Skip if config missing
        var items = groupedItems[categoryKey] || [];

        return '<div class="category-section" data-category="' + categoryKey + '">' +
            '<div class="category-header" style="background-image: url(\'' + (category.bgImage || '') + '\');">' +
            '<div class="category-header-content">' +
            '<span class="category-icon">' + (category.icon || '🍽️') + '</span>' +
            '<h2 class="category-name">' + (category.name || categoryKey) + '</h2>' +
            '</div>' +
            '<div class="category-toggle">+</div>' +
            '</div>' +
            '<div class="category-items">' +
            items.map(function (item) {
                var hasVariants = item.variants && item.variants.length > 0;
                var variantDisplay = category.variantDisplay || 'modal';
                var displayName = (currentLanguage === 'en' && item.nameEn) ? item.nameEn : item.name;

                var priceHtml = '<span class="menu-item-price">' + item.price + ' ₺</span>';
                var variantsListHtml = '';

                if (hasVariants && variantDisplay === 'list') {
                    priceHtml = '';
                    variantsListHtml = '<div class="item-variants-list">' +
                        item.variants.map(function (v) {
                            return '<div class="variant-line">' +
                                '<span class="v-name">' + v.name + '</span>' +
                                '<span class="v-price">' + v.price + ' ₺</span>' +
                                '</div>';
                        }).join('') +
                        '</div>';
                }

                return '<div class="menu-item ' + (!item.image ? 'no-image' : '') + '" onclick="openItemModal(' + item.id + ')">' +
                    (item.image ? '<img src="' + item.image + '" alt="' + displayName + '" class="menu-item-image" loading="lazy">' : '') +
                    '<div class="menu-item-content">' +
                    '<div class="menu-item-header">' +
                    '<div class="menu-item-info">' +
                    '<h3 class="menu-item-name">' + displayName + '</h3>' +
                    '</div>' +
                    priceHtml +
                    '</div>' +
                    '<p class="menu-item-description">' + (item.description || '') + '</p>' +
                    variantsListHtml +
                    '<div class="menu-item-footer">' +
                    (item.tags && item.tags.includes('spicy') ? '<span class="menu-item-tag spicy">🌶️ Acılı</span>' : '') +
                    (item.tags && item.tags.includes('vegetarian') ? '<span class="menu-item-tag vegetarian">🌱 Vejetaryen</span>' : '') +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }).join('') +
            '</div>' +
            '</div>';
    }).join('');

    // Add click handlers to category headers
    document.querySelectorAll('.category-header').forEach(function (header) {
        header.addEventListener('click', function () {
            var section = this.parentElement;
            var wasActive = section.classList.contains('active');

            // Close all sections
            document.querySelectorAll('.category-section').forEach(function (s) {
                s.classList.remove('active');
            });

            // Open clicked section if it wasn't active
            if (!wasActive) {
                section.classList.add('active');
                setTimeout(function () {
                    section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        });
    });
}

// Open item modal
window.openItemModal = function (itemId) {
    var item = menuData.find(function (i) { return i.id === itemId; });
    if (!item || !modalBody || !itemModal) return;

    var variantsHtml = '';
    if (item.variants && item.variants.length > 0) {
        variantsHtml = '<div class="modal-variants-selection" style="margin-bottom: 1rem; border-top: 1px solid #eee; padding-top: 1rem;">' +
            '<p style="font-weight:600; margin-bottom:0.5rem;">Seçenekler:</p>' +
            item.variants.map(function (v, i) {
                return '<div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; align-items:center;">' +
                    '<label style="cursor:pointer; display:flex; gap:0.5rem; align-items:center;">' +
                    '<input type="radio" name="variant" value="' + v.price + '" ' + (i === 0 ? 'checked' : '') + ' onchange="document.querySelector(\'.modal-item-price\').textContent = this.value + \' ₺\'">' +
                    v.name +
                    '</label>' +
                    '<span style="font-weight:600;">' + v.price + ' ₺</span>' +
                    '</div>';
            }).join('') +
            '</div>';
    }

    modalBody.innerHTML =
        (item.image ? '<img src="' + item.image + '" alt="' + item.name + '" class="modal-item-image">' : '') +
        '<div class="modal-item-header">' +
        '<h2 class="modal-item-name">' + item.name + '</h2>' +
        (item.nameEn ? '<p style="color: var(--text-muted); font-style: italic; margin-bottom: 0.5rem;">' + item.nameEn + '</p>' : '') +
        '<p class="modal-item-price">' + (item.variants && item.variants.length > 0 ? item.variants[0].price : item.price) + ' ₺</p>' +
        '</div>' +
        variantsHtml +
        '<p class="modal-item-description">' + (item.description || '') + '</p>' +
        '<div class="modal-item-tags">' +
        (item.tags && item.tags.includes('spicy') ? '<span class="menu-item-tag spicy">🌶️ Acılı</span>' : '') +
        (item.tags && item.tags.includes('vegetarian') ? '<span class="menu-item-tag vegetarian">🌱 Vejetaryen</span>' : '') +
        '</div>';

    itemModal.classList.add('active');
    document.body.classList.add('no-scroll');
};

// Close item modal
function closeItemModal() {
    if (itemModal) {
        itemModal.classList.remove('active');
        document.body.classList.remove('no-scroll');
    }
}

// Close allergen modal
function closeAllergenModal() {
    if (allergenModal) {
        allergenModal.classList.remove('active');
        document.body.classList.remove('no-scroll');
    }
}

// Open allergen modal
function openAllergenModal() {
    console.log('Opening allergen modal...');
    if (allergenModal) {
        allergenModal.classList.add('active');
        document.body.classList.add('no-scroll');
    } else {
        console.log('allergenModal not found!');
    }
}

// Banner slider functionality
var currentSlide = 0;

function initBannerSlider() {
    var slides = document.querySelectorAll('.banner-slide');
    var dots = document.querySelectorAll('.dot');

    function showSlide(index) {
        slides.forEach(function (slide) { slide.classList.remove('active'); });
        dots.forEach(function (dot) { dot.classList.remove('active'); });

        if (slides[index]) slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
    }

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Auto-advance banner
    if (slides.length > 1) {
        setInterval(function () {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);
    }
}

// Initialize app
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOMContentLoaded - Initializing menu...');

    // Get DOM elements after page loads
    menuAccordion = document.getElementById('menuAccordion');
    itemModal = document.getElementById('itemModal');
    modalBody = document.getElementById('modalBody');
    modalClose = document.getElementById('modalClose');
    modalOverlay = document.getElementById('modalOverlay');
    allergenBtn = document.getElementById('allergenBtn');
    allergenModal = document.getElementById('allergenModal');
    allergenClose = document.getElementById('allergenClose');
    allergenOverlay = document.getElementById('allergenOverlay');

    console.log('DOM elements:', {
        menuAccordion: !!menuAccordion,
        allergenBtn: !!allergenBtn,
        allergenModal: !!allergenModal
    });

    // Setup modal close handlers
    if (modalClose) modalClose.addEventListener('click', closeItemModal);
    if (modalOverlay) modalOverlay.addEventListener('click', closeItemModal);

    // Setup allergen modal handlers
    if (allergenBtn) {
        allergenBtn.addEventListener('click', openAllergenModal);
        console.log('Allergen button click handler added');
    }

    if (allergenClose) allergenClose.addEventListener('click', closeAllergenModal);
    if (allergenOverlay) allergenOverlay.addEventListener('click', closeAllergenModal);

    // Close modals on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeItemModal();
            closeAllergenModal();
        }
    });

    // Init banner slider
    initBannerSlider();

    // Check if we are in WordPress environment
    if (typeof mickeyData !== 'undefined') {
        console.log('WordPress environment detected, loading data via AJAX...');

        jQuery.when(
            jQuery.get(mickeyData.ajax_url, { action: 'mickey_get_data', key: 'mickeys_menu_data' }),
            jQuery.get(mickeyData.ajax_url, { action: 'mickey_get_data', key: 'mickeys_categories' }),
            jQuery.get(mickeyData.ajax_url, { action: 'mickey_get_data', key: 'mickeys_allergens' })
        ).done(function (resMenu, resCat, resAllergen) {
            var menuRes = resMenu[0];
            var catRes = resCat[0];
            var algRes = resAllergen[0];

            console.log('AJAX responses received');

            if (menuRes.success && menuRes.data) {
                try {
                    menuData = JSON.parse(menuRes.data);
                    console.log('Menu data loaded:', menuData.length, 'items');
                } catch (e) {
                    console.error('Failed to parse menu data:', e);
                }
            }

            if (catRes.success && catRes.data) {
                try {
                    var loadedCategories = JSON.parse(catRes.data);
                    if (loadedCategories && Object.keys(loadedCategories).length > 0) {
                        categoryConfig = loadedCategories;
                        console.log('Categories loaded:', Object.keys(categoryConfig).length, 'categories');
                    }
                } catch (e) {
                    console.error('Failed to parse categories:', e);
                }
            }

            if (algRes.success && algRes.data) {
                try {
                    adminAllergens = JSON.parse(algRes.data);
                    console.log('Allergens loaded:', adminAllergens.length, 'allergens');
                } catch (e) {
                    console.error('Failed to parse allergens:', e);
                }
            }

            // Render everything
            renderMenuAccordion();
            renderAllergens();
            activateFirstCategory();
            toggleLanguage(currentLanguage);

        }).fail(function (error) {
            console.error('AJAX failed:', error);
            renderMenuAccordion();
            renderAllergens();
            activateFirstCategory();
        });
    } else {
        console.log('Non-WordPress environment, using fallback data');
        renderMenuAccordion();
        renderAllergens();
        activateFirstCategory();
    }
});

function activateFirstCategory() {
    var firstCategory = document.querySelector('.category-section');
    if (firstCategory) {
        firstCategory.classList.add('active');
    }
}

// Toggle language
window.toggleLanguage = function (lang) {
    currentLanguage = lang;
    localStorage.setItem('mickey_lang', lang);

    // Update active buttons
    document.querySelectorAll('.lang-btn').forEach(function (btn) {
        var isTr = btn.innerHTML.indexOf('🇹🇷') > -1 || btn.title === 'Türkçe';
        var isEn = btn.innerHTML.indexOf('🇬🇧') > -1 || btn.title === 'English';

        if ((lang === 'tr' && isTr) || (lang === 'en' && isEn)) {
            btn.style.opacity = '1';
            btn.style.transform = 'scale(1.1)';
        } else {
            btn.style.opacity = '0.5';
            btn.style.transform = 'scale(1)';
        }
    });

    // Update all translatable elements
    document.querySelectorAll('[data-lang-tr][data-lang-en]').forEach(function (el) {
        var text = lang === 'en' ? el.getAttribute('data-lang-en') : el.getAttribute('data-lang-tr');
        el.textContent = text;
    });

    // Re-render menu with new language
    renderMenuAccordion();
    activateFirstCategory();
};

// Render allergens
function renderAllergens() {
    var grid = document.getElementById('allergensGrid');
    if (!grid) return;

    // If no allergens from DB, show default
    if (!adminAllergens || adminAllergens.length === 0) {
        adminAllergens = [
            { id: 1, name: 'Fındık ve Yerfıstığı', icon: '🥜', description: 'Bazı ürünlerimizde fındık, ceviz ve yerfıstığı bulunmaktadır.' },
            { id: 2, name: 'Süt Ürünleri', icon: '🥛', description: 'Peynir, krema ve süt içeren ürünlerimiz mevcuttur.' },
            { id: 3, name: 'Gluten', icon: '🌾', description: 'Ekmek, makarna ve hamur işi ürünlerimizde gluten bulunur.' },
            { id: 4, name: 'Deniz Ürünleri', icon: '🦐', description: 'Balık ve deniz ürünleri içeren yemeklerimiz vardır.' },
            { id: 5, name: 'Yumurta', icon: '🥚', description: 'Bazı ürünlerimizde yumurta kullanılmaktadır.' }
        ];
    }

    var sortedAllergens = adminAllergens.slice().sort(function (a, b) {
        return (a.order || 999) - (b.order || 999);
    });

    grid.innerHTML = sortedAllergens.map(function (a) {
        return '<div class="allergen-card" style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 12px; width: 100%;">' +
            '<span style="font-size: 2.5rem; line-height: 1;">' + a.icon + '</span>' +
            '<div style="flex: 1;">' +
            '<h4 style="margin: 0 0 0.5rem 0; font-weight: 600; color: #333;">' + a.name + '</h4>' +
            '<p style="margin: 0; font-size: 0.9rem; color: #666; line-height: 1.4;">' + a.description + '</p>' +
            '</div>' +
            '</div>';
    }).join('');
}

// Smooth scroll
document.documentElement.style.scrollBehavior = 'smooth';
