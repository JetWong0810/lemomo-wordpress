<?php
/**
 * Template Name: FAQ
 */
get_header();

$acf_active = function_exists('get_field');
$theme_uri  = get_template_directory_uri();
$img_dir    = $theme_uri . '/assets/images';

$page_title = $acf_active ? (get_field('faq_title') ?: 'Ada Pertanyaan?') : 'Ada Pertanyaan?';
$search_placeholder = $acf_active ? (get_field('faq_search_placeholder') ?: 'Ada Pertanyaan?') : 'Ada Pertanyaan?';

$categories = $acf_active ? get_field('faq_categories') : null;

if (empty($categories)) {
    $categories = [
        [
            'category_name' => 'Tentang Lemomo',
            'faq_items' => [
                [
                    'question' => 'Apa itu Lemomo?',
                    'answer'   => 'Lemomo adalah platform e-commerce berbasis minat di Indonesia yang menghadirkan pengalaman belanja seru melalui konsep blind box, belanja interaktif, dan fitur titip jual 24 jam.',
                ],
                [
                    'question' => 'Bagaimana cara membuat akun Lemomo?',
                    'answer'   => 'Anda dapat membuat akun Lemomo dengan mengunduh aplikasi dari Google Play atau App Store, lalu mendaftar menggunakan nomor telepon atau email Anda.',
                ],
                [
                    'question' => 'Apakah Lemomo aman digunakan?',
                    'answer'   => 'Ya, Lemomo menggunakan sistem keamanan berlapis untuk melindungi data dan transaksi pengguna.',
                ],
                [
                    'question' => 'Kenapa harga di Lemomo bisa lebih murah?',
                    'answer'   => 'Lemomo bekerja sama langsung dengan brand dan supplier untuk memberikan harga terbaik kepada pengguna.',
                ],
                [
                    'question' => 'Apa saya bisa belanja tanpa Blind Box?',
                    'answer'   => 'Tentu! Lemomo menyediakan berbagai pilihan belanja selain blind box.',
                ],
                [
                    'question' => 'Apakah ada reward harian?',
                    'answer'   => 'Ya, Lemomo menyediakan berbagai reward harian yang bisa Anda klaim setiap hari.',
                ],
                [
                    'question' => 'Lupa kata sandi?',
                    'answer'   => 'Anda dapat mereset kata sandi melalui halaman login dengan memilih "Lupa Kata Sandi".',
                ],
                [
                    'question' => 'Apakah kotak kejutan Lemomo itu perjudian?',
                    'answer'   => 'Tidak, blind box Lemomo bukan perjudian. Setiap kotak berisi produk nyata dengan nilai yang setara atau lebih tinggi dari harga yang dibayar.',
                ],
            ],
        ],
        [
            'category_name' => 'Blind Box',
            'faq_items' => [
                [
                    'question' => 'Apa itu Blind Box?',
                    'answer'   => 'Blind Box adalah kotak kejutan berisi produk pilihan yang akan diungkap setelah pembelian.',
                ],
            ],
        ],
        [
            'category_name' => 'Titip Jual',
            'faq_items' => [
                [
                    'question' => 'Bagaimana cara titip jual?',
                    'answer'   => 'Anda dapat menggunakan fitur Titip Jual di aplikasi Lemomo untuk menjual barang Anda.',
                ],
            ],
        ],
        [
            'category_name' => 'Saldo & Top Up',
            'faq_items' => [
                [
                    'question' => 'Bagaimana cara top up saldo?',
                    'answer'   => 'Top up saldo bisa dilakukan melalui berbagai metode pembayaran di aplikasi Lemomo.',
                ],
            ],
        ],
        [
            'category_name' => 'Kode Referral',
            'faq_items' => [
                [
                    'question' => 'Bagaimana cara menggunakan kode referral?',
                    'answer'   => 'Masukkan kode referral saat mendaftar untuk mendapatkan bonus spesial.',
                ],
            ],
        ],
    ];
}
?>

<main class="site-main page-faq">

    <section class="faq-hero" aria-label="FAQ Hero">
        <div class="faq-hero__bg" aria-hidden="true">
            <div class="faq-hero__bg-gradient"></div>
            <div class="faq-hero__bg-texture"></div>
            <div class="faq-hero__bg-overlay"></div>
            <img src="<?php echo esc_url($img_dir . '/faq/faq-hero-deco.png'); ?>"
                 alt="" class="faq-hero__bg-deco">
        </div>
        <div class="container">
            <h1 class="faq-hero__title"><?php echo esc_html($page_title); ?></h1>
        </div>
    </section>

    <section class="faq-content">
        <div class="container">

            <div class="faq-search" role="search">
                <svg class="faq-search__icon" width="30" height="30" viewBox="0 0 30 30" fill="none" aria-hidden="true">
                    <path d="M19.2 19.2L25 25M22 13.5C22 18.19 18.19 22 13.5 22C8.81 22 5 18.19 5 13.5C5 8.81 8.81 5 13.5 5C18.19 5 22 8.81 22 13.5Z" stroke="#5E5E5E" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="faq-search__divider" aria-hidden="true"></span>
                <input type="text"
                       class="faq-search__input"
                       placeholder="<?php echo esc_attr($search_placeholder); ?>"
                       aria-label="Search FAQ">
            </div>

            <div class="faq-body">
                <aside class="faq-categories" aria-label="FAQ Categories">
                    <?php foreach ($categories as $index => $cat) : ?>
                        <button class="faq-categories__item<?php echo $index === 0 ? ' faq-categories__item--active' : ''; ?>"
                                data-category="<?php echo esc_attr($index); ?>"
                                aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                            <?php if ($index === 0) : ?>
                                <span class="faq-categories__accent" aria-hidden="true"></span>
                            <?php endif; ?>
                            <span class="faq-categories__label"><?php echo esc_html($cat['category_name']); ?></span>
                        </button>
                    <?php endforeach; ?>
                </aside>

                <div class="faq-panels">
                    <?php foreach ($categories as $cat_index => $cat) : ?>
                        <div class="faq-panel<?php echo $cat_index === 0 ? ' faq-panel--active' : ''; ?>"
                             data-panel="<?php echo esc_attr($cat_index); ?>">
                            <?php if (!empty($cat['faq_items'])) : ?>
                                <?php foreach ($cat['faq_items'] as $item_index => $item) : ?>
                                    <div class="faq-item<?php echo ($cat_index === 0 && $item_index === 0) ? ' faq-item--open' : ''; ?>">
                                        <button class="faq-item__question" aria-expanded="<?php echo ($cat_index === 0 && $item_index === 0) ? 'true' : 'false'; ?>">
                                            <span class="faq-item__question-text"><?php echo esc_html($item['question']); ?></span>
                                            <span class="faq-item__toggle" aria-hidden="true">
                                                <svg class="faq-item__toggle-icon" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                                    <line class="faq-item__toggle-h" x1="0" y1="7" x2="14" y2="7" stroke="currentColor" stroke-width="2"/>
                                                    <line class="faq-item__toggle-v" x1="7" y1="0" x2="7" y2="14" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </span>
                                        </button>
                                        <div class="faq-item__answer" <?php echo ($cat_index === 0 && $item_index === 0) ? '' : 'hidden'; ?>>
                                            <p><?php echo esc_html($item['answer']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>
