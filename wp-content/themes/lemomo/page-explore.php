<?php
/**
 * Template Name: Explore
 */
get_header();

$acf_active = function_exists('get_field');
$theme_uri  = get_template_directory_uri();
$demo_thumb = $theme_uri . '/assets/images/explore-thumb.png';

$page_title    = $acf_active ? (get_field('explore_title') ?: 'Explore  Lemomo') : 'Explore  Lemomo';
$page_subtitle = $acf_active ? get_field('explore_subtitle') : '';
$main_thumb    = $acf_active ? get_field('explore_main_thumbnail') : null;
$main_video    = $acf_active ? get_field('explore_main_video_url') : '';

$episodes = function_exists('lemomo_get_video_episodes') ? lemomo_get_video_episodes() : [];

if (!$page_subtitle) {
    $page_subtitle = 'Jelajahi Lemomo melalui video panduan dan temukan cara mudah menikmati semua fiturnya.';
}

if (empty($episodes)) {
    $demo_titles = [
        'Tutorial: Download dan Daftar Akun Baru',
        'Tutorial: Download dan Daftar Akun Baru',
        'Tutorial: Download dan Daftar Akun Baru',
        'Tutorial: Download dan Daftar Akun Baru',
        'Tutorial: Download dan Daftar Akun Baru',
        'Tutorial: Download dan Daftar Akun Baru',
    ];
    foreach ($demo_titles as $i => $t) {
        $episodes[] = [
            'title'          => $t,
            'thumbnail'      => $demo_thumb,
            'video_url'      => '#',
            'episode_number' => $i + 2,
            'view_count'     => '3,5K',
        ];
    }
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

    <section class="explore-main-video">
        <div class="container">
            <div class="video-player"
                 data-video="<?php echo esc_url($main_video); ?>"
                 role="button"
                 tabindex="0"
                 aria-label="Play main video">
                <?php if ($main_thumb) : ?>
                    <img src="<?php echo esc_url($main_thumb['url']); ?>"
                         alt="<?php echo esc_attr($main_thumb['alt']); ?>"
                         class="video-player__thumb">
                <?php else : ?>
                    <img src="<?php echo esc_url($demo_thumb); ?>"
                         alt="Explore Lemomo"
                         class="video-player__thumb">
                <?php endif; ?>
                <span class="video-player__play-btn" aria-hidden="true">&#9654; PLAY</span>
            </div>
        </div>
    </section>

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
                        <?php if (!empty($ep['thumbnail'])) : ?>
                            <img src="<?php echo esc_url($ep['thumbnail']); ?>"
                                 alt="<?php echo esc_attr($ep['title'] ?? ''); ?>"
                                 class="episode-card__thumb"
                                 loading="lazy">
                        <?php endif; ?>
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

            <div class="explore-pagination">
                <button class="explore-pagination__btn" aria-label="Previous page" disabled>
                    <svg viewBox="0 0 10 10"><polyline points="7 2 3 5 7 8"/></svg>
                </button>
                <button class="explore-pagination__btn explore-pagination__btn--active" aria-label="Page 1">
                    <span>1</span>
                </button>
                <button class="explore-pagination__btn" aria-label="Page 2">
                    <span>2</span>
                </button>
                <button class="explore-pagination__btn" aria-label="Next page">
                    <svg viewBox="0 0 10 10"><polyline points="3 2 7 5 3 8"/></svg>
                </button>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
