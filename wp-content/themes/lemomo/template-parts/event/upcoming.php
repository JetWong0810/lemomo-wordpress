<?php
/**
 * Event Upcoming Section
 * "Event Selanjutnya" 标题 + 副标题 + 倒计时
 */
$assets = get_template_directory_uri() . '/assets/images/';

$next_event_title = get_field('event_next_title') ?: 'Event Selanjutnya';
$next_event_subtitle = get_field('event_next_subtitle') ?: 'Nantikan keseruan event Lemomo selanjutnya';
$next_event_date = get_field('event_next_date') ?: '';
$next_event_label = get_field('event_next_label') ?: 'Hide and Show Event';
?>

<section class="event-upcoming">
    <div class="container">
        <div class="event-upcoming__header">
            <h2 class="event-upcoming__title"><?php echo esc_html($next_event_title); ?></h2>
            <img src="<?php echo esc_url($assets . 'event-title-deco.svg'); ?>"
                 alt="" class="event-upcoming__deco" aria-hidden="true">
            <p class="event-upcoming__subtitle"><?php echo esc_html($next_event_subtitle); ?></p>
        </div>

        <div class="event-upcoming__banner">
            <div class="event-upcoming__banner-left">
                <span class="event-upcoming__banner-label"><?php echo esc_html($next_event_label); ?></span>
            </div>

            <div class="event-upcoming__banner-right">
                <div class="event-upcoming__countdown" data-target="<?php echo esc_attr($next_event_date); ?>">
                    <div class="event-upcoming__countdown-icon">
                        <img src="<?php echo esc_url($assets . 'event-countdown-icon.svg'); ?>" alt="">
                    </div>
                    <div class="event-upcoming__countdown-item">
                        <span class="event-upcoming__countdown-num" data-unit="hours">00</span>
                        <span class="event-upcoming__countdown-label">Jam</span>
                    </div>
                    <span class="event-upcoming__countdown-sep"></span>
                    <div class="event-upcoming__countdown-item">
                        <span class="event-upcoming__countdown-num" data-unit="minutes">00</span>
                        <span class="event-upcoming__countdown-label">Menit</span>
                    </div>
                    <span class="event-upcoming__countdown-sep"></span>
                    <div class="event-upcoming__countdown-item">
                        <span class="event-upcoming__countdown-num" data-unit="seconds">00</span>
                        <span class="event-upcoming__countdown-label">Detik</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
