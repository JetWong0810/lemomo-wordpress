<?php
$featured = new WP_Query([
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

if (!$featured->have_posts()) return;
?>

<section class="blog-featured">
    <div class="container">
        <h2 class="blog-featured__title">Artikel Terbaru</h2>
    </div>
    <div class="blog-featured__track-wrapper">
        <div class="blog-featured__track">
            <?php while ($featured->have_posts()) : $featured->the_post();
                $cats = get_the_category();
                $cat_name = !empty($cats) ? $cats[0]->name : 'Uncategorized';
                $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            ?>
            <a href="<?php the_permalink(); ?>" class="blog-featured__card">
                <div class="blog-featured__card-img">
                    <?php if ($thumb_url) : ?>
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    <span class="blog-featured__card-tag"><?php echo esc_html($cat_name); ?></span>
                </div>
                <h3 class="blog-featured__card-title"><?php the_title(); ?></h3>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
