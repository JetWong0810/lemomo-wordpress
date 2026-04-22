<?php
/**
 * Blog Featured — 顶部横向滚动卡片（最新5篇文章）
 * 数据来源：WordPress 普通文章（post），取最新5条
 */
$featured_query = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

if (!$featured_query->have_posts()) return;
?>

<section class="blog-featured">
    <div class="container">
        <h2 class="blog-featured__title">Artikel Terbaru</h2>
    </div>
    <div class="blog-featured__track-wrapper">
        <div class="blog-featured__track">
            <?php while ($featured_query->have_posts()) : $featured_query->the_post();
                $cats = get_the_category();
                $cat_name = !empty($cats) ? $cats[0]->name : '';
                $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            ?>
            <a href="<?php the_permalink(); ?>"
               class="blog-featured__card">
                <div class="blog-featured__card-img">
                    <?php if ($thumb_url) : ?>
                    <img src="<?php echo esc_url($thumb_url); ?>"
                         alt="<?php the_title_attribute(); ?>"
                         loading="lazy">
                    <?php endif; ?>
                    <?php if ($cat_name) : ?>
                    <span class="blog-featured__card-tag"><?php echo esc_html($cat_name); ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="blog-featured__card-title"><?php the_title(); ?></h3>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
