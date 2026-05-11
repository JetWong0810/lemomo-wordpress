<?php
/**
 * Event Hero Section
 * 分层背景：渐变底色 → 半透明纹理 → 盒子图片 + 标题
 */
$assets = get_template_directory_uri() . '/assets/images/';
?>

<section class="event-hero">
    <div class="event-hero__bg-gradient" aria-hidden="true"></div>
    <?php lemomo_picture($assets . 'event-hero-bg-texture.png', '', 'event-hero__bg-texture', 'aria-hidden="true"'); ?>


    <div class="event-hero__content">
        <h1 class="event-hero__title">
            <?php echo esc_html(get_field('event_hero_title') ?: 'Temukan Semua Event Seru dari Lemomo'); ?>
        </h1>
    </div>

    <div class="event-hero__curve"></div>
</section>
