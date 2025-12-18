<?php
if (!isset($layout))
    $layout = 'full';
if (!isset($theme_settings))
    $theme_settings = [];

$logoType = isset($theme_settings['logoType']) ? $theme_settings['logoType'] : 'text';
$logoText = !empty($theme_settings['logoText']) ? $theme_settings['logoText'] : "MICKEY'S";
$logoImage = isset($theme_settings['logoImage']) ? $theme_settings['logoImage'] : '';

// Get in Touch settings
$getInTouchText = isset($theme_settings['getInTouchText']) ? $theme_settings['getInTouchText'] : 'GET IN TOUCH';
$getInTouchLink = isset($theme_settings['getInTouchLink']) ? $theme_settings['getInTouchLink'] : '#contact';
$instagramLink = isset($theme_settings['instagramLink']) ? $theme_settings['instagramLink'] : '#';
$facebookLink = isset($theme_settings['facebookLink']) ? $theme_settings['facebookLink'] : '#';
?>
<div class="mqrp-menu-wrapper <?php echo $layout === 'embed' ? 'embed-mode' : ''; ?>">
    <?php if ($layout === 'full'): ?>
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="logo-container">
                    <?php if ($logoType === 'image' && !empty($logoImage)): ?>
                        <img src="<?php echo esc_url($logoImage); ?>" alt="<?php echo esc_attr($logoText); ?>"
                            class="logo-image" style="max-height: 50px;">
                    <?php else: ?>
                        <h1 class="logo"><?php echo esc_html($logoText); ?></h1>
                    <?php endif; ?>
                </div>
                <button class="allergen-btn" id="allergenBtn">
                    <span data-lang-tr="ALERJENLER" data-lang-en="ALLERGENS">ALERJENLER</span>
                </button>
                <a href="<?php echo esc_url($getInTouchLink); ?>" class="contact-btn" id="contactBtn">
                    <span data-lang-tr="<?php echo esc_attr($getInTouchText); ?>"
                        data-lang-en="GET IN TOUCH"><?php echo esc_html($getInTouchText); ?></span>
                </a>
            </div>
        </header>

        <!-- Hero Banner -->
        <?php
        $banners_raw = get_option('mickey_mickeys_banners', '[]');
        $banners = json_decode($banners_raw, true);
        if (!$banners)
            $banners = [];
        $activeBanners = array_filter($banners, function ($b) {
            return !empty($b['active']); });
        $activeBanners = array_values($activeBanners); // Re-index
        $bannerCount = count($activeBanners);
        ?>
        <?php if ($bannerCount > 0): ?>
            <section class="hero-banner">
                <div class="banner-slider">
                    <?php foreach ($activeBanners as $index => $banner): ?>
                        <div class="banner-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="<?php
                                 if (!empty($banner['width']))
                                     echo 'max-width: ' . intval($banner['width']) . 'px;';
                                 if (!empty($banner['height']))
                                     echo 'height: ' . intval($banner['height']) . 'px;';
                                 ?>">
                            <?php if (!empty($banner['image'])): ?>
                                <img src="<?php echo esc_url($banner['image']); ?>" alt="<?php echo esc_attr($banner['title']); ?>"
                                    class="banner-image" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="banner-content"
                                    style="background: <?php echo esc_attr($banner['bgColor'] ?? '#C84B31'); ?>;">
                                    <div class="banner-pattern"></div>
                                    <?php if (!empty($banner['title'])): ?>
                                        <h2 class="banner-title"><?php echo esc_html($banner['title']); ?></h2>
                                    <?php endif; ?>
                                    <?php if (!empty($banner['discount'])): ?>
                                        <div class="banner-discount">%<?php echo intval($banner['discount']); ?> <span>İNDİRİM</span></div>
                                    <?php endif; ?>
                                    <?php if (!empty($banner['subtitle'])): ?>
                                        <p class="banner-subtitle"><?php echo esc_html($banner['subtitle']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($bannerCount > 1): ?>
                    <div class="banner-dots">
                        <?php for ($i = 0; $i < $bannerCount; $i++): ?>
                            <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $i; ?>)"></span>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Menu Accordion -->
    <?php
    $viewMode = 'grid';
    if (isset($theme_settings) && isset($theme_settings['viewMode'])) {
        $viewMode = $theme_settings['viewMode'];
    }
    ?>
    <main class="menu-accordion" id="menuAccordion" data-view="<?php echo esc_attr($viewMode); ?>">
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
                <h2 class="allergen-title" data-lang-tr="Alerjen Bilgileri" data-lang-en="Allergen Information">Alerjen
                    Bilgileri</h2>
                <div class="allergens-grid" id="allergensGrid">
                    <!-- Populated by JS -->
                </div>
                <p class="allergen-note">
                    <span data-lang-tr="⚠️ Alerji veya özel beslenme ihtiyacınız varsa lütfen garsonlarımıza danışınız."
                        data-lang-en="⚠️ If you have any allergies or special dietary needs, please consult our staff.">⚠️
                        Alerji veya özel beslenme ihtiyacınız varsa lütfen garsonlarımıza danışınız.</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php if ($layout === 'full'): ?>
        <footer class="footer">
            <footer class="footer">
                <div class="container" style="display: flex; flex-direction: column; align-items: center;">
                    <div class="footer-grid">
                        <div class="footer-col">
                            <h3 data-lang-tr="İletişim" data-lang-en="Contact">İletişim</h3>
                            <p><?php echo isset($theme_settings['footerContact']) && !empty($theme_settings['footerContact']) ? nl2br(esc_html($theme_settings['footerContact'])) : '+90 555 123 45 67<br>Minerva Han, Karakoy'; ?>
                            </p>
                        </div>
                        <div class="footer-col">
                            <h3 data-lang-tr="Çalışma Saatleri" data-lang-en="Hours">Çalışma Saatleri</h3>
                            <p><?php echo isset($theme_settings['footerHours']) && !empty($theme_settings['footerHours']) ? nl2br(esc_html($theme_settings['footerHours'])) : 'Pzt - Paz: 10:00 - 02:00'; ?>
                            </p>
                        </div>
                        <div class="footer-col">
                            <h3 data-lang-tr="Bize Ulaşın" data-lang-en="Get in Touch">Bize Ulaşın</h3>
                            <div class="social-links">
                                <a href="<?php echo esc_url($instagramLink); ?>" class="social-link" target="_blank"
                                    rel="noopener" aria-label="Instagram">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                </a>
                                <a href="<?php echo esc_url($facebookLink); ?>" class="social-link" target="_blank"
                                    rel="noopener" aria-label="Facebook">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                    </svg>
                                </a>
                            </div>
                            <!-- Language Switcher Footer -->
                            <div class="lang-switcher">
                                <button onclick="toggleLanguage('tr')" class="lang-btn" title="Türkçe">🇹🇷</button>
                                <button onclick="toggleLanguage('en')" class="lang-btn" title="English">🇬🇧</button>
                            </div>
                        </div>
                    </div>
                    <div class="footer-bottom">
                        <p><?php echo isset($theme_settings['footerCopyright']) && !empty($theme_settings['footerCopyright']) ? esc_html($theme_settings['footerCopyright']) : '&copy; 2024 Mickey\'s Restaurant'; ?>
                        </p>
                    </div>
                </div>
            </footer>
        <?php endif; ?>
</div>