// Admin Panel JavaScript
console.log('admin-script.js loaded!');
// LocalStorage keys (used as DB keys)
const STORAGE_KEYS = {
    MENU_DATA: 'mickeys_menu_data',
    ALLERGENS: 'mickeys_allergens',
    BANNERS: 'mickeys_banners',
    CATEGORIES: 'mickeys_categories',
    THEME_SETTINGS: 'mickey_theme_settings'
};

// Initialize data from localStorage or use default
// Initialize data containers - will be populated via AJAX
let adminMenuData = [];
let adminAllergens = [];
let adminBanners = [];
let adminCategories = {};
let adminThemeSettings = {};
let selectedProductIds = new Set();
let currentPage = 1;
const itemsPerPage = 20;

// Default data fallbacks (if DB is empty)
const defaultAllergens = [
    { id: 1, name: 'Fındık ve Yerfıstığı', icon: '🥜', description: 'Bazı ürünlerimizde fındık, ceviz ve yerfıstığı bulunmaktadır.' },
    { id: 2, name: 'Süt Ürünleri', icon: '🥛', description: 'Peynir, krema ve süt içeren ürünlerimiz mevcuttur.' },
    { id: 3, name: 'Gluten', icon: '🌾', description: 'Ekmek, makarna ve hamur işi ürünlerimizde gluten bulunur.' },
    { id: 4, name: 'Deniz Ürünleri', icon: '🦐', description: 'Balık ve deniz ürünleri içeren yemeklerimiz vardır.' },
    { id: 5, name: 'Yumurta', icon: '🥚', description: 'Bazı ürünlerimizde yumurta kullanılmaktadır.' },
    { id: 6, name: 'Acı Baharatlar', icon: '🌶️', description: 'Acılı ürünlerimiz özel olarak işaretlenmiştir.' }
];

const defaultBanners = [
    {
        id: 1,
        title: 'Her gün 14:00-19:00 arası tüm kokteyllerde',
        subtitle: '%20 İNDİRİM',
        discount: 20,
        bgColor: '#C84B31',
        active: true
    }
];

const defaultCategories = {
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

let currentEditingProduct = null;
let currentEditingAllergen = null;
let currentEditingBanner = null;
let currentEditingCategory = null;

// Save data to Database (via AJAX)
// Save data to Database (via AJAX)
function saveData() {
    const promises = [];

    // Helper to save one key
    const saveKey = (key, data) => {
        return jQuery.post(mickeyAdminData.ajax_url, {
            action: 'mickey_save_data',
            nonce: mickeyAdminData.nonce,
            key: key,
            value: JSON.stringify(data)
        });
    };

    promises.push(saveKey(STORAGE_KEYS.MENU_DATA, adminMenuData));
    promises.push(saveKey(STORAGE_KEYS.ALLERGENS, adminAllergens));
    promises.push(saveKey(STORAGE_KEYS.BANNERS, adminBanners));
    promises.push(saveKey(STORAGE_KEYS.CATEGORIES, adminCategories));

    return Promise.all(promises);
}

// Load data from Database
function initData() {
    // Use jQuery deferreds or Promises
    const loadKey = (key) => {
        return jQuery.get(mickeyAdminData.ajax_url, {
            action: 'mickey_get_data',
            key: key
        });
    };

    return Promise.all([
        loadKey(STORAGE_KEYS.MENU_DATA),
        loadKey(STORAGE_KEYS.ALLERGENS),
        loadKey(STORAGE_KEYS.BANNERS),
        loadKey(STORAGE_KEYS.CATEGORIES)
    ]).then(([resMenu, resAllergens, resBanners, resCategories]) => {

        // Menu Data
        if (resMenu.success && resMenu.data) {
            adminMenuData = JSON.parse(resMenu.data);
        } else {
            adminMenuData = typeof menuData !== 'undefined' ? menuData : [];
        }

        // Allergens
        if (resAllergens.success && resAllergens.data) {
            adminAllergens = JSON.parse(resAllergens.data);
        } else {
            adminAllergens = defaultAllergens;
        }

        // Banners
        if (resBanners.success && resBanners.data) {
            adminBanners = JSON.parse(resBanners.data);
        } else {
            adminBanners = defaultBanners;
        }

        // Categories
        if (resCategories.success && resCategories.data) {
            adminCategories = JSON.parse(resCategories.data);
        } else {
            adminCategories = defaultCategories;
        }

        // Categories
        if (resCategories.success && resCategories.data) {
            adminCategories = JSON.parse(resCategories.data);
        } else {
            adminCategories = defaultCategories;
        }

        // Theme Settings
        loadKey(STORAGE_KEYS.THEME_SETTINGS).then(resSettings => {
            if (resSettings.success && resSettings.data) {
                adminThemeSettings = JSON.parse(resSettings.data);
            } else {
                adminThemeSettings = {};
            }
            loadThemeSettings(); // Populate form
        });

        // Initial render
        renderProductsTable();
        updateCategorySelects();
        // Pricing history init (could be loaded similarly)
        initializePricingSection();

        console.log('Data initialized');
    });
}

// Navigation
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function () {
        console.log('Nav clicked, section:', this.dataset.section);

        // Update active nav item
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');

        // Show corresponding section
        const section = this.dataset.section;
        document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));

        const sectionElement = document.getElementById(`${section}-section`);
        console.log('Section element found:', sectionElement);

        if (sectionElement) {
            sectionElement.classList.add('active');
            console.log('Active class added to:', `${section}-section`);
        } else {
            console.error('Section element NOT found:', `${section}-section`);
        }

        // Load section data
        if (section === 'products') renderProductsTable();
        if (section === 'categories') renderCategoriesGrid();
        if (section === 'allergens') renderAllergensList();
        if (section === 'banners') renderBannersGrid();
        if (section === 'pricing') {
            console.log('Pricing section - calling init functions');
            updatePricingCategorySelects();
            updatePercentagePreview();
            updateRoundingPreview();
            renderPricingHistory();
            console.log('Pricing init complete');
        }
    });
});

// ==================== Products Management ====================
function renderProductsTable() {
    const tbody = document.getElementById('productsTableBody');
    const searchQuery = document.getElementById('productSearch').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;

    let filteredProducts = adminMenuData;

    if (searchQuery) {
        filteredProducts = filteredProducts.filter(p =>
            p.name.toLowerCase().includes(searchQuery) ||
            p.nameEn.toLowerCase().includes(searchQuery) ||
            p.description.toLowerCase().includes(searchQuery)
        );
    }

    if (categoryFilter) {
        filteredProducts = filteredProducts.filter(p => p.category === categoryFilter);
    }

    // Pagination Logic
    const totalItems = filteredProducts.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = 1;

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const itemsToShow = filteredProducts.slice(start, end);

    tbody.innerHTML = itemsToShow.map(product => `
        <tr>
            <td><input type="checkbox" class="product-select" value="${product.id}" onchange="toggleProductSelection(${product.id}, this)" ${selectedProductIds.has(product.id) ? 'checked' : ''}></td>
            <td>${product.id}</td>
            <td>
                ${product.image ?
            `<img src="${product.image}" alt="${product.name}" class="product-image">` :
            '<div class="product-image-placeholder">No Image</div>'
        }
            </td>
            <td>
                <div class="product-name">${product.name}</div>
                ${product.nameEn ? `<div class="product-name-en">${product.nameEn}</div>` : ''}
            </td>
            <td>${getCategoryName(product.category)}</td>
            <td>${product.price} ₺</td>
            <td>
                ${product.tags.includes('spicy') ? '<span class="tag tag-spicy">🌶️ Acılı</span>' : ''}
                ${product.tags.includes('vegetarian') ? '<span class="tag tag-vegetarian">🌱 Vejetaryen</span>' : ''}
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-secondary btn-sm btn-icon" onclick="editProduct(${product.id})" title="Düzenle">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-danger btn-sm btn-icon" onclick="deleteProduct(${product.id})" title="Sil">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const container = document.getElementById('productPagination');
    if (!container) return;

    if (totalPages <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = '';
    // Prev
    html += `<button class="btn-secondary btn-sm" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>&laquo;</button>`;

    // Pages logic (show max 5 pages)
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }

    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="btn-secondary btn-sm ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})" style="${i === currentPage ? 'background: var(--primary-color); color: white;' : ''}">${i}</button>`;
    }

    // Next
    html += `<button class="btn-secondary btn-sm" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>&raquo;</button>`;

    container.innerHTML = html;
}

