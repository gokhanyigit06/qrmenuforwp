<div class="wrap mickeys-admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1 class="logo">MICKEY'S</h1>
            <p class="logo-subtitle">Admin Panel</p>
        </div>

        <nav class="sidebar-nav">
            <button class="nav-item active" data-section="products">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                Ürün Yönetimi
            </button>

            <button class="nav-item" data-section="categories">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                Kategoriler
            </button>

            <button class="nav-item" data-section="allergens">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                </svg>
                Alerjenler
            </button>

            <button class="nav-item" data-section="banners">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="15" rx="2" ry="2" />
                    <polyline points="17 2 12 7 7 2" />
                </svg>
                Banner Yönetimi
            </button>

            <button class="nav-item" data-section="excel">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
                Excel İşlemleri
            </button>

            <button class="nav-item" data-section="pricing">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23" />
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                Fiyat Değişimi
            </button>

            <button class="nav-item" data-section="settings">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                    </path>
                </svg>
                Görsel Düzenlemeler
            </button>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Products Section -->
        <section class="content-section active" id="products-section">
            <div class="section-header">
                <h2 class="section-title">Ürün Yönetimi</h2>
                <div class="header-actions" style="display: flex; gap: 0.5rem;">
                    <button class="btn-secondary" onclick="exportToExcel()" title="Excel İndir">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Excel
                    </button>
                    <button class="btn-primary" id="addProductBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Yeni Ürün Ekle
                    </button>
                </div>
            </div>

            <div class="filters">
                <div class="search-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" id="productSearch" placeholder="Ürün ara...">
                </div>

                <select id="categoryFilter" class="filter-select">
                    <option value="">Tüm Kategoriler</option>
                    <option value="starters">Başlangıçlar</option>
                    <option value="snacks">Atıştırmalıklar</option>
                    <option value="salads">Salatalar</option>
                    <option value="asian">Asya Mutfağı</option>
                    <option value="pizza">Pizza</option>
                    <option value="burgers">Burgerler</option>
                    <option value="pasta">Makarna</option>
                    <option value="mexican">Meksika Mutfağı</option>
                    <option value="mains">Ana Yemekler</option>
                    <option value="desserts">Tatlılar</option>
                    <option value="drinks">İçecekler</option>
                </select>
            </div>

            <div class="bulk-actions"
                style="margin-bottom: 1rem; display: flex; gap: 1rem; align-items: center; background: #fff; padding: 1rem; border-radius: 8px; border: 1px solid #eee;">
                <select id="bulkActionSelect" style="padding: 0.5rem;">
                    <option value="">Toplu İşlem Seç</option>
                    <option value="delete">Seçilenleri Sil</option>
                    <option value="allergens">Etiket/Alerjen Ata</option>
                    <option value="category">Kategori Değiştir</option>
                </select>
                <button class="btn-secondary" id="applyBulkActionBtn" onclick="handleBulkAction()">Uygula</button>
                <span id="selectedCount" style="color: #666; font-size: 0.9rem;">0 ürün seçildi</span>
            </div>

            <div class="products-table">
                <table id="productsTable">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAllProducts"
                                    onchange="toggleSelectAll(this)"></th>
                            <th>ID</th>
                            <th>Görsel</th>
                            <th>Ürün Adı</th>
                            <th>Kategori</th>
                            <th>Fiyat</th>
                            <th>Etiketler</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <!-- Products will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
            <div id="productPagination"
                style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                <!-- JS will populate -->
            </div>
        </section>

        <!-- Categories Section -->
        <section class="content-section" id="categories-section">
            <div class="section-header">
                <h2 class="section-title">Kategori Yönetimi</h2>
                <button class="btn-primary" id="addCategoryBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Yeni Kategori Ekle
                </button>
            </div>

            <div class="categories-grid" id="categoriesGrid">
                <!-- Categories will be dynamically inserted here -->
            </div>
        </section>

        <!-- Allergens Section -->
        <section class="content-section" id="allergens-section">
            <div class="section-header">
                <h2 class="section-title">Alerjen Yönetimi</h2>
                <button class="btn-primary" id="addAllergenBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Yeni Alerjen Ekle
                </button>
            </div>

            <div class="allergens-list" id="allergensList">
                <!-- Allergens will be dynamically inserted here -->
            </div>
        </section>

        <!-- Banners Section -->
        <section class="content-section" id="banners-section">
            <div class="section-header">
                <h2 class="section-title">Banner Yönetimi</h2>
                <button class="btn-primary" id="addBannerBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Yeni Banner Ekle
                </button>
            </div>

            <div class="banners-grid" id="bannersGrid">
                <!-- Banners will be dynamically inserted here -->
            </div>
        </section>

        <!-- Excel Section -->
        <section class="content-section" id="excel-section">
            <div class="section-header">
                <h2 class="section-title">Excel İşlemleri</h2>
            </div>

            <div class="excel-actions">
                <div class="excel-card">
                    <div class="excel-icon">📥</div>
                    <h3>Excel'den İçe Aktar</h3>
                    <p>Toplu ürün güncellemesi yapmak için Excel dosyası yükleyin</p>
                    <input type="file" id="excelImport" accept=".xlsx,.xls" style="display: none;">
                    <button class="btn-secondary" onclick="document.getElementById('excelImport').click()">
                        Dosya Seç
                    </button>
                </div>

                <div class="excel-card">
                    <div class="excel-icon">📤</div>
                    <h3>Excel'e Aktar</h3>
                    <p>Mevcut ürünleri Excel dosyası olarak indirin</p>
                    <button class="btn-secondary" id="exportExcel">
                        Excel İndir
                    </button>
                </div>

                <div class="excel-card">
                    <div class="excel-icon">📋</div>
                    <h3>Şablon İndir</h3>
                    <p>Ürün ekleme için Excel şablonunu indirin</p>
                    <button class="btn-secondary" id="downloadTemplate">
                        Şablon İndir
                    </button>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="content-section" id="pricing-section">
            <div class="section-header">
                <h2 class="section-title">Fiyat Değişimi</h2>
            </div>

            <div class="pricing-container">
                <!-- Yüzdelik Fiyat Değişimi -->
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <div class="pricing-icon">📊</div>
                        <h3>Yüzdelik Fiyat Değişimi</h3>
                    </div>
                    <p class="pricing-description">Tüm ürünlerin veya seçili kategorinin fiyatlarını yüzdelik olarak
                        artırın veya azaltın.</p>

                    <div class="pricing-form">
                        <div class="form-group">
                            <label>Kategori Seçin</label>
                            <select id="pricingCategoryFilter" class="filter-select"
                                onchange="updatePercentagePreview()">
                                <option value="">Tüm Kategoriler</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Değişim Yüzdesi (%)</label>
                            <div class="percentage-input-wrapper">
                                <input type="number" id="pricePercentage" value="10" min="-100" max="1000" step="0.1"
                                    oninput="updatePercentagePreview()">
                                <span class="percentage-suffix">%</span>
                            </div>
                            <small class="form-hint">Pozitif değer = artış, Negatif değer = azalış</small>
                        </div>

                        <div class="pricing-preview" id="percentagePreview">
                            <div class="preview-title">Önizleme</div>
                            <div class="preview-content">
                                <span class="preview-label">Etkilenecek ürün sayısı:</span>
                                <span class="preview-value" id="affectedProductsCount">0</span>
                            </div>
                            <div class="preview-content">
                                <span class="preview-label">Örnek: 100₺ →</span>
                                <span class="preview-value" id="examplePriceChange">110₺</span>
                            </div>
                        </div>

                        <div class="pricing-actions">
                            <button class="btn-secondary" id="previewPercentageBtn" onclick="updatePercentagePreview()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                                Önizle
                            </button>
                            <button class="btn-primary" id="applyPercentageBtn" onclick="applyPercentageChange()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Uygula
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Fiyat Yuvarlama -->
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <div class="pricing-icon">🔄</div>
                        <h3>Fiyat Yuvarlama</h3>
                    </div>
                    <p class="pricing-description">Fiyatları en yakın değere yuvarlayarak düzgün fiyatlar oluşturun.</p>

                    <div class="pricing-form">
                        <div class="form-group">
                            <label>Kategori Seçin</label>
                            <select id="roundingCategoryFilter" class="filter-select"
                                onchange="updateRoundingPreview()">
                                <option value="">Tüm Kategoriler</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Yuvarlama Değeri</label>
                            <select id="roundingValue" class="filter-select" onchange="updateRoundingPreview()">
                                <option value="1">1₺'ye yuvarla</option>
                                <option value="5" selected>5₺'ye yuvarla</option>
                                <option value="10">10₺'ye yuvarla</option>
                                <option value="25">25₺'ye yuvarla</option>
                                <option value="50">50₺'ye yuvarla</option>
                                <option value="100">100₺'ye yuvarla</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Yuvarlama Yöntemi</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="roundingMethod" value="round" checked
                                        onchange="updateRoundingPreview()">
                                    <span>En Yakın</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="roundingMethod" value="ceil"
                                        onchange="updateRoundingPreview()">
                                    <span>Yukarı</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="roundingMethod" value="floor"
                                        onchange="updateRoundingPreview()">
                                    <span>Aşağı</span>
                                </label>
                            </div>
                        </div>

                        <div class="pricing-preview" id="roundingPreview">
                            <div class="preview-title">Önizleme</div>
                            <div class="preview-content">
                                <span class="preview-label">Etkilenecek ürün sayısı:</span>
                                <span class="preview-value" id="roundingAffectedCount">0</span>
                            </div>
                            <div class="preview-examples" id="roundingExamples">
                                <!-- Örnekler dinamik olarak eklenecek -->
                            </div>
                        </div>

                        <div class="pricing-actions">
                            <button class="btn-secondary" id="previewRoundingBtn" onclick="updateRoundingPreview()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                                Önizle
                            </button>
                            <button class="btn-primary" id="applyRoundingBtn" onclick="applyRoundingChange()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Uygula
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fiyat Değişim Geçmişi -->
            <div class="pricing-history-section">
                <h3 class="pricing-history-title">Son Fiyat Değişiklikleri</h3>
                <div class="pricing-history-list" id="pricingHistoryList">
                    <div class="pricing-history-empty">Henüz fiyat değişikliği yapılmadı.</div>
                </div>
            </div>
        </section>

        <!-- Settings Section -->
        <section class="content-section" id="settings-section">
            <div class="section-header">
                <h2 class="section-title">Görsel Düzenlemeler</h2>
                <button class="btn-primary" id="saveSettingsBtn" onclick="saveThemeSettings()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Ayarları Kaydet
                </button>
            </div>

            <div class="form-grid" style="display: block;">
                <div class="excel-card"
                    style="text-align: left; max-width: 800px; margin: 0 auto; background: var(--bg-secondary);">
                    <h3>Tema Ayarları</h3>

                    <h4
                        style="margin-top: 1rem; border-bottom: 1px solid #ddd; margin-bottom: 1rem; padding-bottom: 0.5rem; color: #333;">
                        🎨 Hazır Tema Seç
                    </h4>
                    <div class="form-group">
                        <label>Tema Şablonu</label>
                        <select id="settingThemePreset" onchange="applyThemePreset(this.value)">
                            <option value="">-- Özel Ayarlar --</option>
                            <option value="modern-bistro">Modern Bistro (Siyah + Altın)</option>
                            <option value="fast-fresh">Fast & Fresh (Turuncu + Yeşil)</option>
                            <option value="fine-dining">Fine Dining (Bordo + Krem)</option>
                            <option value="ocean-blue">Ocean Blue (Deniz)</option>
                            <option value="italiano">Italiano (İtalyan)</option>
                            <option value="neon-nights">Neon Nights (Bar)</option>
                            <option value="rustic-wood">Rustic Wood (Geleneksel)</option>
                            <option value="minimalist">Minimalist (Siyah/Beyaz)</option>
                            <option value="tokyo-street">Tokyo Street (Japon)</option>
                            <option value="mexican-fiesta">Mexican Fiesta (Meksika)</option>
                            <option value="garden-fresh">Garden Fresh (Vejetaryen)</option>
                            <option value="royal-purple">Royal Purple (VIP)</option>
                            <option value="sunrise-cafe">Sunrise Cafe (Kahvaltı)</option>
                            <option value="smoky-bbq">Smoky BBQ (Steakhouse)</option>
                            <option value="ice-cream-dream">Ice Cream Dream (Tatlıcı)</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                        <div class="form-group">
                            <label>📐 Görünüm Modu</label>
                            <select id="settingViewMode">
                                <option value="grid">⊞ Kart Grid</option>
                                <option value="list">☰ Liste Kompakt</option>
                                <option value="gallery">⊠ Galeri</option>
                                <option value="magazine">◫ Magazine</option>
                                <option value="carousel">⟷ Carousel</option>
                                <option value="masonry">▦ Masonry</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>🌙 Karanlık Mod</label>
                            <select id="settingDarkMode">
                                <option value="light">Sadece Aydınlık</option>
                                <option value="dark">Sadece Karanlık</option>
                                <option value="auto">Otomatik (Cihaza Göre)</option>
                                <option value="user">Müşteri Seçimli</option>
                            </select>
                        </div>
                    </div>

                    <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #ddd;">

                    <p>Menü görünümünü özelleştirin. Fontlar ve renkler tüm menüde geçerli olacaktır.</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Logo Tipi</label>
                            <select id="settingLogoType" onchange="toggleLogoSettings()">
                                <option value="text">Metin (Yazı)</option>
                                <option value="image">Görsel (Resim)</option>
                            </select>
                        </div>
                        <div class="form-group" id="logoTextGroup">
                            <label>Logo Metni</label>
                            <input type="text" id="settingLogoText" placeholder="MICKEY'S">
                        </div>
                        <div class="form-group" id="logoImageGroup" style="display: none;">
                            <label>Logo Görseli</label>
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="text" id="settingLogoImage" placeholder="https://...">
                                <button type="button" class="btn-secondary"
                                    onclick="openMediaUploader('settingLogoImage')">Seç</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Kenar Yuvarlaklığı (px)</label>
                            <input type="number" id="settingBorderRadius" placeholder="12" value="12">
                        </div>
                        <div class="form-group">
                            <label>Çerçeve Kalınlığı (px)</label>
                            <input type="number" id="settingBorderWidth" placeholder="1" value="1">
                        </div>
                        <div class="form-group">
                            <label>Ana Font (Metinler)</label>
                            <select id="settingFontPrimary">
                                <option value="Outfit">Outfit (Modern Sans)</option>
                                <option value="Metropolis">Metropolis</option>
                                <option value="Inter">Inter</option>
                                <option value="Roboto">Roboto</option>
                                <option value="Open Sans">Open Sans</option>
                                <option value="Lato">Lato</option>
                                <option value="Poppins">Poppins</option>
                                <option value="Raleway">Raleway</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Başlık Fontu</label>
                            <select id="settingFontDisplay">
                                <option value="Playfair Display">Playfair Display (Serif)</option>
                                <option value="Merriweather">Merriweather</option>
                                <option value="Lora">Lora</option>
                                <option value="Montserrat">Montserrat</option>
                                <option value="Oswald">Oswald</option>
                                <option value="Bebas Neue">Bebas Neue</option>
                            </select>
                        </div>

                        <h4
                            style="margin-top: 1.5rem; border-bottom: 1px solid #ddd; margin-bottom: 1rem; padding-bottom: 0.5rem; color: #333;">
                            Yerleşim & Tipografi</h4>
                        <div class="form-group">
                            <label>Sayfa Genişliği</label>
                            <select id="settingLayoutWidth" onchange="toggleCustomWidth()">
                                <option value="full">Tam Genişlik (Full)</option>
                                <option value="container">Kutulu (1200px)</option>
                                <option value="narrow">Dar (800px)</option>
                                <option value="custom">Özel Genişlik (%)</option>
                            </select>
                        </div>
                        <div class="form-group" id="customWidthGroup" style="display: none;">
                            <label>Özel Genişlik (%)</label>
                            <input type="number" id="settingCustomWidth" min="50" max="100" value="90" placeholder="90">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label>Başlık Boyutu (px)</label>
                                <input type="number" id="settingHeaderFontSize" value="24" placeholder="24">
                            </div>
                            <div class="form-group">
                                <label>Harf Aralığı (px)</label>
                                <input type="number" id="settingHeaderSpacing" value="0" step="0.5" placeholder="0">
                            </div>
                        </div>

                        <h4
                            style="margin-top: 1.5rem; border-bottom: 1px solid #ddd; margin-bottom: 1rem; padding-bottom: 0.5rem; color: #333;">
                            Renkler</h4>
                        <div class="form-group">
                            <label>Ana Renk (Primary)</label>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="color" id="settingPrimaryColor" value="#8B4513"
                                    style="width: 50px; padding: 0; border: none; height: 40px;">
                                <input type="text" id="settingPrimaryColorText" value="#8B4513" style="flex: 1;"
                                    onchange="document.getElementById('settingPrimaryColor').value = this.value; this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Vurgu Rengi (Accent)</label>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="color" id="settingAccentColor" value="#C84B31"
                                    style="width: 50px; padding: 0; border: none; height: 40px;">
                                <input type="text" id="settingAccentColorText" value="#C84B31" style="flex: 1;"
                                    onchange="document.getElementById('settingAccentColor').value = this.value; this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Arka Plan Rengi</label>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="color" id="settingBgColor" value="#f8f9fa"
                                    style="width: 50px; padding: 0; border: none; height: 40px;">
                                <input type="text" id="settingBgColorText" value="#f8f9fa" style="flex: 1;"
                                    onchange="document.getElementById('settingBgColor').value = this.value; this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Metin Rengi</label>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="color" id="settingTextColor" value="#2D2D2D"
                                    style="width: 50px; padding: 0; border: none; height: 40px;">
                                <input type="text" id="settingTextColorText" value="#2D2D2D" style="flex: 1;"
                                    onchange="document.getElementById('settingTextColor').value = this.value; this.value = this.value.toUpperCase();">
                            </div>
                        </div>

                        <h4
                            style="margin-top: 1.5rem; border-bottom: 1px solid #ddd; margin-bottom: 1rem; padding-bottom: 0.5rem; color: #333;">
                            Footer (Alt Kısım)</h4>
                        <div class="form-group">
                            <label>Footer Başlık Rengi</label>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="color" id="settingFooterHeaderColor" value="#ffffff"
                                    style="width: 50px; padding: 0; border: none; height: 40px;">
                                <input type="text" id="settingFooterHeaderColorText" value="#ffffff" style="flex: 1;"
                                    onchange="document.getElementById('settingFooterHeaderColor').value = this.value; this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Footer Metin Rengi</label>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="color" id="settingFooterTextColor" value="#bbbbbb"
                                    style="width: 50px; padding: 0; border: none; height: 40px;">
                                <input type="text" id="settingFooterTextColorText" value="#bbbbbb" style="flex: 1;"
                                    onchange="document.getElementById('settingFooterTextColor').value = this.value; this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label>İletişim Metni</label>
                            <textarea id="settingFooterContact" rows="2" placeholder="Telefon: +90..."></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Çalışma Saatleri</label>
                            <textarea id="settingFooterHours" rows="2"
                                placeholder="Hafta içi: 10:00 - 22:00..."></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Copyright Metni</label>
                            <input type="text" id="settingFooterCopyright" placeholder="© 2024 Restaurant Restaurant">
                        </div>

                        <h4
                            style="margin-top: 1.5rem; border-bottom: 1px solid #ddd; margin-bottom: 1rem; padding-bottom: 0.5rem; color: #333; grid-column: 1 / -1;">
                            Buton & Sosyal Medya
                        </h4>
                        <div class="form-group">
                            <label>Get in Touch Buton Metni</label>
                            <input type="text" id="settingGetInTouchText" placeholder="İLETİŞİM" value="İLETİŞİM">
                        </div>
                        <div class="form-group">
                            <label>Get in Touch Buton Linki</label>
                            <input type="text" id="settingGetInTouchLink" placeholder="https://wa.me/905551234567"
                                value="#contact">
                        </div>
                        <div class="form-group">
                            <label>Instagram Linki</label>
                            <input type="text" id="settingInstagramLink" placeholder="https://instagram.com/yourpage">
                        </div>
                        <div class="form-group">
                            <label>Facebook Linki</label>
                            <input type="text" id="settingFacebookLink" placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>

                    <h4
                        style="margin-top: 1.5rem; border-bottom: 1px solid #ddd; margin-bottom: 1rem; padding-bottom: 0.5rem; color: #333; grid-column: 1 / -1;">
                        Kategori & Tab Ayarları
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Kategori Aralığı (px)</label>
                            <input type="number" id="settingCategorySpacing" placeholder="10" value="10">
                        </div>
                        <div class="form-group">
                            <label>Kategori İç Boşluk (Padding)</label>
                            <select id="settingCategoryPadding">
                                <option value="small">Dar</option>
                                <option value="medium" selected>Orta</option>
                                <option value="large">Geniş</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kategori Başlık Yüksekliği (px)</label>
                            <input type="number" id="settingCategoryHeaderHeight" placeholder="80" value="80" min="50"
                                max="300">
                            <p style="font-size: 0.8rem; color: #888; margin-top: 0.25rem;">📐 Görsel daha fazla
                                görünsün istiyorsanız yüksekliği artırın (örn: 120-150)</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="settingForceFullWidth" checked>
                            <span>Tam Genişliği Zorla (Kapsayıcıdan Taş)</span>
                        </label>
                        <p style="font-size: 0.8rem; color: #666; margin-top: 0.25rem;">Eğer tema menüyü daraltıyorsa
                            bunu işaretleyin.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Confirm Modal for Price Changes -->
    <div class="modal" id="confirmModal">
        <div class="modal-overlay" onclick="closeConfirmModal()"></div>
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <h3 class="modal-title">Onay</h3>
                <button class="modal-close" onclick="closeConfirmModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <p id="confirmMessage" style="font-size: 1rem; color: var(--text-primary); margin: 0;">
                    Bu işlemi gerçekleştirmek istediğinizden emin misiniz?
                </p>
            </div>
            <div class="modal-footer"
                style="display: flex; gap: 1rem; justify-content: flex-end; padding: 1rem 1.5rem; border-top: 1px solid var(--border-color);">
                <button class="btn-secondary" onclick="closeConfirmModal()">İptal</button>
                <button class="btn-primary" id="confirmOkBtn" onclick="confirmAction()">Onayla</button>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal" id="productModal">
        <div class="modal-overlay"></div>
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3 class="modal-title" id="productModalTitle">Yeni Ürün Ekle</h3>
                <button class="modal-close" onclick="closeProductModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <form id="productForm" class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Ürün Adı (Türkçe) *</label>
                        <input type="text" id="productName" required>
                    </div>

                    <div class="form-group">
                        <label>Ürün Adı (İngilizce)</label>
                        <input type="text" id="productNameEn">
                    </div>

                    <div class="form-group">
                        <label>Kategori *</label>
                        <select id="productCategory" required>
                            <option value="">Kategori Seçin</option>
                            <option value="starters">Başlangıçlar</option>
                            <option value="snacks">Atıştırmalıklar</option>
                            <option value="salads">Salatalar</option>
                            <option value="asian">Asya Mutfağı</option>
                            <option value="pizza">Pizza</option>
                            <option value="burgers">Burgerler</option>
                            <option value="pasta">Makarna</option>
                            <option value="mexican">Meksika Mutfağı</option>
                            <option value="mains">Ana Yemekler</option>
                            <option value="desserts">Tatlılar</option>
                            <option value="drinks">İçecekler</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Fiyat (₺) *</label>
                        <input type="number" id="productPrice" step="0.01" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Açıklama</label>
                        <textarea id="productDescription" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label" style="margin-bottom: 0.5rem; display: inline-flex;">
                            <input type="checkbox" id="productNoImage" onchange="toggleProductImageInput(this)">
                            <span>Görsel Yok (Listede görsel gizlenir)</span>
                        </label>
                        <label>Görsel URL</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" id="productImage" placeholder="https://..." style="flex: 1;">
                            <button type="button" class="btn-secondary"
                                onclick="openMediaUploader('productImage')">Seç</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Görsel Kodu</label>
                        <input type="text" id="productImageCode" placeholder="örn: burger-classic">
                    </div>

                    <div class="form-group full-width">
                        <label>Varyasyonlar (Opsiyonel)</label>
                        <p style="font-size: 0.8rem; color: #666; margin-bottom: 0.5rem;">Farklı porsiyon/boyut
                            seçenekleri için kullanın (örn: 33cl, 50cl, Kadeh, Şişe vb).</p>
                        <div id="productVariantsContainer"
                            style="margin-bottom: 0.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                            <!-- Variant rows will go here -->
                        </div>
                        <button type="button" class="btn-secondary btn-sm" onclick="addVariantRow()">+ Varyasyon
                            Ekle</button>
                    </div>

                    <div class="form-group full-width">
                        <label>Etiketler</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="tagSpicy" value="spicy">
                                <span>🌶️ Acılı</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" id="tagVegetarian" value="vegetarian">
                                <span>🌱 Vejetaryen</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeProductModal()">İptal</button>
                    <button type="submit" class="btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Allergen Modal -->
    <div class="modal" id="allergenModal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="allergenModalTitle">Yeni Alerjen Ekle</h3>
                <button class="modal-close" onclick="closeAllergenModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <form id="allergenForm" class="modal-body">
                <div class="form-group">
                    <label>Alerjen Adı *</label>
                    <input type="text" id="allergenName" required>
                </div>

                <div class="form-group">
                    <label>İkon (Emoji)</label>
                    <input type="text" id="allergenIcon" placeholder="🥜">
                </div>

                <div class="form-group">
                    <label>Açıklama *</label>
                    <textarea id="allergenDescription" rows="3" required></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeAllergenModal()">İptal</button>
                    <button type="submit" class="btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Modal -->
    <div class="modal" id="categoryModal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="categoryModalTitle">Yeni Kategori Ekle</h3>
                <button class="modal-close" onclick="closeCategoryModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <form id="categoryForm" class="modal-body">
                <div class="form-group">
                    <label>Kategori Anahtarı (Key) *</label>
                    <input type="text" id="categoryKey" required placeholder="örn: desserts">
                    <small style="color: var(--text-muted); font-size: 0.85rem;">Küçük harf, tire veya alt çizgi
                        kullanın</small>
                </div>

                <div class="form-group">
                    <label>Kategori Adı *</label>
                    <input type="text" id="categoryName" required placeholder="örn: Tatlılar">
                </div>

                <div class="form-group">
                    <label>İkon (Emoji) *</label>
                    <input type="text" id="categoryIcon" required placeholder="🍰">
                </div>

                <div class="form-group">
                    <label>Arka Plan Görseli URL</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" id="categoryBgImage" placeholder="https://..." style="flex: 1;">
                        <button type="button" class="btn-secondary"
                            onclick="openMediaUploader('categoryBgImage')">Seç</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Fiyat/Varyasyon Gösterimi</label>
                    <select id="categoryVariantDisplay">
                        <option value="modal">Standart (Detayda Seçim)</option>
                        <option value="list">Liste (Kadeh/Şişe vb. yan yana)</option>
                    </select>
                    <small style="color: var(--text-muted); font-size: 0.85rem;">Şarap veya Bira menüsü için 'Liste'
                        önerilir.</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeCategoryModal()">İptal</button>
                    <button type="submit" class="btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Banner Modal -->
    <div class="modal" id="bannerModal">
        <div class="modal-overlay"></div>
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3 class="modal-title" id="bannerModalTitle">Yeni Banner Ekle</h3>
                <button class="modal-close" onclick="closeBannerModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <form id="bannerForm" class="modal-body">
                <div class="form-group">
                    <label>Banner Başlığı (Opsiyonel)</label>
                    <input type="text" id="bannerTitle" placeholder="Görsel banner için boş bırakın">
                </div>

                <div class="form-group">
                    <label>Alt Başlık</label>
                    <input type="text" id="bannerSubtitle">
                </div>

                <div class="form-group">
                    <label>İndirim Oranı (%)</label>
                    <input type="number" id="bannerDiscount" min="0" max="100">
                </div>

                <div class="form-group">
                    <label>Arka Plan Rengi</label>
                    <input type="color" id="bannerBgColor" value="#C84B31">
                </div>

                <div class="form-group">
                    <label>Görsel URL</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" id="bannerImage" placeholder="https://..." style="flex: 1;">
                        <button type="button" class="btn-secondary"
                            onclick="openMediaUploader('bannerImage')">Seç</button>
                    </div>
                    <p style="font-size: 0.8rem; color: #888; margin-top: 0.5rem;">📐 Önerilen boyut: <strong>1400 x 400
                            piksel</strong></p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Genişlik (px)</label>
                        <input type="number" id="bannerWidth" placeholder="Otomatik" min="100" max="2000">
                    </div>
                    <div class="form-group">
                        <label>Yükseklik (px)</label>
                        <input type="number" id="bannerHeight" placeholder="Otomatik" min="50" max="800">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="bannerActive">
                        <span>Aktif</span>
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeBannerModal()">İptal</button>
                    <button type="submit" class="btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bulk Allergen Modal -->
