<?php
$title         = get_field('features_title')       ?: 'Belanja Online';
$description   = get_field('features_description') ?: 'Jelajahi berbagai kategori produk original, pengalaman belanja yang aman, nyaman, dan seru tanpa ribet awal sampai checkout';
$feature_list  = get_field('features_list');
$section_image = get_field('features_image');
$img_dir       = get_template_directory_uri() . '/assets/images';

$default_features = [
    ['label' => 'Blind Box Emas Gratis'],
    ['label' => 'Blind Box Berhadiah Premium'],
    ['label' => 'Titip Jual Instan 24 Jam'],
    ['label' => 'Ajak Teman Dapat Bonus'],
];
$items = $feature_list ?: $default_features;
?>

<section class="features">
    <img src="<?php echo esc_url($img_dir . '/howto-glow.svg'); ?>" alt="" class="features__glow" aria-hidden="true">
    <div class="container features__inner">

        <div class="features__media">
            <?php if ($section_image) : ?>
                <img src="<?php echo esc_url($section_image['url']); ?>"
                     alt="<?php echo esc_attr($section_image['alt']); ?>">
            <?php else : ?>
                <img src="<?php echo esc_url($img_dir . '/features-phone-2acf1e.png'); ?>"
                     alt="Belanja Online">
            <?php endif; ?>
        </div>

        <div class="features__text">
            <h2 class="features__title"><?php echo esc_html($title); ?></h2>
            <p class="features__desc"><?php echo esc_html($description); ?></p>
        </div>

        <div class="features__panel">
            <div class="features__panel-header">
                <span class="features__panel-accent"></span>
                <span class="features__panel-label"><?php echo esc_html($title); ?></span>
            </div>
            <ul class="features__list">
                <?php foreach ($items as $item) : ?>
                    <li class="features__item"><?php echo esc_html($item['label']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</section>