window.changePage = function (page) {
    if (page < 1) return;
    currentPage = page;
    renderProductsTable();
};

function getCategoryName(categoryKey) {
    const names = {
        starters: 'Başlangıçlar',
        snacks: 'Atıştırmalıklar',
        salads: 'Salatalar',
        asian: 'Asya Mutfağı',
        pizza: 'Pizza',
        burgers: 'Burgerler',
        pasta: 'Makarna',
        mexican: 'Meksika Mutfağı',
        mains: 'Ana Yemekler',
        desserts: 'Tatlılar',
        drinks: 'İçecekler'
    };
    return names[categoryKey] || categoryKey;
}

// Search and filter
document.getElementById('productSearch')?.addEventListener('input', renderProductsTable);
document.getElementById('categoryFilter')?.addEventListener('change', renderProductsTable);

// Add Product
document.getElementById('addProductBtn')?.addEventListener('click', () => {
    currentEditingProduct = null;
    document.getElementById('productModalTitle').textContent = 'Yeni Ürün Ekle';
    document.getElementById('productForm').reset();
    document.getElementById('productVariantsContainer').innerHTML = '';
    document.getElementById('productModal').classList.add('active');
});

// Edit Product
function editProduct(id) {
    currentEditingProduct = adminMenuData.find(p => p.id === id);
    if (!currentEditingProduct) return;

    document.getElementById('productModalTitle').textContent = 'Ürün Düzenle';
    document.getElementById('productName').value = currentEditingProduct.name;
    document.getElementById('productNameEn').value = currentEditingProduct.nameEn || '';
    document.getElementById('productCategory').value = currentEditingProduct.category;
    document.getElementById('productPrice').value = currentEditingProduct.price;
    document.getElementById('productDescription').value = currentEditingProduct.description;
    document.getElementById('productImageCode').value = currentEditingProduct.imageCode || ''; // Keep hidden or useless

    // Image handling
    const imgInput = document.getElementById('productImage');
    const noImgCheck = document.getElementById('productNoImage');

    document.getElementById('productNoImage').checked = false;
    document.getElementById('productImage').disabled = false;

    // Reset variants
    const vContainer = document.getElementById('productVariantsContainer');
    vContainer.innerHTML = '';
    if (currentEditingProduct.variants && Array.isArray(currentEditingProduct.variants)) {
        currentEditingProduct.variants.forEach(v => addVariantRow(v.name, v.price));
    }

    if (currentEditingProduct.image) {
        imgInput.value = currentEditingProduct.image;
        if (noImgCheck) {
            noImgCheck.checked = false;
            imgInput.disabled = false;
        }
    } else {
        imgInput.value = '';
        if (noImgCheck) {
            noImgCheck.checked = true;
            imgInput.disabled = true;
        }
    }
    document.getElementById('tagSpicy').checked = currentEditingProduct.tags.includes('spicy');
    document.getElementById('tagVegetarian').checked = currentEditingProduct.tags.includes('vegetarian');

    document.getElementById('productModal').classList.add('active');
}

// Delete Product
function deleteProduct(id) {
    if (confirm('Bu ürünü silmek istediğinizden emin misiniz?')) {
        adminMenuData = adminMenuData.filter(p => p.id !== id);
        saveData();
        renderProductsTable();
        showNotification('Ürün başarıyla silindi', 'success');
    }
}

// Product Form Submit
document.getElementById('productForm')?.addEventListener('submit', (e) => {
    e.preventDefault();

    const tags = [];
    if (document.getElementById('tagSpicy').checked) tags.push('spicy');
    if (document.getElementById('tagVegetarian').checked) tags.push('vegetarian');

    // Gather variants
    const variants = [];
    document.querySelectorAll('.variant-row').forEach(row => {
        const name = row.querySelector('.variant-name').value;
        const price = parseFloat(row.querySelector('.variant-price').value);
        if (name && !isNaN(price)) {
            variants.push({ name, price });
        }
    });

    const newProduct = {
        id: currentEditingProduct ? currentEditingProduct.id : Date.now(),
        name: document.getElementById('productName').value,
        nameEn: document.getElementById('productNameEn').value,
        category: document.getElementById('productCategory').value,
        price: parseFloat(document.getElementById('productPrice').value),
        description: document.getElementById('productDescription').value,
        image: document.getElementById('productNoImage').checked ? '' : document.getElementById('productImage').value,
        imageCode: document.getElementById('productImageCode').value,
        tags: tags,
        variants: variants
    };

    if (currentEditingProduct) {
        // Update existing product
        const index = adminMenuData.findIndex(p => p.id === currentEditingProduct.id);
        adminMenuData[index] = { ...adminMenuData[index], ...newProduct };
        showNotification('Ürün başarıyla güncellendi', 'success');
    } else {
        // Add new product
        adminMenuData.push(newProduct);
        showNotification('Ürün başarıyla eklendi', 'success');
    }

    saveData();
    closeProductModal();
    renderProductsTable();
});

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

// Helper functions for variants
window.addVariantRow = function (name = '', price = '') {
    const container = document.getElementById('productVariantsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'variant-row';
    newRow.innerHTML = `
        <input type="text" class="variant-name" placeholder="Varyant Adı" value="${name}">
        <input type="number" step="0.01" class="variant-price" placeholder="Fiyat" value="${price}">
        <button type="button" class="btn-danger btn-sm" onclick="this.closest('.variant-row').remove()">Kaldır</button>
    `;
    container.appendChild(newRow);
};

// Event listener for "Add Variant" button
document.getElementById('addVariantBtn')?.addEventListener('click', () => addVariantRow());

// ==================== Categories Management ====================
function renderCategoriesGrid() {
    const grid = document.getElementById('categoriesGrid');
    const productCounts = {};

    // Count products per category
    adminMenuData.forEach(item => {
        if (!productCounts[item.category]) {
            productCounts[item.category] = 0;
        }
        productCounts[item.category]++;
    });

    // Sort keys based on 'order' property
    const sortedKeys = Object.keys(adminCategories).sort((a, b) => {
        const orderA = adminCategories[a].order !== undefined ? adminCategories[a].order : 999;
        const orderB = adminCategories[b].order !== undefined ? adminCategories[b].order : 999;
        return orderA - orderB;
    });

    grid.innerHTML = sortedKeys.map(catKey => {
        const cat = adminCategories[catKey];
        const count = productCounts[catKey] || 0;

        return `
            <div class="category-card" data-key="${catKey}">
                <div class="category-card-header">
                    <span class="sort-handle" style="cursor: grab; margin-right: 10px; color: #999; font-size: 1.2rem;">☰</span>
                    <div class="category-card-icon">${cat.icon}</div>
                    <div>
                        <div class="category-card-name">${cat.name}</div>
                        <div class="category-card-count">${count} ürün</div>
                    </div>
                </div>
                <div class="action-buttons" style="margin-top: 1rem;">
                    <button class="btn-secondary btn-sm" onclick="editCategory('${catKey}')">Düzenle</button>
                    <button class="btn-danger btn-sm" onclick="deleteCategory('${catKey}')">Sil</button>
                </div>
            </div>
        `;
    }).join('');

    // Initialize Sortable
    if (jQuery.fn.sortable) {
        jQuery(grid).sortable({
            handle: '.sort-handle',
            placeholder: "ui-state-highlight",
            update: function (event, ui) {
                // Update order in data
                jQuery(this).children().each(function (index) {
                    const key = jQuery(this).data('key');
                    if (adminCategories[key]) {
                        adminCategories[key].order = index;
                    }
                });
                saveData();
            }
        });
    }
}

// Add Category
document.getElementById('addCategoryBtn')?.addEventListener('click', () => {
    currentEditingCategory = null;
    document.getElementById('categoryModalTitle').textContent = 'Yeni Kategori Ekle';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryKey').readOnly = false;
    document.getElementById('categoryModal').classList.add('active');
});

// Edit Category
function editCategory(key) {
    currentEditingCategory = key;
    const category = adminCategories[key];
    if (!category) return;

    document.getElementById('categoryModalTitle').textContent = 'Kategori Düzenle';
    document.getElementById('categoryKey').value = key;
    document.getElementById('categoryKey').readOnly = true; // Don't allow changing key
    document.getElementById('categoryKey').disabled = true;
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryIcon').value = category.icon;
    document.getElementById('categoryBgImage').value = category.image || '';
    document.getElementById('categoryVariantDisplay').value = category.variantDisplay || 'modal';

    document.getElementById('categoryModal').classList.add('active');
}

// Delete Category
function deleteCategory(key) {
    const productCount = adminMenuData.filter(p => p.category === key).length;

    if (productCount > 0) {
        if (!confirm(`Bu kategoride ${productCount} ürün var. Kategoriyi silmek bu ürünleri de silecektir. Devam etmek istiyor musunuz?`)) {
            return;
        }
        // Remove products in this category
        adminMenuData = adminMenuData.filter(p => p.category !== key);
    } else {
        if (!confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')) {
            return;
        }
    }

    delete adminCategories[key];
    saveData();
    renderCategoriesGrid();
    updateCategorySelects();
    showNotification('Kategori başarıyla silindi', 'success');
}

// Category Form Submit
document.getElementById('categoryForm')?.addEventListener('submit', (e) => {
    e.preventDefault();

    const key = document.getElementById('categoryKey').value.toLowerCase().replace(/\s+/g, '-');
    const categoryData = {
        name: document.getElementById('categoryName').value,
        icon: document.getElementById('categoryIcon').value,
        image: document.getElementById('categoryBgImage').value,
        variantDisplay: document.getElementById('categoryVariantDisplay').value
    };

    if (currentEditingCategory) {
        // Update existing category
        adminCategories[currentEditingCategory] = categoryData;
        showNotification('Kategori başarıyla güncellendi', 'success');
    } else {
        // Check if key already exists
        if (adminCategories[key]) {
            showNotification('Bu kategori anahtarı zaten kullanılıyor', 'error');
            return;
        }
        // Add new category
        adminCategories[key] = categoryData;
        showNotification('Kategori başarıyla eklendi', 'success');
    }

    saveData();
    closeCategoryModal();
    renderCategoriesGrid();
    updateCategorySelects();
});

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('active');
}

