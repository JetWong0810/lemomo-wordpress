<?php
/**
 * Event Grid Section
 * 事件卡片网格 + 底部装饰波浪
 */
$assets = get_template_directory_uri() . '/assets/images/';

$events = [
    [
        'title' => 'Foto Ramadan Challenge',
        'date' => '20 Februari – 30 Maret 2026',
        'location' => 'Online',
        'desc' => 'Campaign media sosial yang mengajak peserta berbagi momen Ramadan bersama keluarga atau aksi kebaikan dan berhadiah menarik seperti liburan ke Pulau Seribu, emas, dan merchandise eksklusif.',
        'image' => 'event-card1-56586a.png',
    ],
    [
        'title' => 'Foto Ramadan Challenge',
        'date' => '20 Februari – 30 Maret 2026',
        'location' => 'Online',
        'desc' => 'Campaign media sosial yang mengajak peserta berbagi momen Ramadan bersama keluarga atau aksi kebaikan dan berhadiah menarik seperti liburan ke Pulau Seribu, emas, dan merchandise eksklusif.',
        'image' => 'event-card4-56586a.png',
    ],
    [
        'title' => 'Foto Ramadan Challenge',
        'date' => '20 Februari – 30 Maret 2026',
        'location' => 'Online',
        'desc' => 'Campaign media sosial yang mengajak peserta berbagi momen Ramadan bersama keluarga atau aksi kebaikan dan berhadiah menarik seperti liburan ke Pulau Seribu, emas, dan merchandise eksklusif.',
        'image' => 'event-card7-56586a.png',
    ],
    [
        'title' => 'Foto Ramadan Challenge',
        'date' => '20 Februari – 30 Maret 2026',
        'location' => 'Online',
        'desc' => 'Campaign media sosial yang mengajak peserta berbagi momen Ramadan bersama keluarga atau aksi kebaikan dan berhadiah menarik seperti liburan ke Pulau Seribu, emas, dan merchandise eksklusif.',
        'image' => 'event-card2-56586a.png',
    ],
    [
        'title' => 'CFD Bareng Lemomo',
        'date' => '28 Desember 2025',
        'location' => 'Jakarta Pusat',
        'desc' => 'Aktivasi di area Car Free Day dengan mengajak audiens mencoba Blind Box Lemomo dan membagikan hadiah emas secara langsung.',
        'image' => 'event-card5.png',
    ],
    [
        'title' => 'Perempuan Berlari',
        'date' => '30 November 2025',
        'location' => 'Senayan Park Jakarta.',
        'desc' => 'Event lari khusus perempuan yang menghadirkan berbagai aktivitas seru, pembagian merchandise eksklusif, serta hadiah emas dari Lemomo untuk peserta dan figur publik.',
        'image' => 'event-card8-56586a.png',
    ],
    [
        'title' => 'Run For Humanity',
        'date' => '23 November 2026',
        'location' => 'Lapangan Sunburst - BSD City',
        'desc' => '',
        'image' => 'event-card3-56586a.png',
    ],
    [
        'title' => 'Satu Padel Tournament',
        'date' => '',
        'location' => 'TRT Padel and Tennis Court Bintaro',
        'desc' => 'Kolaborasi pada ajang padel bergengsi yang diikuti oleh selebriti dan influencer. Event ini menjadi momen penuh energi positif dalam menyambut peluncuran Lemomo.',
        'image' => 'event-card6-56586a.png',
    ],
];

$acf_events = get_field('event_list');
if ($acf_events) {
    $events = [];
    foreach ($acf_events as $ev) {
        $img = $ev['event_image'] ?? null;
        $events[] = [
            'title'    => $ev['event_title'] ?? '',
            'date'     => $ev['event_date'] ?? '',
            'location' => $ev['event_location'] ?? '',
            'desc'     => $ev['event_description'] ?? '',
            'image'    => $img ? $img['url'] : '',
            'is_acf'   => true,
        ];
    }
}
?>

<section class="event-grid">
    <img src="<?php echo esc_url($assets . 'event-bg-wave.svg'); ?>"
         alt="" class="event-grid__bg-wave" aria-hidden="true">

    <div class="event-grid__list">
        <?php foreach ($events as $event) :
            $img_src = !empty($event['is_acf'])
                ? $event['image']
                : $assets . $event['image'];
        ?>
        <article class="event-card">
            <div class="event-card__thumb">
                <img src="<?php echo esc_url($img_src); ?>"
                     alt="<?php echo esc_attr($event['title']); ?>">
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
