<?php
$img_dir = get_template_directory_uri() . '/assets/images';
?>

<section class="about-media">
    <div class="container about-media__inner">
        <div class="about-media__header">
            <h2 class="about-media__title">Liputan Media</h2>
            <p class="about-media__desc">Kumpulan media nasional yang meliputi Lemomo</p>
        </div>
        <a href="#" class="about-media__link">
            Lihat Selengkapnya
            <img src="<?php echo esc_url($img_dir . '/about-arrow-right.svg'); ?>" alt="" aria-hidden="true">
        </a>
    </div>

    <div class="about-media__marquee">
        <div class="about-media__marquee-track">
            <img src="<?php echo esc_url($img_dir . '/about-partner-marquee.svg'); ?>" alt="Media logos" class="about-media__marquee-img">
            <img src="<?php echo esc_url($img_dir . '/about-partner-marquee.svg'); ?>" alt="" class="about-media__marquee-img" aria-hidden="true">
        </div>
    </div>
</section>
