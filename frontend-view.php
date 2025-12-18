<?php 
if (!isset($layout)) $layout = 'full';
if (!isset($theme_settings)) $theme_settings = [];

$logoType = isset($theme_settings['logoType']) ? $theme_settings['logoType'] : 'text';
$logoText = !empty($theme_settings['logoText']) ? $theme_settings['logoText'] : "MICKEY'S";
$logoImage = isset($theme_settings['logoImage']) ? $theme_settings['logoImage'] : '';
?>
<div class="mickeys-menu-wrapper <?php echo $layout === 'embed' ? 'embed-mode' : ''; ?>">
    <?php if ($layout === 'full'): ?>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-container">
                <?php if ($logoType === 'image' && !empty($logoImage)): ?>
                    <img src="<?php echo esc_url($logoImage); ?>" alt="<?php echo esc_attr($logoText); ?>" class="logo-image" style="max-height: 50px;">
                <?php else: ?>
                    <h1 class="logo"><?php echo esc_html($logoText); ?></h1>
                <?php endif; ?>
            </div>
            <button class="allergen-btn" id="allergenBtn">
                ALERJENLER
            </button>
            <div class="lang-switcher" style="display: flex; gap: 5px; margin: 0 10px;">
                <button onclick="toggleLanguage('tr')" class="lang-btn" title="Türkçe" style="background:none; border:none; font-size:1.5rem; cursor:pointer; padding:0;">🇹🇷</button>
                <button onclick="toggleLanguage('en')" class="lang-btn" title="English" style="background:none; border:none; font-size:1.5rem; cursor:pointer; padding:0;">🇬🇧</button>
            </div>
            <button class="contact-btn" id="contactBtn">
                GET IN TOUCH
            </button>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="banner-slider">
            <div class="banner-slide active">
                <div class="banner-content">
                    <div class="banner-pattern"></div>
                    <h2 class="banner-title">Her gün 14:00-19:00 arası tüm kokteyllerde</h2>
                    <div class="banner-discount">%20 <span>İNDİRİM</span></div>
                    <div class="banner-drinks">
                        <div class="drink-icon">🍹</div>
                        <div class="drink-icon">🍸</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </section>
    <?php endif; ?>

    <!-- Menu Accordion -->
    <main class="menu-accordion" id="menuAccordion">
        <!-- Categories will be dynamically inserted here -->
    </main>

    <!-- Item Detail Modal -->
    <div class="modal" id="itemModal">
        <div class="modal-overlay" id="modalOverlay"></div>
        <div class="modal-content">
            <button class="modal-close" id="modalClose">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <div class="modal-body" id="modalBody">
                <!-- Modal content will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <!-- Allergen Modal -->
    <div class="modal" id="allergenModal">
        <div class="modal-overlay" id="allergenOverlay"></div>
        <div class="modal-content allergen-modal-content">
            <button class="modal-close" id="allergenClose">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <div class="modal-body">
                <h2 class="allergen-title">Alerjen Bilgileri</h2>
                <div class="allergen-list">
                    <div class="allergen-item">
                        <span class="allergen-icon">🥜</span>
                        <div>
                            <h3>Fındık ve Yerfıstığı</h3>
                            <p>Bazı ürünlerimizde fındık, ceviz ve yerfıstığı bulunmaktadır.</p>
                        </div>
                    </div>
                    <div class="allergen-item">
                        <span class="allergen-icon">🥛</span>
                        <div>
                            <h3>Süt Ürünleri</h3>
                            <p>Peynir, krema ve süt içeren ürünlerimiz mevcuttur.</p>
                        </div>
                <div class="allergens-grid" id="allergensGrid">
                    <!-- Populated by JS -->
                </div>
                <p class="allergen-note">
                    ⚠️ Alerji veya özel beslenme ihtiyacınız varsa lütfen garsonlarımıza danışınız.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php if ($layout === 'full'): ?>
    <footer class="footer">
        <div class="container">
            <div class="footer-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; padding: 3rem 0;">
                <div class="footer-col">
                    <h3>Contact</h3>
                    <p><?php echo isset($theme_settings['footerContact']) && !empty($theme_settings['footerContact']) ? nl2br(esc_html($theme_settings['footerContact'])) : '+90 555 123 45 67<br>Minerva Han, Karakoy'; ?></p>
                </div>
                <div class="footer-col">
                    <h3>Hours</h3>
                    <p><?php echo isset($theme_settings['footerHours']) && !empty($theme_settings['footerHours']) ? nl2br(esc_html($theme_settings['footerHours'])) : 'Mon - Sun: 10:00 - 02:00'; ?></p>
                </div>
                <div class="footer-col">
                    <h3>Get in Touch</h3>
                    <div class="social-links" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                        <a href="#" class="social-link" style="width: 40px; height: 40px; border: 1px solid currentColor; border-radius: 50%; display: flex; align-items: center; justify-content: center;">IG</a>
                        <a href="#" class="social-link" style="width: 40px; height: 40px; border: 1px solid currentColor; border-radius: 50%; display: flex; align-items: center; justify-content: center;">FB</a>
                    </div>
                    <!-- Language Switcher Footer -->
                    <div class="lang-switcher" style="display: flex; gap: 10px;">
                        <button onclick="toggleLanguage('tr')" class="lang-btn" style="background:none; border:none; font-size:1.5rem; cursor:pointer; padding:0;">🇹🇷</button>
                        <button onclick="toggleLanguage('en')" class="lang-btn" style="background:none; border:none; font-size:1.5rem; cursor:pointer; padding:0;">🇬🇧</button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; text-align: center;">
                <p><?php echo isset($theme_settings['footerCopyright']) && !empty($theme_settings['footerCopyright']) ? esc_html($theme_settings['footerCopyright']) : '&copy; 2024 Mickey\'s Restaurant'; ?></p>
            </div>
        </div>
    </footer>
    <?php endif; ?>
</div>
