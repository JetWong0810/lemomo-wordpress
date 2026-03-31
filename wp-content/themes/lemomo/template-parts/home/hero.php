<?php
$title    = get_field('hero_title')    ?: 'Satu Aplikasi<br>Banyak Untungnya';
$subtitle = get_field('hero_subtitle') ?: 'E-Commerce Berbasis Minat Pertama di Indonesia';
$cta_text = get_field('hero_cta_text') ?: 'Pelajari Selengkapnya';
$cta_link = get_field('hero_cta_link') ?: '#';
$hero_image = get_field('hero_image');
$img_dir  = get_template_directory_uri() . '/assets/images';
?>

<section class="hero">
    <div class="hero__bg">
        <div class="hero__bg-gradient"></div>
        <img src="<?php echo esc_url($img_dir . '/hero-bg-texture-56586a.png'); ?>" alt="" class="hero__bg-texture" aria-hidden="true">
        <div class="hero__bg-overlay"></div>
    </div>

    <div class="container hero__inner">
        <div class="hero__media">
            <?php if ($hero_image) : ?>
                <img src="<?php echo esc_url($hero_image['url']); ?>"
                     alt="<?php echo esc_attr($hero_image['alt']); ?>"
                     class="hero__img">
            <?php else : ?>
                <img src="<?php echo esc_url($img_dir . '/hero-phones-399c27.png'); ?>"
                     alt="Lemomo App" class="hero__img">
            <?php endif; ?>
        </div>

        <div class="hero__content">
            <p class="hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <h1 class="hero__title"><?php echo wp_kses($title, ['br' => []]); ?></h1>
            <a href="<?php echo esc_url($cta_link); ?>" class="btn-cta">
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>
    </div>

    <div class="hero__curve"></div>
</section>
