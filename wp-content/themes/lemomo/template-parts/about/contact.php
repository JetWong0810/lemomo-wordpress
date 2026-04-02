<?php
$img_dir = get_template_directory_uri() . '/assets/images';
?>

<section class="about-contact">
    <div class="about-contact__bg-pattern" aria-hidden="true"></div>

    <div class="container about-contact__inner">
        <div class="about-contact__header">
            <h2 class="about-contact__title">Butuh Bantuan Lebih Lanjut?</h2>
            <p class="about-contact__desc">Isi data diri Anda untuk mendapatkan informasi terbaru dan penawaran spesial dari Lemomo.</p>
        </div>

        <form class="about-contact__form" action="#" method="post">
            <div class="about-contact__form-inner">
                <img src="<?php echo esc_url($img_dir . '/about-form-bg.svg'); ?>" alt="" class="about-contact__form-bg" aria-hidden="true">

                <div class="about-contact__fields">
                    <div class="about-contact__field">
                        <label for="contact-name">Nama Lengkap*</label>
                        <input type="text" id="contact-name" name="name" placeholder="Masukan nama lengkap" required>
                    </div>

                    <div class="about-contact__field">
                        <label for="contact-email">Alamat Email*</label>
                        <input type="email" id="contact-email" name="email" placeholder="Masukan email" required>
                    </div>

                    <div class="about-contact__field">
                        <label for="contact-phone">Nomor Telepon*</label>
                        <input type="tel" id="contact-phone" name="phone" placeholder="Masukan nomor telepon" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="about-contact__submit">Kirim</button>
        </form>
    </div>
</section>
