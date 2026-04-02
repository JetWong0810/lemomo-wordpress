<?php
$img_dir = get_template_directory_uri() . '/assets/images';
$title = get_field('about_hero_title') ?: 'Tentang Lemomo';
$desc  = get_field('about_hero_desc') ?: 'Lemomo adalah platform e-commerce berbasis minat di Indonesia yang menghadirkan pengalaman belanja yang berbeda melalui konsep blind box, belanja interaktif, dan fitur titip jual 24 jam.';
?>

<section class="about-hero">
    <div class="about-hero__bg">
        <div class="about-hero__bg-gradient"></div>
        <img src="<?php echo esc_url($img_dir . '/hero-bg-texture-56586a.png'); ?>" alt="" class="about-hero__bg-texture" aria-hidden="true">
        <img src="<?php echo esc_url($img_dir . '/about-hero-overlay.svg'); ?>" alt="" class="about-hero__bg-overlay" aria-hidden="true">
    </div>

    <div class="about-hero__inner">
        <div class="about-hero__media">
            <img src="<?php echo esc_url($img_dir . '/about-hero-phone-56586a.png'); ?>"
                 alt="Lemomo App" class="about-hero__img">
        </div>

        <div class="about-hero__content">
            <h1 class="about-hero__title"><?php echo esc_html($title); ?></h1>
            <p class="about-hero__desc"><?php echo esc_html($desc); ?></p>
        </div>
    </div>

    <img src="<?php echo esc_url($img_dir . '/about-hero-divider.svg'); ?>" alt="" class="about-hero__divider" aria-hidden="true">
    <div class="about-hero__curve"></div>
</section>
