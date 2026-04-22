<?php
/**
 * Template Name: Explore
 */
get_header();

$acf_active = function_exists('get_field');
$theme_uri  = get_template_directory_uri();
$demo_thumb = $theme_uri . '/assets/images/explore-thumb.webp';

$page_title    = $acf_active ? (get_field('explore_title') ?: 'Explore  Lemomo') : 'Explore  Lemomo';
$page_subtitle = $acf_active ? get_field('explore_subtitle') : '';
$main_thumb    = $acf_active ? get_field('explore_main_thumbnail') : null;
$main_video    = $acf_active ? get_field('explore_main_video_url') : '';

$ep_page     = max(1, isset($_GET['ep_page']) ? (int)$_GET['ep_page'] : 1);
$ep_per_page = 12;

// 总数由 app-api 列表接口的完整数组长度决定（接口无分页参数）
$ep_total       = function_exists('lemomo_get_video_episodes_total') ? lemomo_get_video_episodes_total() : 0;
$ep_total_pages = $ep_total > 0 ? (int)ceil($ep_total / $ep_per_page) : 1;
$episodes       = function_exists('lemomo_get_video_episodes') ? lemomo_get_video_episodes($ep_page, $ep_per_page) : [];

if (!$page_subtitle) {
    $page_subtitle = 'Jelajahi Lemomo melalui video panduan dan temukan cara mudah menikmati semua fiturnya.';
}

?>

<main class="site-main page-explore">

    <div class="explore-bg-deco" aria-hidden="true"></div>

    <section class="explore-hero">
        <div class="container">
            <h1 class="explore-hero__title"><?php echo esc_html($page_title); ?></h1>
            <?php if ($page_subtitle) : ?>
                <p class="explore-hero__subtitle"><?php echo esc_html($page_subtitle); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($episodes) || $main_video) :
    $first_ep_video = (!empty($episodes[0]['video_url'])) ? $episodes[0]['video_url'] : $main_video;
    $first_ep_thumb = (!empty($episodes[0]['thumbnail'])) ? $episodes[0]['thumbnail'] : ($main_thumb ? $main_thumb['url'] : $demo_thumb);
    ?>
    <section class="explore-main-video">
        <div class="container">
            <div class="explore-player" id="explorePlayer"
                 data-video="<?php echo esc_url($first_ep_video); ?>"
                 data-thumb="<?php echo esc_url($first_ep_thumb); ?>">
                <div class="explore-player__container">
                    <img src="<?php echo esc_url($first_ep_thumb); ?>"
                         alt="Explore Lemomo"
                         class="explore-player__thumb">
                    <span class="explore-player__play-btn" aria-hidden="true">&#9654; PLAY</span>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($episodes)) : ?>
    <section class="explore-episodes">
        <div class="container">
            <h2 class="explore-episodes__title">Semua Episode</h2>
            <div class="episodes-grid">
                <?php foreach ($episodes as $index => $ep) : ?>
                    <div class="episode-card"
                         data-video="<?php echo esc_url($ep['video_url'] ?? ''); ?>"
                         role="button"
                         tabindex="0">
                        <div class="episode-card__thumb-wrap">
                            <img src="<?php echo esc_url($ep['thumbnail'] ?: $demo_thumb); ?>"
                                 alt="<?php echo esc_attr($ep['title'] ?? ''); ?>"
                                 class="episode-card__thumb"
                                 loading="lazy">
                            <span class="episode-card__play-btn" aria-hidden="true">
                                <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="22" cy="22" r="22" fill="rgba(0,0,0,0.45)"/>
                                    <polygon points="17,13 35,22 17,31" fill="#ffffff"/>
                                </svg>
                            </span>
                        </div>
                        <div class="episode-card__info">
                            <?php if (!empty($ep['title'])) : ?>
                                <p class="episode-card__title"><?php echo esc_html($ep['title']); ?></p>
                            <?php endif; ?>
                            <div class="episode-card__meta">
                                <span class="episode-card__badge">
                                    EP. <?php echo esc_html($ep['episode_number'] ?? ($index + 1)); ?>
                                </span>
                                <?php
                                $views = $ep['view_count'] ?? '';
                                if ($views) : ?>
                                    <span class="episode-card__views">
                                        <svg class="episode-card__views-icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
                                            <path d="M8 3C4.36 3 1.26 5.28.25 8.5c1.01 3.22 4.11 5.5 7.75 5.5s6.74-2.28 7.75-5.5C14.74 5.28 11.64 3 8 3zm0 9.17a3.67 3.67 0 110-7.34 3.67 3.67 0 010 7.34zM8 7a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" fill="currentColor"/>
                                        </svg>
                                        <?php echo esc_html($views); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($ep_total_pages > 1) : ?>
            <div class="explore-pagination">
                <?php
                $prev_url = $ep_page > 1
                    ? add_query_arg('ep_page', $ep_page - 1)
                    : '';
                $next_url = $ep_page < $ep_total_pages
                    ? add_query_arg('ep_page', $ep_page + 1)
                    : '';
                ?>

                <?php if ($prev_url) : ?>
                <a href="<?php echo esc_url($prev_url); ?>" class="explore-pagination__btn" aria-label="Previous page">
                    <svg viewBox="0 0 10 10"><polyline points="7 2 3 5 7 8"/></svg>
                </a>
                <?php else : ?>
                <span class="explore-pagination__btn" aria-disabled="true" style="opacity:.4;cursor:default;">
                    <svg viewBox="0 0 10 10"><polyline points="7 2 3 5 7 8"/></svg>
                </span>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $ep_total_pages; $p++) : ?>
                    <?php if ($p === $ep_page) : ?>
                    <span class="explore-pagination__btn explore-pagination__btn--active" aria-label="Page <?php echo $p; ?>">
                        <span><?php echo $p; ?></span>
                    </span>
                    <?php else : ?>
                    <a href="<?php echo esc_url(add_query_arg('ep_page', $p)); ?>"
                       class="explore-pagination__btn" aria-label="Page <?php echo $p; ?>">
                        <span><?php echo $p; ?></span>
                    </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($next_url) : ?>
                <a href="<?php echo esc_url($next_url); ?>" class="explore-pagination__btn" aria-label="Next page">
                    <svg viewBox="0 0 10 10"><polyline points="3 2 7 5 3 8"/></svg>
                </a>
                <?php else : ?>
                <span class="explore-pagination__btn" aria-disabled="true" style="opacity:.4;cursor:default;">
                    <svg viewBox="0 0 10 10"><polyline points="3 2 7 5 3 8"/></svg>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
