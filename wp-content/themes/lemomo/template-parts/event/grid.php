<?php
/**
 * Event Grid Section
 * 事件卡片网格 + 底部装饰波浪
 * 数据优先级：外部 API > ACF > 静态兜底
 */
$assets = get_template_directory_uri() . '/assets/images/';

// ── 1. 尝试从 API 拉取线下活动 ──
$events     = [];
$api_active = function_exists('lemomo_get_offline_events');

if ($api_active) {
    $offline_data = lemomo_get_offline_events();
    $raw          = $offline_data['list'] ?? [];

    foreach ($raw as $item) {
        $id     = (int)($item['id'] ?? 0);
        $detail = ($id && function_exists('lemomo_get_lesson_detail')) ? lemomo_get_lesson_detail($id) : [];

        $events[] = [
            'id'        => $id,
            'title'     => $item['title']    ?? '',
            'date'      => '',
            'location'  => 'Offline',
            'desc'      => wp_strip_all_tags($detail['message'] ?? ''),
            'image'     => $item['picUrl']   ?? '',
            'video_url' => $detail['url']    ?? '',
            'is_url'    => true,
            'type'      => 'offline',
        ];
    }
}

// ── 2. API 无数据时 fallback ACF ──
if (empty($events)) {
    $acf_events = get_field('event_list');
    if ($acf_events) {
        foreach ($acf_events as $ev) {
            $img      = $ev['event_image'] ?? null;
            $events[] = [
                'title'    => $ev['event_title']       ?? '',
                'date'     => $ev['event_date']         ?? '',
                'location' => $ev['event_location']     ?? '',
                'desc'     => $ev['event_description']  ?? '',
                'image'    => $img ? $img['url'] : '',
                'is_url'   => true,
                'type'     => 'offline',
            ];
        }
    }
}

// ── 3. 最终兜底静态数据 ──
if (empty($events)) {
    $static = [
        ['title' => 'Foto Ramadan Challenge',   'date' => '20 Februari – 30 Maret 2026', 'location' => 'Online',                          'desc' => 'Campaign media sosial mengajak peserta berbagi momen Ramadan dan berhadiah menarik seperti liburan, emas, dan merchandise eksklusif.', 'image' => 'event-card1-56586a.png', 'type' => 'online'],
        ['title' => 'Foto Ramadan Challenge',   'date' => '20 Februari – 30 Maret 2026', 'location' => 'Online',                          'desc' => 'Campaign media sosial mengajak peserta berbagi momen Ramadan dan berhadiah menarik seperti liburan, emas, dan merchandise eksklusif.', 'image' => 'event-card4-56586a.png', 'type' => 'online'],
        ['title' => 'Foto Ramadan Challenge',   'date' => '20 Februari – 30 Maret 2026', 'location' => 'Online',                          'desc' => 'Campaign media sosial mengajak peserta berbagi momen Ramadan dan berhadiah menarik seperti liburan, emas, dan merchandise eksklusif.', 'image' => 'event-card7-56586a.png', 'type' => 'online'],
        ['title' => 'Foto Ramadan Challenge',   'date' => '20 Februari – 30 Maret 2026', 'location' => 'Online',                          'desc' => 'Campaign media sosial mengajak peserta berbagi momen Ramadan dan berhadiah menarik seperti liburan, emas, dan merchandise eksklusif.', 'image' => 'event-card2-56586a.png', 'type' => 'online'],
        ['title' => 'CFD Bareng Lemomo',        'date' => '28 Desember 2025',            'location' => 'Jakarta Pusat',                   'desc' => 'Aktivasi di area Car Free Day dengan mengajak audiens mencoba Blind Box Lemomo dan membagikan hadiah emas secara langsung.',           'image' => 'event-card5.png',        'type' => 'offline'],
        ['title' => 'Perempuan Berlari',        'date' => '30 November 2025',            'location' => 'Senayan Park Jakarta',            'desc' => 'Event lari khusus perempuan dengan aktivitas seru, merchandise eksklusif, dan hadiah emas dari Lemomo.',                                'image' => 'event-card8-56586a.png', 'type' => 'offline'],
        ['title' => 'Run For Humanity',         'date' => '23 November 2026',            'location' => 'Lapangan Sunburst - BSD City',    'desc' => '',                                                                                                                                     'image' => 'event-card3-56586a.png', 'type' => 'offline'],
        ['title' => 'Satu Padel Tournament',    'date' => '',                            'location' => 'TRT Padel and Tennis Court Bintaro', 'desc' => 'Kolaborasi pada ajang padel bergengsi yang diikuti selebriti dan influencer.',                                                    'image' => 'event-card6-56586a.png', 'type' => 'offline'],
    ];
    foreach ($static as $s) {
        $s['is_url'] = false;
        $events[]    = $s;
    }
}
?>

<section class="event-grid">
    <img src="<?php echo esc_url($assets . 'event-bg-wave.svg'); ?>"
         alt="" class="event-grid__bg-wave" aria-hidden="true">

    <div class="event-grid__list">
        <?php
        $fallback_event_img = $assets . 'event-card1-56586a.png';
        foreach ($events as $event) :
            if (!empty($event['is_url'])) {
                $img_src = !empty($event['image']) ? $event['image'] : $fallback_event_img;
            } else {
                $img_src = !empty($event['image']) ? $assets . $event['image'] : $fallback_event_img;
            }
        ?>
        <article class="event-card<?php echo !empty($event['video_url']) ? ' event-card--has-video' : ''; ?>"
                 <?php if (!empty($event['video_url'])) : ?>
                 data-video="<?php echo esc_url($event['video_url']); ?>"
                 role="button" tabindex="0"
                 <?php endif; ?>>
            <div class="event-card__thumb-wrap">
                <img src="<?php echo esc_url($img_src); ?>"
                     alt="<?php echo esc_attr($event['title']); ?>"
                     class="event-card__thumb">
                <?php if (!empty($event['video_url'])) : ?>
                <span class="event-card__play-btn" aria-hidden="true">
                    <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="22" cy="22" r="22" fill="rgba(0,0,0,0.45)"/>
                        <polygon points="17,13 35,22 17,31" fill="#ffffff"/>
                    </svg>
                </span>
                <?php endif; ?>
            </div>
            <div class="event-card__body">
                <h3 class="event-card__title"><?php echo esc_html($event['title']); ?></h3>
                <div class="event-card__meta">
                    <?php if (!empty($event['date'])) : ?>
                    <div class="event-card__meta-row">
                        <img src="<?php echo esc_url($assets . 'event-icon-calendar.svg'); ?>"
                             alt="" class="event-card__meta-icon" aria-hidden="true">
                        <span><?php echo esc_html($event['date']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($event['location'])) : ?>
                    <div class="event-card__meta-row">
                        <img src="<?php echo esc_url($assets . 'event-icon-location.svg'); ?>"
                             alt="" class="event-card__meta-icon" aria-hidden="true">
                        <span><?php echo esc_html($event['location']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($event['desc'])) : ?>
                <p class="event-card__desc"><?php echo esc_html($event['desc']); ?></p>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

</section>
