<?php
/**
 * Blog Media — Liputan Media（媒体报道列表）
 * 数据来源：后台左侧「媒体报道」CPT（lemomo_media）
 */
$media_paged = isset($_GET['media_page']) ? max(1, (int) $_GET['media_page']) : 1;
$per_page = 8;

$media_query = new WP_Query([
    'post_type'      => 'lemomo_media',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'paged'          => $media_paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

if (!$media_query->have_posts()) return;

$total_pages = $media_query->max_num_pages;
$img_dir = get_template_directory_uri() . '/assets/images';
?>

<section class="blog-media">
    <div class="blog-media__bg-pattern" aria-hidden="true">
        <img src="<?php echo esc_url($img_dir . '/blog-media-bg-pattern.svg'); ?>" alt="">
    </div>

    <div class="container">
        <h2 class="blog-media__title">Liputan Media</h2>

        <div class="blog-media__grid">
            <?php while ($media_query->have_posts()) : $media_query->the_post();
                $external_url = get_post_meta(get_the_ID(), '_lemomo_media_url', true);
                $thumb_url    = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                $link_url     = $external_url ?: '#';
                $domain       = '';
                if ($external_url) {
                    $parsed = wp_parse_url($external_url);
                    $domain = isset($parsed['host']) ? preg_replace('/^www\./', '', $parsed['host']) : '';
                }
            ?>
            <a href="<?php echo esc_url($link_url); ?>"
               class="blog-media__card"
               <?php echo $external_url ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                <div class="blog-media__card-img">
                    <?php if ($thumb_url) : ?>
                    <img src="<?php echo esc_url($thumb_url); ?>"
                         alt="<?php the_title_attribute(); ?>"
                         loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="blog-media__card-body">
                    <h4 class="blog-media__card-title"><?php the_title(); ?></h4>
                    <?php if ($domain) : ?>
                    <span class="blog-media__card-source"><?php echo esc_html($domain); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php if ($total_pages > 1) : ?>
        <div class="blog-media__pagination">
            <?php if ($media_paged > 1) : ?>
            <a href="<?php echo esc_url(add_query_arg('media_page', $media_paged - 1)); ?>" class="blog-media__page blog-media__page--arrow" aria-label="Previous">
                <svg width="8" height="12" viewBox="0 0 8 12" fill="none"><polyline points="6 1 1 6 6 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <?php else : ?>
            <span class="blog-media__page blog-media__page--arrow blog-media__page--disabled" aria-disabled="true">
                <svg width="8" height="12" viewBox="0 0 8 12" fill="none"><polyline points="6 1 1 6 6 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) :
                $is_active = ($i === $media_paged);
            ?>
                <?php if ($is_active) : ?>
                <span class="blog-media__page blog-media__page--active"><?php echo $i; ?></span>
                <?php else : ?>
                <a href="<?php echo esc_url(add_query_arg('media_page', $i)); ?>" class="blog-media__page"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($media_paged < $total_pages) : ?>
            <a href="<?php echo esc_url(add_query_arg('media_page', $media_paged + 1)); ?>" class="blog-media__page blog-media__page--arrow" aria-label="Next">
                <svg width="8" height="12" viewBox="0 0 8 12" fill="none"><polyline points="2 1 7 6 2 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <?php else : ?>
            <span class="blog-media__page blog-media__page--arrow blog-media__page--disabled" aria-disabled="true">
                <svg width="8" height="12" viewBox="0 0 8 12" fill="none"><polyline points="2 1 7 6 2 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