// Update category selects in product form and filter
function updateCategorySelects() {
    const productCategorySelect = document.getElementById('productCategory');
    const categoryFilterSelect = document.getElementById('categoryFilter');

    if (productCategorySelect) {
        const currentValue = productCategorySelect.value;
        productCategorySelect.innerHTML = '<option value="">Kategori Seçin</option>' +
            Object.keys(adminCategories).map(key =>
                `<option value="${key}">${adminCategories[key].name}</option>`
            ).join('');
        productCategorySelect.value = currentValue;
    }

    if (categoryFilterSelect) {
        const currentValue = categoryFilterSelect.value;
        categoryFilterSelect.innerHTML = '<option value="">Tüm Kategoriler</option>' +
            Object.keys(adminCategories).map(key =>
                `<option value="${key}">${adminCategories[key].name}</option>`
            ).join('');
        categoryFilterSelect.value = currentValue;
    }
}

// Update getCategoryName to use adminCategories
function getCategoryName(categoryKey) {
    return adminCategories[categoryKey]?.name || categoryKey;
}
function renderAllergensList() {
    const list = document.getElementById('allergensList');

    const sortedAllergens = [...adminAllergens].sort((a, b) => (a.order || 999) - (b.order || 999));

    list.innerHTML = sortedAllergens.map(allergen => `
        <div class="allergen-card" data-id="${allergen.id}">
            <div class="allergen-icon">${allergen.icon}</div>
            <div class="allergen-content">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="sort-handle" style="cursor: grab; color: #999; font-size: 1.2rem;">☰</span>
                    <div class="allergen-name">${allergen.name}</div>
                </div>
                <div class="allergen-description">${allergen.description}</div>
                <div class="allergen-actions">
                    <button class="btn-secondary btn-sm" onclick="editAllergen(${allergen.id})">Düzenle</button>
                    <button class="btn-danger btn-sm" onclick="deleteAllergen(${allergen.id})">Sil</button>
                </div>
            </div>
        </div>
    `).join('');

    if (jQuery.fn.sortable) {
        jQuery(list).sortable({
            handle: '.sort-handle',
            update: function (event, ui) {
                jQuery(this).children().each(function (index) {
                    const id = jQuery(this).data('id');
                    const item = adminAllergens.find(a => a.id == id);
                    if (item) item.order = index;
                });
                saveData();
            }
        });
    }
}

// Add Allergen
document.getElementById('addAllergenBtn')?.addEventListener('click', () => {
    currentEditingAllergen = null;
    document.getElementById('allergenModalTitle').textContent = 'Yeni Alerjen Ekle';
    document.getElementById('allergenForm').reset();
    document.getElementById('allergenModal').classList.add('active');
});

// Edit Allergen
function editAllergen(id) {
    currentEditingAllergen = adminAllergens.find(a => a.id === id);
    if (!currentEditingAllergen) return;

    document.getElementById('allergenModalTitle').textContent = 'Alerjen Düzenle';
    document.getElementById('allergenName').value = currentEditingAllergen.name;
    document.getElementById('allergenIcon').value = currentEditingAllergen.icon;
    document.getElementById('allergenDescription').value = currentEditingAllergen.description;

    document.getElementById('allergenModal').classList.add('active');
}

// Delete Allergen
function deleteAllergen(id) {
    if (confirm('Bu alerjeni silmek istediğinizden emin misiniz?')) {
        adminAllergens = adminAllergens.filter(a => a.id !== id);
        saveData();
        renderAllergensList();
        showNotification('Alerjen başarıyla silindi', 'success');
    }
}

// Allergen Form Submit
document.getElementById('allergenForm')?.addEventListener('submit', (e) => {
    e.preventDefault();

    const allergenData = {
        name: document.getElementById('allergenName').value,
        icon: document.getElementById('allergenIcon').value,
        description: document.getElementById('allergenDescription').value
    };

    if (currentEditingAllergen) {
        const index = adminAllergens.findIndex(a => a.id === currentEditingAllergen.id);
        adminAllergens[index] = { ...adminAllergens[index], ...allergenData };
        showNotification('Alerjen başarıyla güncellendi', 'success');
    } else {
        const newId = Math.max(...adminAllergens.map(a => a.id), 0) + 1;
        adminAllergens.push({ id: newId, ...allergenData });
        showNotification('Alerjen başarıyla eklendi', 'success');
    }

    saveData();
    closeAllergenModal();
    renderAllergensList();
});

function closeAllergenModal() {
    document.getElementById('allergenModal').classList.remove('active');
}

// ==================== Banners Management ====================
function renderBannersGrid() {
    const grid = document.getElementById('bannersGrid');

    grid.innerHTML = adminBanners.map(banner => `
        <div class="banner-card">
            <div class="banner-preview" style="background: ${banner.bgColor}">
                <div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">${banner.title}</h3>
                    ${banner.subtitle ? `<p style="font-size: 2rem; font-weight: 700;">${banner.subtitle}</p>` : ''}
                </div>
            </div>
            <div class="banner-card-content">
                <div class="banner-card-title">${banner.title}</div>
                <span class="banner-card-status ${banner.active ? 'active' : 'inactive'}">
                    ${banner.active ? '✓ Aktif' : '✗ Pasif'}
                </span>
                <div class="action-buttons" style="margin-top: 1rem;">
                    <button class="btn-secondary btn-sm" onclick="editBanner(${banner.id})">Düzenle</button>
                    <button class="btn-danger btn-sm" onclick="deleteBanner(${banner.id})">Sil</button>
                </div>
            </div>
        </div>
    `).join('');
}

// Add Banner
document.getElementById('addBannerBtn')?.addEventListener('click', () => {
    currentEditingBanner = null;
    document.getElementById('bannerModalTitle').textContent = 'Yeni Banner Ekle';
    document.getElementById('bannerForm').reset();
    document.getElementById('bannerModal').classList.add('active');
});

// Edit Banner
function editBanner(id) {
    currentEditingBanner = adminBanners.find(b => b.id === id);
    if (!currentEditingBanner) return;

    document.getElementById('bannerModalTitle').textContent = 'Banner Düzenle';
    document.getElementById('bannerTitle').value = currentEditingBanner.title;
    document.getElementById('bannerSubtitle').value = currentEditingBanner.subtitle || '';
    document.getElementById('bannerDiscount').value = currentEditingBanner.discount || '';
    document.getElementById('bannerBgColor').value = currentEditingBanner.bgColor;
    document.getElementById('bannerImage').value = currentEditingBanner.image || '';
    document.getElementById('bannerWidth').value = currentEditingBanner.width || '';
    document.getElementById('bannerHeight').value = currentEditingBanner.height || '';
    document.getElementById('bannerActive').checked = currentEditingBanner.active;

    document.getElementById('bannerModal').classList.add('active');
}

