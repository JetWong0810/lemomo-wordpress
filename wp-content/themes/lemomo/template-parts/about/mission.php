<?php
$title = get_field('about_mission_title') ?: "Belanja Lebih Seru\nBerbagi Lebih Bermakna";
$desc  = get_field('about_mission_desc') ?: 'Dengan sistem yang transparan, adil, dan mengikuti regulasi yang berlaku, Lemomo berkomitmen untuk menciptakan ekosistem belanja yang menyenangkan sekaligus memberikan peluang nilai tambah bagi para pengguna.';
$img_dir = get_template_directory_uri() . '/assets/images';
?>

<section class="about-mission">
    <div class="about-mission__bg-pattern" aria-hidden="true"></div>

    <div class="container about-mission__inner">
        <h2 class="about-mission__title"><?php echo nl2br(esc_html($title)); ?></h2>
        <p class="about-mission__desc"><?php echo esc_html($desc); ?></p>
    </div>

    <div class="about-mission__showcase">
        <div class="about-mission__product">
            <img src="<?php echo esc_url($img_dir . '/about-brand-left-shape.svg'); ?>" alt="" class="about-mission__product-shape about-mission__product-shape--purple" aria-hidden="true">
            <img src="<?php echo esc_url($img_dir . '/about-brand-left-shape2.svg'); ?>" alt="" class="about-mission__product-shape about-mission__product-shape--orange" aria-hidden="true">
            <img src="<?php echo esc_url($img_dir . '/about-brand-left.png'); ?>" alt="Lemomo Blind Box" class="about-mission__product-img">
        </div>

        <div class="about-mission__visi-misi">
            <img src="<?php echo esc_url($img_dir . '/about-brand-right-shape.svg'); ?>" alt="" class="about-mission__visi-deco about-mission__visi-deco--tl" aria-hidden="true">
            <img src="<?php echo esc_url($img_dir . '/about-brand-right-shape2.svg'); ?>" alt="" class="about-mission__visi-deco about-mission__visi-deco--br" aria-hidden="true">
            <div class="about-mission__card">
                <h3 class="about-mission__card-title">Visi</h3>
                <hr class="about-mission__card-divider">
                <p class="about-mission__card-text">Menjadi platform e-commerce berbasis minat dan partisipasi yang membuka peluang keuntungan bagi seluruh pengguna.</p>
            </div>
            <div class="about-mission__card">
                <h3 class="about-mission__card-title">Misi</h3>
                <hr class="about-mission__card-divider">
                <p class="about-mission__card-text">Mengubah aktivitas belanja dari sekadar konsumsi menjadi pengalaman yang seru, bernilai, dan memberikan peluang keuntungan bagi pengguna.</p>
            </div>
        </div>
    </div>
</section>
