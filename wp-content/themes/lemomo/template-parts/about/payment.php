<?php
$img_dir = get_template_directory_uri() . '/assets/images';
?>

<section class="about-payment">
    <div class="container about-payment__inner">
        <div class="about-payment__text">
            <h2 class="about-payment__title">Partner Metode Pembayaran Resmi</h2>
            <p class="about-payment__desc">Lemomo bekerja sama dengan penyedia layanan pembayaran terpercaya di Indonesia untuk menghadirkan transaksi yang aman dan nyaman bagi pengguna</p>
        </div>

        <div class="about-payment__visual">
            <div class="about-payment__logos">
                <img src="<?php echo esc_url($img_dir . '/about-payment-logos.svg'); ?>" alt="Payment Partners" class="about-payment__logos-img">
            </div>
            <div class="about-payment__phone">
                <img src="<?php echo esc_url($img_dir . '/about-payment-phone-56586a.png'); ?>" alt="Lemomo Payment" class="about-payment__phone-img">
            </div>
        </div>
    </div>

</section>
