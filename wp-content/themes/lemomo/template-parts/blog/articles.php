<?php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$current_cat = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';

$args = [
    'posts_per_page' => 10,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ($current_cat) {
    $args['category_name'] = $current_cat;
}

$blog_query = new WP_Query($args);
$categories = get_categories(['hide_empty' => true]);
$filter_cats = ['Update', 'Informasi', 'Trivia', 'Event'];
$all_posts = $blog_query->posts;
$highlight = !empty($all_posts) ? $all_posts[0] : null;
$grid_posts = array_slice($all_posts, 1);
?>

<section class="blog-articles">
    <div class="container">

        <div class="blog-articles__header">
            <h2 class="blog-articles__title">Artikel lainnya</h2>
            <div class="blog-articles__filters">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"
                   class="blog-articles__filter<?php echo !$current_cat ? ' blog-articles__filter--active' : ''; ?>">
                    Semua
                </a>
                <?php foreach ($filter_cats as $fc) :
                    $cat_obj = get_category_by_slug(strtolower($fc));
                    if (!$cat_obj) continue;
                    $is_active = (strtolower($current_cat) === strtolower($cat_obj->slug));
                ?>
                <a href="<?php echo esc_url(add_query_arg('cat', $cat_obj->slug)); ?>"
                   class="blog-articles__filter<?php echo $is_active ? ' blog-articles__filter--active' : ''; ?>">
                    <?php echo esc_html($fc); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($highlight) :
            setup_postdata($highlight);
            $h_cats = get_the_category($highlight->ID);
            $h_cat = !empty($h_cats) ? $h_cats[0]->name : '';
            $h_thumb = get_the_post_thumbnail_url($highlight->ID, 'large');
        ?>
        <article class="blog-highlight">
            <div class="blog-highlight__content">
                <h3 class="blog-highlight__title"><?php echo esc_html($highlight->post_title); ?></h3>
                <p class="blog-highlight__excerpt"><?php
                    $excerpt = $highlight->post_excerpt ?: wp_trim_words($highlight->post_content, 40, '......');
                    echo esc_html($excerpt);
                ?></p>
                <a href="<?php echo esc_url(get_permalink($highlight->ID)); ?>" class="blog-highlight__link">
                    <span>Baca Selengkapnya</span>
                </a>
            </div>
            <?php if ($h_thumb) : ?>
            <div class="blog-highlight__image">
                <img src="<?php echo esc_url($h_thumb); ?>" alt="<?php echo esc_attr($highlight->post_title); ?>">
            </div>
            <?php endif; ?>
        </article>
        <?php wp_reset_postdata(); endif; ?>

        <?php if (!empty($grid_posts)) : ?>
        <div class="blog-grid">
            <?php
            $id_months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            foreach ($grid_posts as $gp) :
                setup_postdata($gp);
                $g_cats = get_the_category($gp->ID);
                $g_cat = !empty($g_cats) ? $g_cats[0]->name : '';
                $g_thumb = get_the_post_thumbnail_url($gp->ID, 'medium_large');
                $g_date = get_the_date('j', $gp->ID) . ' ' . $id_months[(int)get_the_date('n', $gp->ID) - 1] . ' ' . get_the_date('Y', $gp->ID);
            ?>
            <a href="<?php echo esc_url(get_permalink($gp->ID)); ?>" class="blog-card">
                <div class="blog-card__img">
                    <?php if ($g_thumb) : ?>
                    <img src="<?php echo esc_url($g_thumb); ?>" alt="<?php echo esc_attr($gp->post_title); ?>">
                    <?php endif; ?>
                </div>
                <?php if ($g_cat) : ?>
                <span class="blog-card__tag"><?php echo esc_html($g_cat); ?></span>
                <?php endif; ?>
                <h4 class="blog-card__title"><?php echo esc_html($gp->post_title); ?></h4>
                <time class="blog-card__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d', $gp->ID)); ?>">
                    <?php echo esc_html($g_date); ?>
                </time>
            </a>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

        <?php if ($blog_query->max_num_pages > 1) : ?>
        <div class="explore-pagination">
            <?php if ($paged > 1) : ?>
            <a href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>" class="explore-pagination__btn" aria-label="Previous page">
                <svg viewBox="0 0 10 10"><polyline points="7 2 3 5 7 8"/></svg>
            </a>
            <?php else : ?>
            <span class="explore-pagination__btn" aria-disabled="true" style="opacity:0.4;cursor:default;">
                <svg viewBox="0 0 10 10"><polyline points="7 2 3 5 7 8"/></svg>
            </span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $blog_query->max_num_pages; $i++) : ?>
                <?php if ($i === $paged) : ?>
                <span class="explore-pagination__btn explore-pagination__btn--active" aria-label="Page <?php echo $i; ?>">
                    <span><?php echo $i; ?></span>
                </span>
                <?php else : ?>
                <a href="<?php echo esc_url(get_pagenum_link($i)); ?>" class="explore-pagination__btn" aria-label="Page <?php echo $i; ?>">
                    <span><?php echo $i; ?></span>
                </a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($paged < $blog_query->max_num_pages) : ?>
            <a href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>" class="explore-pagination__btn" aria-label="Next page">
                <svg viewBox="0 0 10 10"><polyline points="3 2 7 5 3 8"/></svg>
            </a>
            <?php else : ?>
            <span class="explore-pagination__btn" aria-disabled="true" style="opacity:0.4;cursor:default;">
                <svg viewBox="0 0 10 10"><polyline points="3 2 7 5 3 8"/></svg>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; wp_reset_query(); ?>

    </div>
</section>
