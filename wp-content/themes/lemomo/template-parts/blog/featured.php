<?php
/**
 * Blog Featured — 顶部横向滚动卡片（外链媒体报道）
 * 数据来源：后台左侧「媒体报道」CPT（lemomo_media）
 */
$media_query = new WP_Query([
    'post_type'      => 'lemomo_media',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order date',
    'order'          => 'DESC',
]);

if (!$media_query->have_posts()) return;
?>

<section class="blog-featured">
    <div class="container">
        <h2 class="blog-featured__title">Artikel Terbaru</h2>
    </div>
    <div class="blog-featured__track-wrapper">
        <div class="blog-featured__track">
            <?php while ($media_query->have_posts()) : $media_query->the_post();
                $external_url = get_post_meta(get_the_ID(), '_lemomo_media_url', true);
                $thumb_url    = get_the_post_thumbnail_url(get_the_ID(), 'large');
                if (!$external_url) continue;
            ?>
            <a href="<?php echo esc_url($external_url); ?>"
               class="blog-featured__card"
               target="_blank"
               rel="noopener noreferrer">
                <div class="blog-featured__card-img">
                    <?php if ($thumb_url) : ?>
                    <img src="<?php echo esc_url($thumb_url); ?>"
                         alt="<?php the_title_attribute(); ?>"
                         loading="lazy">
                    <?php endif; ?>
                    <span class="blog-featured__card-tag">Media</span>
                </div>
                <h3 class="blog-featured__card-title"><?php the_title(); ?></h3>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
