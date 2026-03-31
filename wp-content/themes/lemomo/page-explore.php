<?php
/**
 * Template Name: Explore
 */
get_header();

$page_title    = get_field('explore_title') ?: 'Explore Lemomo';
$page_subtitle = get_field('explore_subtitle');
$main_thumb    = get_field('explore_main_thumbnail');
$main_video    = get_field('explore_main_video_url');

$episodes = lemomo_get_video_episodes();
?>

<main class="site-main page-explore">

    <section class="explore-hero">
        <div class="container">
            <h1 class="explore-hero__title"><?php echo esc_html($page_title); ?></h1>
            <?php if ($page_subtitle) : ?>
                <p class="explore-hero__subtitle"><?php echo esc_html($page_subtitle); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($main_video || $main_thumb) : ?>
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
                <?php endif; ?>
                <span class="video-player__play-btn" aria-hidden="true">&#9654; PLAY</span>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($episodes)) : ?>
    <section class="explore-episodes">
        <div class="container">
            <h2 class="explore-episodes__title">Semua Episode</h2>
            <div class="episodes-grid">
                <?php foreach ($episodes as $ep) : ?>
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
                        <?php if (!empty($ep['title'])) : ?>
                            <p class="episode-card__title"><?php echo esc_html($ep['title']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
