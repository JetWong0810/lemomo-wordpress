<?php
$img_dir = get_template_directory_uri() . '/assets/images';

$items = [
    [
        'label' => 'Transparan',
        'desc'  => 'Sistem yang adil, terbuka, dan memberikan rasa aman bagi seluruh pengguna.',
        'image' => 'about-filosofi-transparan-56586a.png',
    ],
    [
        'label' => 'Keuntungan',
        'desc'  => 'Membuka peluang bagi pengguna untuk mendapatkan nilai dan keuntungan.',
        'image' => 'about-filosofi-keuntungan-56586a.png',
    ],
    [
        'label' => 'Kejutan',
        'desc'  => 'Pengalaman belanja yang seru dan penuh kejutan di setiap aktivitas Lemomo.',
        'image' => 'about-filosofi-kejutan-56586a.png',
    ],
];
?>

<section class="about-filosofi">
    <div class="container about-filosofi__inner">
        <h2 class="about-filosofi__title">Filosofi Lemomo</h2>

        <div class="about-filosofi__grid">
            <?php foreach ($items as $item) : ?>
            <div class="about-filosofi__card">
                <div class="about-filosofi__card-visual">
                    <img src="<?php echo esc_url($img_dir . '/about-filosofi-card-bg.svg'); ?>" alt="" class="about-filosofi__card-bg" aria-hidden="true">
                    <img src="<?php echo esc_url($img_dir . '/' . $item['image']); ?>" alt="<?php echo esc_attr($item['label']); ?>" class="about-filosofi__card-photo">
                    <div class="about-filosofi__card-label">
                        <span class="about-filosofi__label-text"><?php echo esc_html($item['label']); ?></span>
                    </div>
                    <p class="about-filosofi__card-desc"><?php echo esc_html($item['desc']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