// Delete Banner
function deleteBanner(id) {
    if (confirm('Bu banner\'ı silmek istediğinizden emin misiniz?')) {
        adminBanners = adminBanners.filter(b => b.id !== id);
        saveData();
        renderBannersGrid();
        showNotification('Banner başarıyla silindi', 'success');
    }
}

// Banner Form Submit
document.getElementById('bannerForm')?.addEventListener('submit', (e) => {
    e.preventDefault();

    const bannerData = {
        title: document.getElementById('bannerTitle').value,
        subtitle: document.getElementById('bannerSubtitle').value,
        discount: parseInt(document.getElementById('bannerDiscount').value) || 0,
        bgColor: document.getElementById('bannerBgColor').value,
        image: document.getElementById('bannerImage').value,
        width: parseInt(document.getElementById('bannerWidth').value) || 0,
        height: parseInt(document.getElementById('bannerHeight').value) || 0,
        active: document.getElementById('bannerActive').checked
    };

    if (currentEditingBanner) {
        const index = adminBanners.findIndex(b => b.id === currentEditingBanner.id);
        adminBanners[index] = { ...adminBanners[index], ...bannerData };
        showNotification('Banner başarıyla güncellendi', 'success');
    } else {
        const newId = Math.max(...adminBanners.map(b => b.id), 0) + 1;
        adminBanners.push({ id: newId, ...bannerData });
        showNotification('Banner başarıyla eklendi', 'success');
    }

    saveData();
    closeBannerModal();
    renderBannersGrid();
});

function closeBannerModal() {
    document.getElementById('bannerModal').classList.remove('active');
}

// ==================== Excel Operations ====================
// Export to Excel
document.getElementById('exportExcel')?.addEventListener('click', () => {
    try {
        const wb = XLSX.utils.book_new();

        // Group products by category
        const productsByCategory = {};
        adminMenuData.forEach(item => {
            if (!productsByCategory[item.category]) {
                productsByCategory[item.category] = [];
            }
            productsByCategory[item.category].push(item);
        });

        // Create a sheet for each category
        Object.keys(productsByCategory).forEach(categoryKey => {
            const categoryName = getCategoryName(categoryKey);
            const products = productsByCategory[categoryKey];

            const ws_data = [
                ['ID', 'Ürün Adı', 'İngilizce Adı', 'Fiyat (₺)', 'Açıklama', 'Görsel Kodu', 'Acılı', 'Vejetaryen']
            ];

            products.forEach(item => {
                ws_data.push([
                    item.id,
                    item.name,
                    item.nameEn || '',
                    item.price,
                    item.description,
                    item.image || '',
                    item.tags.includes('spicy') ? 'Evet' : 'Hayır',
                    item.tags.includes('vegetarian') ? 'Evet' : 'Hayır'
                ]);
            });

            const ws = XLSX.utils.aoa_to_sheet(ws_data);

            // Set column widths
            ws['!cols'] = [
                { wch: 5 },   // ID
                { wch: 25 },  // Ürün Adı
                { wch: 25 },  // İngilizce Adı
                { wch: 10 },  // Fiyat
                { wch: 50 },  // Açıklama
                { wch: 20 },  // Görsel Kodu
                { wch: 8 },   // Acılı
                { wch: 12 }   // Vejetaryen
            ];

            // Sanitize sheet name (max 31 chars, no special chars)
            let sheetName = categoryName.substring(0, 31).replace(/[:\\\/\?\*\[\]]/g, '');
            XLSX.utils.book_append_sheet(wb, ws, sheetName);
        });

        // Also create a summary sheet with all products
        const allProductsData = [
            ['ID', 'Ürün Adı', 'İngilizce Adı', 'Kategori', 'Fiyat (₺)', 'Açıklama', 'Görsel Kodu', 'Acılı', 'Vejetaryen']
        ];

        adminMenuData.forEach(item => {
            allProductsData.push([
                item.id,
                item.name,
                item.nameEn || '',
                getCategoryName(item.category),
                item.price,
                item.description,
                item.image || '',
                item.tags.includes('spicy') ? 'Evet' : 'Hayır',
                item.tags.includes('vegetarian') ? 'Evet' : 'Hayır'
            ]);
        });

        const allWs = XLSX.utils.aoa_to_sheet(allProductsData);
        allWs['!cols'] = [
            { wch: 5 },   // ID
            { wch: 25 },  // Ürün Adı
            { wch: 25 },  // İngilizce Adı
            { wch: 20 },  // Kategori
            { wch: 10 },  // Fiyat
            { wch: 50 },  // Açıklama
            { wch: 20 },  // Görsel Kodu
            { wch: 8 },   // Acılı
            { wch: 12 }   // Vejetaryen
        ];

        // Add summary sheet as first sheet
        XLSX.utils.book_append_sheet(wb, allWs, 'Tüm Ürünler');

        // Generate filename with date
        const date = new Date();
        const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        const filename = `mickeys-menu-${dateStr}.xlsx`;

        // Write file
        XLSX.writeFile(wb, filename);

        showNotification(`Excel dosyası başarıyla indirildi: ${filename}`, 'success');
    } catch (error) {
        console.error('Excel export error:', error);
        showNotification('Excel dosyası oluşturulurken hata oluştu', 'error');
    }
});

// Download Template
document.getElementById('downloadTemplate')?.addEventListener('click', () => {
    try {
        const wb = XLSX.utils.book_new();

        // Create template with examples for each category
        const ws_data = [
            ['ID', 'Ürün Adı', 'İngilizce Adı', 'Kategori', 'Fiyat (₺)', 'Açıklama', 'Görsel Kodu', 'Acılı', 'Vejetaryen'],
            ['', 'Örnek Çorba', 'Sample Soup', 'starters', '150', 'Günün özel çorbası', 'soup', 'Hayır', 'Hayır'],
            ['', 'Örnek Salata', 'Sample Salad', 'salads', '180', 'Taze mevsim sebzeleri ile', 'salad', 'Hayır', 'Evet'],
            ['', 'Örnek Pizza', 'Sample Pizza', 'pizza', '220', 'İtalyan usulü pizza', 'pizza', 'Hayır', 'Hayır'],
            ['', 'Örnek Burger', 'Sample Burger', 'burgers', '200', 'Özel soslu burger', 'burger', 'Hayır', 'Hayır'],
            ['', 'Örnek Tatlı', 'Sample Dessert', 'desserts', '120', 'Ev yapımı tatlı', 'dessert', 'Hayır', 'Evet']
        ];

        const ws = XLSX.utils.aoa_to_sheet(ws_data);

        // Set column widths
        ws['!cols'] = [
            { wch: 5 },   // ID
            { wch: 25 },  // Ürün Adı
            { wch: 25 },  // İngilizce Adı
            { wch: 20 },  // Kategori
            { wch: 10 },  // Fiyat
            { wch: 50 },  // Açıklama
            { wch: 20 },  // Görsel Kodu
            { wch: 8 },   // Acılı
            { wch: 12 }   // Vejetaryen
        ];

        XLSX.utils.book_append_sheet(wb, ws, 'Şablon');

        // Add a sheet with category list
        const categoryData = [
            ['Kategori Anahtarı', 'Kategori Adı'],
            ...Object.keys(adminCategories).map(key => [key, adminCategories[key].name])
        ];

        const catWs = XLSX.utils.aoa_to_sheet(categoryData);
        catWs['!cols'] = [{ wch: 20 }, { wch: 25 }];
        XLSX.utils.book_append_sheet(wb, catWs, 'Kategoriler');

        XLSX.writeFile(wb, 'mickeys-menu-template.xlsx');

        showNotification('Şablon dosyası başarıyla indirildi', 'success');
    } catch (error) {
        console.error('Template download error:', error);
        showNotification('Şablon dosyası oluşturulurken hata oluştu', 'error');
    }
});