<div class="modal" id="bulkAllergenModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeBulkAllergenModal()">&times;</span>
        <h2 class="modal-title">Toplu Etiket/Alerjen Atama</h2>
        <p>Seçilen ürünlere eklenecek veya çıkarılacak etiketleri belirleyin.</p>

        <div class="form-group">
            <label>Etiketler</label>
            <div class="tags-container">
                <label class="tag-checkbox">
                    <input type="checkbox" id="bulkTagSpicy">
                    <span>Acılı (Ekle)</span>
                </label>
                <label class="tag-checkbox">
                    <input type="checkbox" id="bulkTagVegetarian">
                    <span>Vejetaryen (Ekle)</span>
                </label>
            </div>
            <!-- Future: Custom Allergens -->
            <p style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">Şu an sadece ekleme işlemi yapar. Mevcut
                etiketleri korur.</p>
        </div>

        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeBulkAllergenModal()">İptal</button>
            <button class="btn-primary" onclick="saveBulkAllergens()">Kaydet</button>
        </div>
    </div>
</div>
</div>

<!-- Bulk Category Modal -->
<div class="modal" id="bulkCategoryModal">
    <div class="modal-content" style="max-width: 400px;">
        <span class="close-modal" onclick="closeBulkCategoryModal()">&times;</span>
        <h2 class="modal-title">Toplu Kategori Değiştir</h2>
        <p>Seçilen ürünlerin kategorisini güncelleyin.</p>

        <div class="form-group">
            <label>Yeni Kategori</label>
            <select id="bulkCategorySelect"
                style="width: 100%; padding: 0.5rem; margin-top: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Kategori Seçin...</option>
            </select>
        </div>

        <div class="modal-footer" style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.5rem;">
            <button class="btn-secondary" onclick="closeBulkCategoryModal()">İptal</button>
            <button class="btn-primary" onclick="applyBulkCategory()">Kaydet</button>
        </div>
    </div>
</div>