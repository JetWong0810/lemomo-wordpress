<?php
$title = get_field('how_to_title') ?: 'Cara Menggunakan Lemomo';
$how_to_image = get_field('how_to_image');
$img_dir = get_template_directory_uri() . '/assets/images';

$default_steps = [
    ['step_title' => 'Download',               'step_description' => 'Unduh aplikasi Lemomo melalui App Store atau Google Play'],
    ['step_title' => 'Registrasi',              'step_description' => 'Daftar akun dan verifikasi nomor HP dengan mudah'],
    ['step_title' => 'Pilih & Buka Blind Box',  'step_description' => 'Pilih blind box favoritmu & buka untuk dapatkan hadiah premium'],
    ['step_title' => 'Kirim atau Titip Jual',   'step_description' => 'Hadiah bisa dikirim ke rumah atau dijual kembali dalam 24 Jam'],
    ['step_title' => 'Dapat Untung!',           'step_description' => 'Nikmati bonus hingga keuntungan dari hasil titip jual'],
];
$steps = get_field('how_to_steps') ?: $default_steps;
?>

<section class="how-to">
    <img src="<?php echo esc_url($img_dir . '/howto-bg.svg'); ?>" alt="" class="how-to__bg" aria-hidden="true">

    <div class="container how-to__inner">

        <h2 class="how-to__title"><?php echo esc_html($title); ?></h2>

        <div class="how-to__body">
            <ol class="how-to__steps">
                <?php foreach ($steps as $i => $step) : ?>
                    <li class="how-to__step<?php echo $i === 0 ? ' how-to__step--active' : ''; ?>">
                        <div class="how-to__step-num">
                            <svg width="35" height="30" viewBox="0 0 35 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.05 0H11.93C10.01 0 8.24 1.02 7.28 2.68L1.72 12.32C0.76 13.98 0.76 16.03 1.72 17.69L7.28 27.32C8.24 28.98 10.01 30 11.93 30H23.05C24.97 30 26.74 28.98 27.7 27.32L33.26 17.69C34.22 16.03 34.22 13.98 33.26 12.32L27.7 2.69C26.74 1.03 24.97 0.01 23.05 0.01V0Z"
                                    <?php echo $i === 0 ? 'fill="#9A3A96"' : 'fill="white" stroke="#9A3A96" stroke-width="2" stroke-miterlimit="10"'; ?>/>
                                <text x="17.5" y="19" text-anchor="middle" font-family="Albert Sans, sans-serif" font-weight="700" font-size="14"
                                    fill="<?php echo $i === 0 ? 'white' : '#2D0D30'; ?>"><?php echo $i + 1; ?></text>
                            </svg>
                        </div>
                        <div class="how-to__step-body">
                            <strong class="how-to__step-title"><?php echo esc_html($step['step_title']); ?></strong>
                            <p class="how-to__step-desc"><?php echo esc_html($step['step_description']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>

            <div class="how-to__media">
                <?php if ($how_to_image) : ?>
                    <img src="<?php echo esc_url($how_to_image['url']); ?>"
                         alt="<?php echo esc_attr($how_to_image['alt']); ?>">
                <?php else : ?>
                    <img src="<?php echo esc_url($img_dir . '/howto-phone.png'); ?>" alt="Cara Menggunakan Lemomo">
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>