// Import from Excel
document.getElementById('excelImport')?.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

            // Skip header row
            const rows = jsonData.slice(1);
            let importCount = 0;
            let updateCount = 0;
            let newCount = 0;

            rows.forEach(row => {
                if (!row[1]) return; // Skip empty rows

                const tags = [];
                if (row[7] === 'Evet') tags.push('spicy');
                if (row[8] === 'Evet') tags.push('vegetarian');

                // Handle both old format (with category column) and new format
                const categoryValue = row[3];
                let categoryKey = categoryValue;

                // If category is in Turkish, find the key
                if (categoryValue && !adminCategories[categoryValue]) {
                    const foundKey = Object.keys(adminCategories).find(
                        key => adminCategories[key].name === categoryValue
                    );
                    if (foundKey) {
                        categoryKey = foundKey;
                    }
                }

                const product = {
                    id: row[0] || Math.max(...adminMenuData.map(p => p.id), 0) + 1,
                    name: row[1],
                    nameEn: row[2] || '',
                    category: categoryKey,
                    price: parseFloat(row[4]),
                    description: row[5] || '',
                    image: row[6] || '',
                    tags: tags
                };

                const existingIndex = adminMenuData.findIndex(p => p.id === product.id);
                if (existingIndex >= 0) {
                    adminMenuData[existingIndex] = product;
                    updateCount++;
                } else {
                    adminMenuData.push(product);
                    newCount++;
                }
                importCount++;
            });

            saveData();
            renderProductsTable();

            let message = `${importCount} ürün işlendi: ${newCount} yeni, ${updateCount} güncellendi`;
            showNotification(message, 'success');
        } catch (error) {
            console.error('Excel import error:', error);
            showNotification('Excel dosyası okunurken hata oluştu: ' + error.message, 'error');
        }
    };
    reader.readAsArrayBuffer(file);

    // Reset file input
    e.target.value = '';
});

// ==================== Notifications ====================
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function () {
        this.parentElement.classList.remove('active');
    });
});

// ==================== Theme Settings ====================
function toggleCustomWidth() {
    const layout = document.getElementById('settingLayoutWidth').value;
    const group = document.getElementById('customWidthGroup');
    if (group) {
        group.style.display = layout === 'custom' ? 'block' : 'none';
    }
}
// Theme Presets
var themePresets = {
    'modern-bistro': { primary: '#1a1a1a', secondary: '#d4af37', bg: '#0d0d0d', card: '#1f1f1f', text: '#ffffff', fontPrimary: 'Inter', fontDisplay: 'Inter', borderRadius: '12' },
    'fast-fresh': { primary: '#ff6b35', secondary: '#2ec4b6', bg: '#ffffff', card: '#f8f9fa', text: '#1a1a2e', fontPrimary: 'Poppins', fontDisplay: 'Poppins', borderRadius: '16' },
    'fine-dining': { primary: '#722f37', secondary: '#f5f0e1', bg: '#1a1a1a', card: '#2d2d2d', text: '#f5f0e1', fontPrimary: 'Lora', fontDisplay: 'Playfair Display', borderRadius: '4' },
    'ocean-blue': { primary: '#0077b6', secondary: '#90e0ef', bg: '#caf0f8', card: '#ffffff', text: '#03045e', fontPrimary: 'Montserrat', fontDisplay: 'Montserrat', borderRadius: '12' },
    'italiano': { primary: '#c41e3a', secondary: '#008c45', bg: '#fffdf7', card: '#ffffff', text: '#2d2d2d', fontPrimary: 'Lora', fontDisplay: 'Lora', borderRadius: '8' },
    'neon-nights': { primary: '#ff00ff', secondary: '#00ffff', bg: '#0a0a0a', card: '#1a1a2e', text: '#ffffff', fontPrimary: 'Outfit', fontDisplay: 'Outfit', borderRadius: '0' },
    'rustic-wood': { primary: '#8b4513', secondary: '#deb887', bg: '#f5f5dc', card: '#fff8dc', text: '#3e2723', fontPrimary: 'Merriweather', fontDisplay: 'Merriweather', borderRadius: '6' },
    'minimalist': { primary: '#000000', secondary: '#ffffff', bg: '#ffffff', card: '#f5f5f5', text: '#1a1a1a', fontPrimary: 'Inter', fontDisplay: 'Inter', borderRadius: '8' },
    'tokyo-street': { primary: '#dc143c', secondary: '#1a1a1a', bg: '#0d0d0d', card: '#1a1a1a', text: '#ffffff', fontPrimary: 'Outfit', fontDisplay: 'Outfit', borderRadius: '4' },
    'mexican-fiesta': { primary: '#e63946', secondary: '#2ec4b6', bg: '#fff7e6', card: '#ffffff', text: '#1d3557', fontPrimary: 'Poppins', fontDisplay: 'Poppins', borderRadius: '16' },
    'garden-fresh': { primary: '#2d5a27', secondary: '#8bc34a', bg: '#f1f8e9', card: '#ffffff', text: '#1b5e20', fontPrimary: 'Outfit', fontDisplay: 'Outfit', borderRadius: '20' },
    'royal-purple': { primary: '#4a0080', secondary: '#d4af37', bg: '#1a0033', card: '#2d0052', text: '#f8f8f8', fontPrimary: 'Lora', fontDisplay: 'Playfair Display', borderRadius: '8' },
    'sunrise-cafe': { primary: '#ff9a8b', secondary: '#ffecd2', bg: '#fff5f5', card: '#ffffff', text: '#5d4e37', fontPrimary: 'Poppins', fontDisplay: 'Poppins', borderRadius: '24' },
    'smoky-bbq': { primary: '#8b0000', secondary: '#ff4500', bg: '#1a1a1a', card: '#2d2d2d', text: '#f5f5f5', fontPrimary: 'Outfit', fontDisplay: 'Oswald', borderRadius: '4' },
    'ice-cream-dream': { primary: '#ff9ff3', secondary: '#54a0ff', bg: '#ffeaa7', card: '#ffffff', text: '#2d3436', fontPrimary: 'Poppins', fontDisplay: 'Poppins', borderRadius: '30' }
};

function applyThemePreset(presetId) {
    if (!presetId || !themePresets[presetId]) return;
    var preset = themePresets[presetId];
    document.getElementById('settingPrimaryColor').value = preset.primary;
    document.getElementById('settingPrimaryColorText').value = preset.primary;
    document.getElementById('settingBgColor').value = preset.bg;
    document.getElementById('settingBgColorText').value = preset.bg;
    document.getElementById('settingTextColor').value = preset.text;
    document.getElementById('settingTextColorText').value = preset.text;
    document.getElementById('settingFontPrimary').value = preset.fontPrimary;
    document.getElementById('settingFontDisplay').value = preset.fontDisplay;
    document.getElementById('settingBorderRadius').value = preset.borderRadius;
    showNotification('Tema uygulandı: ' + presetId, 'success');
}

function saveThemeSettings() {
    const settings = {
        themePreset: document.getElementById('settingThemePreset')?.value || '',
        viewMode: document.getElementById('settingViewMode')?.value || 'grid',
        darkMode: document.getElementById('settingDarkMode')?.value || 'light',
        logoType: document.getElementById('settingLogoType').value,
        logoText: document.getElementById('settingLogoText').value,
        logoImage: document.getElementById('settingLogoImage').value,
        borderRadius: document.getElementById('settingBorderRadius').value,
        borderWidth: document.getElementById('settingBorderWidth').value,
        fontPrimary: document.getElementById('settingFontPrimary').value,
        fontDisplay: document.getElementById('settingFontDisplay').value,

        layoutWidth: document.getElementById('settingLayoutWidth').value,
        customWidth: document.getElementById('settingCustomWidth')?.value || '90',
        headerFontSize: document.getElementById('settingHeaderFontSize').value,
        customWidth: document.getElementById('settingCustomWidth')?.value || '90',
        headerFontSize: document.getElementById('settingHeaderFontSize').value,
        headerSpacing: document.getElementById('settingHeaderSpacing').value,
        categorySpacing: document.getElementById('settingCategorySpacing').value,
        categoryPadding: document.getElementById('settingCategoryPadding').value,
        categoryHeaderHeight: document.getElementById('settingCategoryHeaderHeight')?.value || '70',
        categoryBgSize: document.getElementById('settingCategoryBgSize')?.value || '100% 100%',
        forceFullWidth: document.getElementById('settingForceFullWidth').checked,

        primaryColor: document.getElementById('settingPrimaryColor').value,
        secondaryColor: '#D2691E',
        accentColor: document.getElementById('settingAccentColor').value,
        bgColor: document.getElementById('settingBgColor').value,
        textColor: document.getElementById('settingTextColor').value,

        footerHeaderColor: document.getElementById('settingFooterHeaderColor').value,
        footerTextColor: document.getElementById('settingFooterTextColor').value,
        footerContact: document.getElementById('settingFooterContact').value,
        footerHours: document.getElementById('settingFooterHours').value,
        footerCopyright: document.getElementById('settingFooterCopyright').value,

        // Social & Get in Touch
        getInTouchText: document.getElementById('settingGetInTouchText')?.value || 'İLETİŞİM',
        getInTouchLink: document.getElementById('settingGetInTouchLink')?.value || '#contact',
        instagramLink: document.getElementById('settingInstagramLink')?.value || '',
        facebookLink: document.getElementById('settingFacebookLink')?.value || ''
    };

    adminThemeSettings = settings;

    // Save via AJAX
    jQuery.post(mickeyAdminData.ajax_url, {
        action: 'mickey_save_data',
        nonce: mickeyAdminData.nonce,
        key: 'theme_settings', // Note: key prefix is added in PHP 'mickey_' + key
        value: JSON.stringify(settings)
    }).done(function (response) {
        if (response.success) {
            showNotification('Tema ayarları kaydedildi', 'success');
        } else {
            showNotification('Kaydetme başarısız', 'error');
        }
    });
}

