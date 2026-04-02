<?php
/**
 * Event Hero Section
 * 合成背景图（渐变+纹理+产品图）+ 标题
 */
$assets = get_template_directory_uri() . '/assets/images/';
?>

<section class="event-hero">
    <img src="<?php echo esc_url($assets . 'event-hero-composite.png'); ?>"
         alt="" class="event-hero__bg-img" aria-hidden="true">

    <div class="event-hero__content">
        <h1 class="event-hero__title">
            <?php echo esc_html(get_field('event_hero_title') ?: 'Temukan Semua Event Seru dari Lemomo'); ?>
        </h1>
    </div>

    <div class="event-hero__curve"></div>
</section>
