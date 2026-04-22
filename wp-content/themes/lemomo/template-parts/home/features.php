<?php
$img_dir = get_template_directory_uri() . '/assets/images';

$tabs = [
    [
        'title'       => 'Belanja Online',
        'description' => 'Jelajahi berbagai kategori produk original, pengalaman belanja yang aman, nyaman, dan seru tanpa ribet dari awal sampai checkout',
        'image'       => $img_dir . '/features-tab-0.webp',
    ],
    [
        'title'       => 'Blind Box Emas Gratis',
        'description' => 'Rasakan serunya unboxing emas gratis setiap hari! buka blind box, kumpulin emasnya, dan bawa pulang emas fisiknya langsung',
        'image'       => $img_dir . '/features-tab-3.webp',
    ],
    [
        'title'       => 'Blind Box Berhadiah Premium',
        'description' => 'Dapatkan hadiah spesial mulai dari iPhone 17 hingga berbagai barang premium lainnya yang bikin setiap buka blind box makin berkesan dan penuh kejutan',
        'image'       => $img_dir . '/features-tab-2.webp',
    ],
    [
        'title'       => 'Titip Jual Instan 24 Jam',
        'description' => 'Kalau dapat barang yang kurang cocok, langsung pakai fitur titip jual. Dijamin laku dalam 24 jam dengan proses cepat dan status bisa dipantau secara real-time',
        'image'       => $img_dir . '/features-tab-4.webp',
    ],
    [
        'title'       => 'Ajak Teman Dapat Bonus',
        'description' => 'Undang temanmu untuk bergabung, lalu kumpulkan bonus dari setiap pendaftaran dan aktivitas mereka. Makin banyak yang ikut, makin untung berlipat',
        'image'       => $img_dir . '/features-tab-1.webp',
    ],
];
?>

<section class="features">
    <img src="<?php echo esc_url($img_dir . '/howto-glow.svg'); ?>" alt="" class="features__glow" aria-hidden="true">
    <div class="container features__inner">

        <div class="features__media">
            <?php foreach ($tabs as $i => $tab) : ?>
            <img src="<?php echo esc_url($tab['image']); ?>"
                 alt="<?php echo esc_attr($tab['title']); ?>"
                 class="features__media-img<?php echo $i === 0 ? ' features__media-img--active' : ''; ?>"
                 data-features-img="<?php echo $i; ?>">
            <?php endforeach; ?>
        </div>

        <div class="features__text">
            <?php foreach ($tabs as $i => $tab) : ?>
            <div class="features__text-content<?php echo $i === 0 ? ' features__text-content--active' : ''; ?>"
                 data-features-text="<?php echo $i; ?>">
                <h2 class="features__title"><?php echo esc_html($tab['title']); ?></h2>
                <p class="features__desc"><?php echo esc_html($tab['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="features__panel">
            <?php foreach ($tabs as $i => $tab) : ?>
            <button class="features__tab<?php echo $i === 0 ? ' features__tab--active' : ''; ?>"
                    data-features-tab="<?php echo $i; ?>" type="button">
                <?php if ($i === 0) : ?>
                <span class="features__tab-accent"></span>
                <?php endif; ?>
                <span class="features__tab-label"><?php echo esc_html($tab['title']); ?></span>
            </button>
            <?php endforeach; ?>
        </div>

    </div>
</section>
