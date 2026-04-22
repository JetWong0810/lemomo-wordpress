<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$cta_text = get_field('header_cta_text', 'option') ?: 'Download App';
$cta_link = get_field('header_cta_link', 'option') ?: '#';
?>

<header class="site-header">
    <div class="container site-header__inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.svg'); ?>"
                 alt="Lemomo" class="site-logo__img">
        </a>

        <nav class="site-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ]);
            ?>
            <a href="#" class="btn-download btn-download--mobile js-open-download">
                <?php echo esc_html($cta_text); ?>
            </a>
        </nav>

        <a href="#" class="btn-download js-open-download">
            <?php echo esc_html($cta_text); ?>
        </a>

        <button class="hamburger" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
