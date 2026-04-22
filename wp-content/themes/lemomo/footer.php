<?php
$copyright = get_field('footer_copyright', 'option') ?: '© 2026 Lemomo. All rights reserved.';
$cta_link  = get_field('header_cta_link', 'option') ?: '#';
$img_dir   = get_template_directory_uri() . '/assets/images';
?>

<footer class="site-footer">
    <div class="footer-gradient-bar"></div>
    <div class="container">
        <div class="footer-main">

            <div class="footer-logo">
                <img src="<?php echo esc_url($img_dir . '/logo.svg'); ?>" alt="Lemomo" class="footer-logo__img">
            </div>

            <div class="footer-links">
                <ul>
                    <li><a href="https://share.lemomo.id/user_agreement.html" target="_blank" rel="noopener noreferrer">Perjanjian pengguna</a></li>
                    <li><a href="https://share.lemomo.id/privacy_policy.html" target="_blank" rel="noopener noreferrer">Kebijakan Privasi</a></li>
                    <li><a href="https://share.lemomo.id/after_sale.html" target="_blank" rel="noopener noreferrer">Ketentuan Titip Jual & Subsidi</a></li>
                    <li><a href="https://share.lemomo.id/delivery_policy.html" target="_blank" rel="noopener noreferrer">Kebijakan Pengiriman</a></li>
                    <li><a href="https://web.lemomo.id/about/" target="_blank" rel="noopener noreferrer">Tentang Kami</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <div class="footer-contact__block">
                    <span class="footer-contact__icon">📍</span>
                    <div>
                        <h4 class="footer-contact__label">Kantor Pusat</h4>
                        <address class="footer-contact__text">
                            Mandiri Inhealth Tower, Jl. Prof. DR. Satrio<br>
                            Kav. E-IV No.6, Lantai 27 unit D&amp;E
                        </address>
                    </div>
                </div>
                <div class="footer-contact__row">
                    <div class="footer-contact__block">
                        <span class="footer-contact__icon">💬</span>
                        <div>
                            <h4 class="footer-contact__label">WhatsAPP</h4>
                            <a href="https://wa.me/628231204794" class="footer-contact__text">+62 823-1204-7941</a>
                        </div>
                    </div>
                    <div class="footer-contact__block">
                        <span class="footer-contact__icon">✉️</span>
                        <div>
                            <h4 class="footer-contact__label">Email</h4>
                            <span class="footer-contact__text">care@lemomoid.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-download">
                <p class="footer-download__label">Download Lemomo</p>
                <div class="footer-download__badges">
                    <a href="https://play.google.com/store/apps/details?id=com.lemomo.indonesia.shopping&pcampaignid=web_share" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo esc_url($img_dir . '/google-play-badge-56586a.png'); ?>" alt="Get it on Google Play" class="footer-badge">
                    </a>
                    <a href="https://apps.apple.com/id/app/lemomo/id6755808367" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo esc_url($img_dir . '/app-store-badge-56586a.png'); ?>" alt="Download on App Store" class="footer-badge">
                    </a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <div class="footer-bottom__social">
                <span class="footer-bottom__social-label">Ikuti Kami:</span>
                <img src="<?php echo esc_url($img_dir . '/social-icons.svg'); ?>" alt="Social Media" class="footer-bottom__social-icons">
            </div>
            <p class="footer-bottom__copyright"><?php echo esc_html($copyright); ?></p>
        </div>
    </div>
</footer>

<?php
$modal_img_dir = get_template_directory_uri() . '/assets/images';
?>
<div class="download-modal" id="downloadModal">
    <div class="download-modal__backdrop"></div>
    <div class="download-modal__dialog">
        <button class="download-modal__close" aria-label="Close">&times;</button>
        <div class="download-modal__body">
            <img src="<?php echo esc_url($modal_img_dir . '/logo.svg'); ?>"
                 alt="Lemomo" class="download-modal__logo">
            <div class="download-modal__badges">
                <a href="https://play.google.com/store/apps/details?id=com.lemomo.indonesia.shopping&pcampaignid=web_share"
                   target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo esc_url($modal_img_dir . '/google-play-badge-56586a.png'); ?>"
                         alt="Get it on Google Play" class="download-modal__badge">
                </a>
                <a href="https://apps.apple.com/id/app/lemomo/id6755808367"
                   target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo esc_url($modal_img_dir . '/app-store-badge-56586a.png'); ?>"
                         alt="Download on App Store" class="download-modal__badge">
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Customer Support Widget (ZhiChi) -->
<div class="support-widget">
    <a href="https://sg.sobot.io/chat/pc/v6/index.html?sysnum=e468cb48ae4e4f7791126bcbec1e411d&schemeId=1986275635513253888"
       target="_blank"
       rel="noopener noreferrer"
       class="support-widget__btn"
       aria-label="Customer Support">
        <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
            <path d="M266.666667 597.333333a32 32 0 0 1-32 32h-42.666667A117.333333 117.333333 0 0 1 74.666667 512v-42.666667A117.333333 117.333333 0 0 1 192 352h20.394667c32.298667-152.32 142.848-256 299.605333-256 156.757333 0 267.306667 103.68 299.605333 256h20.394667A117.333333 117.333333 0 0 1 949.333333 469.333333v42.666667a117.333333 117.333333 0 0 1-117.333333 117.333333l-13.226667 0.085334c-11.52 114.176-63.146667 184.576-168.192 227.754666A117.333333 117.333333 0 0 1 416 853.333333a117.333333 117.333333 0 0 1 218.752-59.008c89.173333-39.253333 122.581333-100.992 122.581333-218.325333v-128c0-170.325333-96-288-245.333333-288S266.666667 277.674667 266.666667 448z m266.666666 202.666667a53.333333 53.333333 0 1 0 0 106.666667 53.333333 53.333333 0 0 0 0-106.666667z m298.666667-384h-10.666667v149.333333h10.666667c29.44 0 53.333333-23.893333 53.333333-53.333333v-42.666667c0-29.44-23.893333-53.333333-53.333333-53.333333z m-629.333333 0H192c-29.44 0-53.333333 23.893333-53.333333 53.333333v42.666667c0 29.44 23.893333 53.333333 53.333333 53.333333h10.666667v-149.333333z" fill="#ffffff"/>
        </svg>
    </a>
</div>

<?php wp_footer(); ?>
</body>
</html>
