<?php
/**
 * Template Name: About Us
 * Slug: about
 */
get_header(); ?>

<main class="site-main page-about">

    <?php get_template_part('template-parts/about/hero'); ?>

    <?php get_template_part('template-parts/about/mission'); ?>

    <?php get_template_part('template-parts/about/filosofi'); ?>

    <?php get_template_part('template-parts/about/guarantee'); ?>

    <?php get_template_part('template-parts/about/payment'); ?>

    <?php get_template_part('template-parts/about/media'); ?>

    <?php get_template_part('template-parts/about/contact'); ?>

</main>

<?php get_footer(); ?>
