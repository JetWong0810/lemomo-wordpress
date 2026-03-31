<?php
$title = get_field('testimonials_title') ?: 'Kata Mereka Tentang Lemomo';
$img_dir = get_template_directory_uri() . '/assets/images';

$default_testimonials = [
    [
        'testimonial_text'   => 'Awalnya coba karena blind box, eh malah bisa dapat cuan. Ngga cocok? Simple tinggal pakai fitur titip jual, saldo bisa balik lagi deh!',
        'testimonial_name'   => 'Ningina',
        'testimonial_rating' => 5,
    ],
    [
        'testimonial_text'   => 'Proses daftar & beli blind box di Lemomo super gampang, semua transaksinya transparan. Jadi mainnya berasa aman!',
        'testimonial_name'   => 'Nouval',
        'testimonial_rating' => 5,
    ],
    [
        'testimonial_text'   => 'Aku paling suka sama fitur emas gratis, setiap hari tukeran dan ajak teman itu gampang bisa langsung bonus.',
        'testimonial_name'   => 'Kokam',
        'testimonial_rating' => 5,
    ],
    [
        'testimonial_text'   => 'Fitur titip jual 24 jam sangat membantu, bisa langsung jual barang yang tidak diinginkan tanpa repot!',
        'testimonial_name'   => 'Dewi',
        'testimonial_rating' => 5,
    ],
    [
        'testimonial_text'   => 'Seru banget buka blind box di Lemomo, hadiahnya beneran premium. Sudah beberapa kali dapat barang bagus!',
        'testimonial_name'   => 'Rian',
        'testimonial_rating' => 5,
    ],
    [
        'testimonial_text'   => 'Aplikasi paling inovatif yang pernah saya coba. Konsep blind box dengan e-commerce ini unik dan bikin ketagihan.',
        'testimonial_name'   => 'Sari',
        'testimonial_rating' => 5,
    ],
];

$testimonials = get_field('testimonials_list') ?: $default_testimonials;
?>

<section class="testimonials">
    <img src="<?php echo esc_url($img_dir . '/testimonials-bg.svg'); ?>" alt="" class="testimonials__bg" aria-hidden="true">

    <div class="testimonials__inner">

        <div class="testimonials__header">
            <div class="testimonials__quote-mark">
                <svg width="80" height="60" viewBox="0 0 80 60" fill="none">
                    <path d="M0 60V36.67C0 16.11 11.33 4.17 34 0l4 8c-12 4-18.67 10.67-20 20h16v32H0zm46 0V36.67C46 16.11 57.33 4.17 80 0l4 8c-12 4-18.67 10.67-20 20h16v32H46z" fill="currentColor"/>
                </svg>
            </div>
            <h2 class="testimonials__title"><?php echo esc_html($title); ?></h2>
        </div>

        <div class="testimonials__cards-area">
            <div class="testimonials__nav">
                <button class="testimonials__nav-btn testimonials__nav-btn--prev" aria-label="Previous">
                    <img src="<?php echo esc_url($img_dir . '/testimonials-arrow-left.svg'); ?>" alt="" aria-hidden="true">
                </button>
                <button class="testimonials__nav-btn testimonials__nav-btn--next" aria-label="Next">
                    <img src="<?php echo esc_url($img_dir . '/testimonials-arrow-right.svg'); ?>" alt="" aria-hidden="true">
                </button>
            </div>
            <div class="testimonials__track-wrapper">
                <div class="testimonials__track">
                <?php foreach ($testimonials as $item) : ?>
                    <div class="testimonials__slide">
                        <div class="testimonials__card">
                            <div class="testimonials__card-stars">
                                <?php for ($s = 0; $s < 5; $s++) : ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#EBAD1F"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <?php endfor; ?>
                            </div>
                            <p class="testimonials__card-text"><?php echo esc_html($item['testimonial_text']); ?></p>
                        </div>
                        <div class="testimonials__card-author">
                            <div class="testimonials__avatar">
                                <?php echo esc_html(mb_substr($item['testimonial_name'], 0, 1)); ?>
                            </div>
                            <span class="testimonials__card-name"><?php echo esc_html($item['testimonial_name']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</section>
