<?php
$img_dir = get_template_directory_uri() . '/assets/images';

$guarantees = [
    'about-guarantee-1.svg',
    'about-guarantee-2.svg',
    'about-guarantee-3.svg',
    'about-guarantee-4.svg',
    'about-guarantee-5.svg',
    'about-guarantee-6.svg',
    'about-guarantee-7.svg',
    'about-guarantee-8.svg',
];
?>

<section class="about-guarantee">
    <div class="container about-guarantee__inner">
        <div class="about-guarantee__header">
            <h2 class="about-guarantee__title">Keamanan &amp; Transparansi di Setiap Transaksi</h2>
            <p class="about-guarantee__subtitle">Kami memastikan setiap langkah dari pembayaran, pembelian hingga pengiriman berjalan aman dan transparan!</p>
        </div>

        <div class="about-guarantee__grid">
            <?php foreach ($guarantees as $svg) : ?>
            <img src="<?php echo esc_url($img_dir . '/' . $svg); ?>" alt="" class="about-guarantee__item">
            <?php endforeach; ?>
        </div>
    </div>
</section>