function loadThemeSettings() {
    if (!adminThemeSettings) return;

    if (adminThemeSettings.logoType) document.getElementById('settingLogoType').value = adminThemeSettings.logoType;
    if (adminThemeSettings.logoText) document.getElementById('settingLogoText').value = adminThemeSettings.logoText;
    if (adminThemeSettings.logoImage) document.getElementById('settingLogoImage').value = adminThemeSettings.logoImage;
    if (adminThemeSettings.borderRadius) document.getElementById('settingBorderRadius').value = adminThemeSettings.borderRadius;
    if (adminThemeSettings.borderWidth) document.getElementById('settingBorderWidth').value = adminThemeSettings.borderWidth;

    // Trigger toggle
    toggleLogoSettings();

    if (adminThemeSettings.fontPrimary) document.getElementById('settingFontPrimary').value = adminThemeSettings.fontPrimary;
    if (adminThemeSettings.fontDisplay) document.getElementById('settingFontDisplay').value = adminThemeSettings.fontDisplay;

    if (adminThemeSettings.layoutWidth) document.getElementById('settingLayoutWidth').value = adminThemeSettings.layoutWidth;
    if (adminThemeSettings.customWidth) document.getElementById('settingCustomWidth').value = adminThemeSettings.customWidth;
    if (adminThemeSettings.headerFontSize) document.getElementById('settingHeaderFontSize').value = adminThemeSettings.headerFontSize;
    if (adminThemeSettings.headerFontSize) document.getElementById('settingHeaderFontSize').value = adminThemeSettings.headerFontSize;
    if (adminThemeSettings.headerSpacing) document.getElementById('settingHeaderSpacing').value = adminThemeSettings.headerSpacing;
    if (adminThemeSettings.categorySpacing) document.getElementById('settingCategorySpacing').value = adminThemeSettings.categorySpacing;
    if (adminThemeSettings.categoryPadding) document.getElementById('settingCategoryPadding').value = adminThemeSettings.categoryPadding;
    if (adminThemeSettings.categoryHeaderHeight && document.getElementById('settingCategoryHeaderHeight')) document.getElementById('settingCategoryHeaderHeight').value = adminThemeSettings.categoryHeaderHeight;
    if (adminThemeSettings.categoryBgSize && document.getElementById('settingCategoryBgSize')) document.getElementById('settingCategoryBgSize').value = adminThemeSettings.categoryBgSize;
    if (adminThemeSettings.forceFullWidth !== undefined) document.getElementById('settingForceFullWidth').checked = adminThemeSettings.forceFullWidth;
    if (adminThemeSettings.themePreset) document.getElementById('settingThemePreset').value = adminThemeSettings.themePreset;
    if (adminThemeSettings.viewMode) document.getElementById('settingViewMode').value = adminThemeSettings.viewMode;
    if (adminThemeSettings.darkMode) document.getElementById('settingDarkMode').value = adminThemeSettings.darkMode;

    const setCol = (id, val) => {
        if (val) {
            const el = document.getElementById(id);
            if (el) el.value = val;
            const elText = document.getElementById(id + 'Text');
            if (elText) elText.value = val;
        }
    };

    setCol('settingPrimaryColor', adminThemeSettings.primaryColor);
    setCol('settingAccentColor', adminThemeSettings.accentColor);
    setCol('settingBgColor', adminThemeSettings.bgColor);
    setCol('settingTextColor', adminThemeSettings.textColor);

    setCol('settingFooterHeaderColor', adminThemeSettings.footerHeaderColor || '#ffffff');
    setCol('settingFooterTextColor', adminThemeSettings.footerTextColor || '#bbbbbb');

    if (adminThemeSettings.footerContact) document.getElementById('settingFooterContact').value = adminThemeSettings.footerContact;
    if (adminThemeSettings.footerHours) document.getElementById('settingFooterHours').value = adminThemeSettings.footerHours;
    if (adminThemeSettings.footerCopyright) document.getElementById('settingFooterCopyright').value = adminThemeSettings.footerCopyright;

    // Social & Get in Touch
    if (adminThemeSettings.getInTouchText && document.getElementById('settingGetInTouchText'))
        document.getElementById('settingGetInTouchText').value = adminThemeSettings.getInTouchText;
    if (adminThemeSettings.getInTouchLink && document.getElementById('settingGetInTouchLink'))
        document.getElementById('settingGetInTouchLink').value = adminThemeSettings.getInTouchLink;
    if (adminThemeSettings.instagramLink && document.getElementById('settingInstagramLink'))
        document.getElementById('settingInstagramLink').value = adminThemeSettings.instagramLink;
    if (adminThemeSettings.facebookLink && document.getElementById('settingFacebookLink'))
        document.getElementById('settingFacebookLink').value = adminThemeSettings.facebookLink;

    toggleCustomWidth();
}

// ==================== Media Uploader ====================
window.openMediaUploader = function (targetInputId) {
    // If the media frame already exists, reopen it.
    /*
    if ( file_frame ) {
      file_frame.open();
      return;
    }
    */

    // Create the media frame.
    var file_frame = wp.media({
        title: 'Görsel Seç',
        button: {
            text: 'Bu Görseli Kullan'
        },
        multiple: false
    });

    // When an image is selected, run a callback.
    file_frame.on('select', function () {
        // We set multiple to false so only get one image from the uploader
        var attachment = file_frame.state().get('selection').first().toJSON();
        document.getElementById(targetInputId).value = attachment.url;
    });

    // Finally, open the modal
    file_frame.open();
};

window.toggleProductImageInput = function (checkbox) {
    const input = document.getElementById('productImage');
    const btn = input.nextElementSibling; // The "Seç" button
    if (checkbox.checked) {
        input.value = '';
        input.disabled = true;
        if (btn) btn.disabled = true;
    } else {
        input.disabled = false;
        if (btn) btn.disabled = false;
    }
};

window.toggleLogoSettings = function () {
    const type = document.getElementById('settingLogoType').value;
    const textGroup = document.getElementById('logoTextGroup');
    const imageGroup = document.getElementById('logoImageGroup');

    if (type === 'text') {
        textGroup.style.display = 'block';
        imageGroup.style.display = 'none';
    } else {
        textGroup.style.display = 'none';
        imageGroup.style.display = 'block';
    }
};

// ==================== Bulk Actions ====================
window.toggleProductSelection = function (id, checkbox) {
    if (checkbox.checked) {
        selectedProductIds.add(id);
    } else {
        selectedProductIds.delete(id);
    }
    updateSelectedCount();
};

window.toggleSelectAll = function (checkbox) {
    const checkboxes = document.querySelectorAll('.product-select');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        const id = parseInt(cb.value);
        if (checkbox.checked) {
            selectedProductIds.add(id);
        } else {
            selectedProductIds.delete(id);
        }
    });
    updateSelectedCount();
};

function updateSelectedCount() {
    document.getElementById('selectedCount').textContent = `${selectedProductIds.size} ürün seçildi`;
    const btn = document.getElementById('applyBulkActionBtn');
    btn.disabled = selectedProductIds.size === 0;
}

