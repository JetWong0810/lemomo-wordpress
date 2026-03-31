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
                    <li><a href="#">Perjanjian pengguna</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Ketentuan Titip Jual & Subsidi</a></li>
                    <li><a href="#">Kebijakan Pengiriman</a></li>
                    <li><a href="#">Tentang Kami</a></li>
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
                            <a href="mailto:care@lemomoid.com" class="footer-contact__text">care@lemomoid.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-download">
                <p class="footer-download__label">Download Lemomo</p>
                <div class="footer-download__badges">
                    <a href="<?php echo esc_url($cta_link); ?>">
                        <img src="<?php echo esc_url($img_dir . '/google-play-badge-56586a.png'); ?>" alt="Get it on Google Play" class="footer-badge">
                    </a>
                    <a href="<?php echo esc_url($cta_link); ?>">
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

<?php wp_footer(); ?>
</body>
</html>
