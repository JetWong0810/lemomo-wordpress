<?php
/**
 * Template Name: Event
 * Slug: event
 */
get_header(); ?>

<main class="site-main page-event">

    <?php get_template_part('template-parts/event/hero'); ?>

    <?php get_template_part('template-parts/event/upcoming'); ?>

    <?php get_template_part('template-parts/event/grid'); ?>

</main>

<?php get_footer(); ?>