window.handleBulkAction = function () {
    const action = document.getElementById('bulkActionSelect').value;
    if (selectedProductIds.size === 0) {
        showNotification('Lütfen en az bir ürün seçin', 'error');
        return;
    }

    if (action === 'delete') {
        if (confirm(`${selectedProductIds.size} ürünü silmek istediğinize emin misiniz?`)) {
            bulkDelete();
        }
    } else if (action === 'allergens') {
        openBulkAllergenModal();
    } else if (action === 'category') {
        openBulkCategoryModal();
    }
};

function bulkDelete() {
    adminMenuData = adminMenuData.filter(p => !selectedProductIds.has(p.id));
    saveData().then(() => {
        showNotification('Seçilen ürünler silindi', 'success');
        selectedProductIds.clear();
        updateSelectedCount();
        renderProductsTable();
        document.getElementById('selectAllProducts').checked = false;
    });
}

window.openBulkAllergenModal = function () {
    document.getElementById('bulkAllergenModal').style.display = 'block';
};

window.closeBulkAllergenModal = function () {
    document.getElementById('bulkAllergenModal').style.display = 'none';
};

window.saveBulkAllergens = function () {
    const spicy = document.getElementById('bulkTagSpicy').checked;
    const vegetarian = document.getElementById('bulkTagVegetarian').checked;

    if (!spicy && !vegetarian) {
        closeBulkAllergenModal();
        return;
    }

    let count = 0;
    adminMenuData.forEach(p => {
        if (selectedProductIds.has(p.id)) {
            if (spicy && !p.tags.includes('spicy')) p.tags.push('spicy');
            if (vegetarian && !p.tags.includes('vegetarian')) p.tags.push('vegetarian');
            count++;
        }
    });

    saveData().then(() => {
        showNotification(`${count} ürüne etiketler eklendi`, 'success');
        closeBulkAllergenModal();
        renderProductsTable();
        // Clear selection or keep it? User might want to do more. Keep it.
    });
};

// Bulk Category Functions
window.openBulkCategoryModal = function () {
    const select = document.getElementById('bulkCategorySelect');
    if (!select) return;

    select.innerHTML = '<option value="">Kategori Seçin...</option>' +
        Object.keys(adminCategories).map(key => `<option value="${key}">${adminCategories[key].name}</option>`).join('');

    const modal = document.getElementById('bulkCategoryModal');
    if (modal) modal.style.display = 'block';
};

window.closeBulkCategoryModal = function () {
    const modal = document.getElementById('bulkCategoryModal');
    if (modal) modal.style.display = 'none';
};

window.applyBulkCategory = function () {
    const newCat = document.getElementById('bulkCategorySelect').value;
    if (!newCat) {
        showNotification('Lütfen kategori seçin', 'error');
        return;
    }

    let count = 0;
    adminMenuData.forEach(p => {
        if (selectedProductIds.has(p.id)) {
            p.category = newCat;
            count++;
        }
    });

    saveData().then(() => {
        showNotification(`${count} ürün kategorisi güncellendi`, 'success');
        closeBulkCategoryModal();
        renderProductsTable();
    });
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    initData();
});

// ==================== Pricing Management ====================
const PRICING_HISTORY_KEY = 'mickeys_pricing_history';
let pricingHistory = JSON.parse(localStorage.getItem(PRICING_HISTORY_KEY)) || [];

function initializePricingSection() {
    console.log('initializePricingSection called');

    // Update category selects for pricing section
    updatePricingCategorySelects();
    console.log('updatePricingCategorySelects done');

    // Initialize percentage preview
    updatePercentagePreview();
    console.log('updatePercentagePreview done');

    updateRoundingPreview();
    console.log('updateRoundingPreview done');

    // Event listeners for percentage change
    document.getElementById('pricePercentage')?.addEventListener('input', updatePercentagePreview);
    document.getElementById('pricingCategoryFilter')?.addEventListener('change', updatePercentagePreview);

    // Event listeners for rounding change
    document.getElementById('roundingValue')?.addEventListener('change', updateRoundingPreview);
    document.getElementById('roundingCategoryFilter')?.addEventListener('change', updateRoundingPreview);
    document.querySelectorAll('input[name="roundingMethod"]').forEach(radio => {
        radio.addEventListener('change', updateRoundingPreview);
    });

    // Button handlers
    document.getElementById('previewPercentageBtn')?.addEventListener('click', updatePercentagePreview);
    document.getElementById('applyPercentageBtn')?.addEventListener('click', applyPercentageChange);
    document.getElementById('previewRoundingBtn')?.addEventListener('click', updateRoundingPreview);
    document.getElementById('applyRoundingBtn')?.addEventListener('click', applyRoundingChange);

    // Render pricing history
    renderPricingHistory();
}

function updatePricingCategorySelects() {
    const pricingCategorySelect = document.getElementById('pricingCategoryFilter');
    const roundingCategorySelect = document.getElementById('roundingCategoryFilter');

    const options = '<option value="">Tüm Kategoriler</option>' +
        Object.keys(adminCategories).map(key =>
            `<option value="${key}">${adminCategories[key].name}</option>`
        ).join('');

    if (pricingCategorySelect) {
        pricingCategorySelect.innerHTML = options;
    }

    if (roundingCategorySelect) {
        roundingCategorySelect.innerHTML = options;
    }
}

function getFilteredProducts(categoryFilter) {
    if (!categoryFilter) {
        return [...adminMenuData];
    }
    return adminMenuData.filter(p => p.category === categoryFilter);
}

function updatePercentagePreview() {
    const percentageInput = document.getElementById('pricePercentage');
    const categoryFilterInput = document.getElementById('pricingCategoryFilter');
    const affectedCountEl = document.getElementById('affectedProductsCount');
    const examplePriceEl = document.getElementById('examplePriceChange');

    if (!percentageInput || !affectedCountEl || !examplePriceEl) {
        return; // Elements not yet available
    }

    const percentage = parseFloat(percentageInput.value) || 0;
    const categoryFilter = categoryFilterInput?.value || '';

    const filteredProducts = getFilteredProducts(categoryFilter);
    const affectedCount = filteredProducts.length;

    // Update affected products count
    affectedCountEl.textContent = affectedCount;

    // Update example price change
    const examplePrice = 100;
    const newPrice = examplePrice * (1 + percentage / 100);
    examplePriceEl.textContent = `${newPrice.toFixed(0)}₺`;
}

function updateRoundingPreview() {
    const roundingValueEl = document.getElementById('roundingValue');
    const roundingCategoryEl = document.getElementById('roundingCategoryFilter');
    const affectedCountEl = document.getElementById('roundingAffectedCount');
    const examplesContainer = document.getElementById('roundingExamples');

    if (!roundingValueEl || !affectedCountEl || !examplesContainer) {
        return; // Elements not yet available
    }

    const roundingValue = parseInt(roundingValueEl.value) || 5;
    const categoryFilter = roundingCategoryEl?.value || '';
    const method = document.querySelector('input[name="roundingMethod"]:checked')?.value || 'round';

    const filteredProducts = getFilteredProducts(categoryFilter);

    // Count products that will be affected (price would change)
    let affectedCount = 0;
    const examples = [];

    filteredProducts.forEach(product => {
        const newPrice = roundPrice(product.price, roundingValue, method);
        if (newPrice !== product.price) {
            affectedCount++;
            if (examples.length < 3) {
                examples.push({
                    name: product.name.substring(0, 20) + (product.name.length > 20 ? '...' : ''),
                    oldPrice: product.price,
                    newPrice: newPrice
                });
            }
        }
    });

    // Update affected count
    affectedCountEl.textContent = affectedCount;

    // Update examples
    if (examples.length > 0) {
        examplesContainer.innerHTML = examples.map(ex => `
            <div class="preview-example-item">
                <span class="product-name">${ex.name}:</span>
                <span class="old-price">${ex.oldPrice}₺</span>
                <span class="arrow">→</span>
                <span class="new-price">${ex.newPrice}₺</span>
            </div>
        `).join('');
    } else {
        examplesContainer.innerHTML = '<div class="preview-example-item"><span style="color: var(--text-muted);">Değişiklik yapılacak ürün yok</span></div>';
    }
}

function roundPrice(price, roundTo, method) {
    switch (method) {
        case 'ceil':
            return Math.ceil(price / roundTo) * roundTo;
        case 'floor':
            return Math.floor(price / roundTo) * roundTo;
        case 'round':
        default:
            return Math.round(price / roundTo) * roundTo;
    }
}

// Pending action for confirm modal
let pendingConfirmAction = null;

function showConfirmModal(message, onConfirm) {
    document.getElementById('confirmMessage').textContent = message;
    pendingConfirmAction = onConfirm;
    document.getElementById('confirmModal').classList.add('active');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('active');
    pendingConfirmAction = null;
}

function confirmAction() {
    if (pendingConfirmAction) {
        pendingConfirmAction();
    }
    closeConfirmModal();
}

function applyPercentageChange() {
    const percentage = parseFloat(document.getElementById('pricePercentage')?.value) || 0;
    const categoryFilter = document.getElementById('pricingCategoryFilter')?.value || '';

    if (percentage === 0) {
        showNotification('Lütfen bir yüzde değeri girin', 'error');
        return;
    }

    const filteredProducts = getFilteredProducts(categoryFilter);

    if (filteredProducts.length === 0) {
        showNotification('Uygulanacak ürün bulunamadı', 'error');
        return;
    }

    const categoryName = categoryFilter ? adminCategories[categoryFilter]?.name : 'Tüm Kategoriler';
    const confirmMessage = `${filteredProducts.length} ürünün fiyatı %${percentage > 0 ? '+' : ''}${percentage} ${percentage > 0 ? 'artırılacak' : 'azaltılacak'}. Devam etmek istiyor musunuz?`;

    // Show custom modal instead of confirm()
    showConfirmModal(confirmMessage, function () {
        // Store old prices for history
        const changes = [];

        // Apply percentage change
        filteredProducts.forEach(product => {
            const oldPrice = product.price;
            const newPrice = Math.round(product.price * (1 + percentage / 100) * 100) / 100;

            // Find and update in adminMenuData
            const index = adminMenuData.findIndex(p => p.id === product.id);
            if (index >= 0) {
                adminMenuData[index].price = newPrice;
                changes.push({
                    productId: product.id,
                    productName: product.name,
                    oldPrice: oldPrice,
                    newPrice: newPrice
                });
            }
        });

        // Save to localStorage
        saveData();

        // Add to history
        addToPricingHistory({
            type: 'percentage',
            percentage: percentage,
            category: categoryName,
            affectedProducts: changes.length,
            changes: changes,
            timestamp: new Date().toISOString()
        });

        // Update UI
        renderProductsTable();
        updatePercentagePreview();
        renderPricingHistory();

        showNotification(`${changes.length} ürünün fiyatı %${percentage > 0 ? '+' : ''}${percentage} ${percentage > 0 ? 'artırıldı' : 'azaltıldı'}`, 'success');
    });
}

function applyRoundingChange() {
    const roundingValue = parseInt(document.getElementById('roundingValue')?.value) || 5;
    const categoryFilter = document.getElementById('roundingCategoryFilter')?.value || '';
    const method = document.querySelector('input[name="roundingMethod"]:checked')?.value || 'round';

    const filteredProducts = getFilteredProducts(categoryFilter);

    if (filteredProducts.length === 0) {
        showNotification('Uygulanacak ürün bulunamadı', 'error');
        return;
    }

    // Count actual changes
    const changes = [];
    filteredProducts.forEach(product => {
        const newPrice = roundPrice(product.price, roundingValue, method);
        if (newPrice !== product.price) {
            changes.push({
                productId: product.id,
                productName: product.name,
                oldPrice: product.price,
                newPrice: newPrice
            });
        }
    });

    if (changes.length === 0) {
        showNotification('Yuvarlanacak ürün bulunamadı', 'info');
        return;
    }

    const categoryName = categoryFilter ? adminCategories[categoryFilter]?.name : 'Tüm Kategoriler';
    const methodNames = { round: 'En Yakın', ceil: 'Yukarı', floor: 'Aşağı' };
    const confirmMessage = `${changes.length} ürünün fiyatı ${roundingValue}₺'ye ${methodNames[method].toLowerCase()} yuvarlanacak. Devam etmek istiyor musunuz?`;

    // Show custom modal instead of confirm()
    showConfirmModal(confirmMessage, function () {
        // Apply rounding
        changes.forEach(change => {
            const index = adminMenuData.findIndex(p => p.id === change.productId);
            if (index >= 0) {
                adminMenuData[index].price = change.newPrice;
            }
        });

        // Save to localStorage
        saveData();

        // Add to history
        addToPricingHistory({
            type: 'rounding',
            roundingValue: roundingValue,
            method: method,
            category: categoryName,
            affectedProducts: changes.length,
            changes: changes,
            timestamp: new Date().toISOString()
        });

        // Update UI
        renderProductsTable();
        updateRoundingPreview();
        renderPricingHistory();

        showNotification(`${changes.length} ürünün fiyatı ${roundingValue}₺'ye yuvarlandı`, 'success');
    });
}

function addToPricingHistory(entry) {
    pricingHistory.unshift(entry);
    // Keep only last 20 entries
    if (pricingHistory.length > 20) {
        pricingHistory = pricingHistory.slice(0, 20);
    }
    localStorage.setItem(PRICING_HISTORY_KEY, JSON.stringify(pricingHistory));
}

function renderPricingHistory() {
    const container = document.getElementById('pricingHistoryList');
    if (!container) return;

    if (pricingHistory.length === 0) {
        container.innerHTML = '<div class="pricing-history-empty">Henüz fiyat değişikliği yapılmadı.</div>';
        return;
    }

    container.innerHTML = pricingHistory.map((entry, index) => {
        const date = new Date(entry.timestamp);
        const timeStr = date.toLocaleString('tr-TR', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        let icon, iconClass, actionText, detailsText;

        if (entry.type === 'percentage') {
            if (entry.percentage > 0) {
                icon = '📈';
                iconClass = 'increase';
                actionText = `%${entry.percentage} Artış`;
            } else {
                icon = '📉';
                iconClass = 'decrease';
                actionText = `%${Math.abs(entry.percentage)} Azalış`;
            }
            detailsText = `${entry.category} - ${entry.affectedProducts} ürün`;
        } else {
            icon = '🔄';
            iconClass = 'round';
            const methodNames = { round: 'En Yakın', ceil: 'Yukarı', floor: 'Aşağı' };
            actionText = `${entry.roundingValue}₺'ye Yuvarlama (${methodNames[entry.method]})`;
            detailsText = `${entry.category} - ${entry.affectedProducts} ürün`;
        }

        return `
            <div class="pricing-history-item">
                <div class="pricing-history-icon ${iconClass}">${icon}</div>
                <div class="pricing-history-content">
                    <div class="pricing-history-action">${actionText}</div>
                    <div class="pricing-history-details">${detailsText}</div>
                </div>
                <div class="pricing-history-time">${timeStr}</div>
                <button class="pricing-history-undo" onclick="undoPricingChange(${index})" title="Geri Al">
                    ↩ Geri Al
                </button>
            </div>
        `;
    }).join('');
}

function undoPricingChange(index) {
    const entry = pricingHistory[index];
    if (!entry || !entry.changes) {
        showNotification('Bu değişiklik geri alınamaz', 'error');
        return;
    }

    showConfirmModal('Bu fiyat değişikliğini geri almak istediğinizden emin misiniz?', function () {
        // Revert changes
        entry.changes.forEach(change => {
            const productIndex = adminMenuData.findIndex(p => p.id === change.productId);
            if (productIndex >= 0) {
                adminMenuData[productIndex].price = change.oldPrice;
            }
        });

        // Remove from history
        pricingHistory.splice(index, 1);
        localStorage.setItem(PRICING_HISTORY_KEY, JSON.stringify(pricingHistory));

        // Save and update
        saveData();
        renderProductsTable();
        renderPricingHistory();
        updatePercentagePreview();
        updateRoundingPreview();

        showNotification('Fiyat değişikliği geri alındı', 'success');
    });
}

window.exportToExcel = function () {
    if (!adminMenuData || adminMenuData.length === 0) {
        showNotification('Dışa aktarılacak ürün yok', 'error');
        return;
    }

    const exportData = adminMenuData.map(p => ({
        ID: p.id,
        Name: p.name,
        Category: p.category,
        Price: p.price,
        Description: p.description,
        Variants: p.variants ? p.variants.map(v => `${v.name}:${v.price}`).join(', ') : ''
    }));

    const ws = XLSX.utils.json_to_sheet(exportData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Products");
    XLSX.writeFile(wb, "mickeys_menu_products.xlsx");
};
