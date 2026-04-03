<?php get_header(); ?>
<?php
$img_dir = get_template_directory_uri() . '/assets/images';
$categories = get_the_category();
$cat_name   = !empty($categories) ? esc_html($categories[0]->name) : 'Update';
?>

<main class="site-main blog-detail">

    <div class="blog-detail__layout container">

        <!-- Hero Image -->
        <div class="blog-detail__hero">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', ['class' => 'blog-detail__hero-img']); ?>
            <?php else : ?>
                <img class="blog-detail__hero-img" src="<?php echo esc_url($img_dir . '/blog/blog-thumb-1.png'); ?>" alt="<?php the_title_attribute(); ?>">
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside class="blog-detail__sidebar">

            <!-- Table of Contents -->
            <div class="blog-detail__toc">
                <h3 class="blog-detail__toc-title">Pada Artikel ini</h3>
                <nav class="blog-detail__toc-nav" id="blog-toc-nav">
                </nav>
            </div>

            <!-- Share -->
            <div class="blog-detail__share">
                <h3 class="blog-detail__share-title">Bagikan Artikel</h3>
                <div class="blog-detail__share-icons">
                    <a href="https://www.threads.net/intent/post?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener" class="blog-detail__share-icon" aria-label="Share on Threads">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><circle cx="18" cy="18" r="17.5" stroke="#141723"/><path d="M21.47 16.83a5.82 5.82 0 0 0-.22-.11 5.53 5.53 0 0 0-2.3-4.81 4.74 4.74 0 0 0-3.08-.91c-1.66.07-3 .79-3.85 2.09l1.35.93a2.94 2.94 0 0 1 2.44-1.37c.77-.03 1.44.18 1.93.6.39.34.65.8.78 1.37a7.3 7.3 0 0 0-2.09-.2c-2.14.12-3.51 1.37-3.42 3.1a2.68 2.68 0 0 0 1.17 2.07 3.35 3.35 0 0 0 2.44.49 3.22 3.22 0 0 0 2.33-1.2c.56-.76.82-1.73.77-2.91a3.56 3.56 0 0 1 1.35 1.3c.63 1.13.69 3-.42 4.3a4.13 4.13 0 0 1-3.3 1.43c-1.37-.05-2.42-.52-3.13-1.4-.66-.82-1-2-1.04-3.48.04-1.49.38-2.66 1.04-3.48.7-.88 1.76-1.35 3.13-1.4a4.42 4.42 0 0 1 2.3.56l.72-1.41a5.8 5.8 0 0 0-3-.72h-.06c-1.76.07-3.13.7-4.07 1.88-.82 1.04-1.24 2.46-1.28 4.21v.72c.04 1.75.46 3.17 1.28 4.21.94 1.18 2.31 1.81 4.07 1.88h.06c1.72-.07 3.06-.67 3.97-1.78 1.35-1.65 1.36-3.91.56-5.34a4.7 4.7 0 0 0-2.33-1.77Zm-2.1 3.63c-.17 1.3-1 2.11-2.27 2.2a2.02 2.02 0 0 1-1.42-.32c-.35-.25-.54-.6-.56-1.02-.07-1.1.83-1.67 2.01-1.74a6.1 6.1 0 0 1 1.72.15c.2.06.38.12.55.2a5.35 5.35 0 0 1-.03.53Z" fill="#141723"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="blog-detail__share-icon" aria-label="Share on X">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><circle cx="18" cy="18" r="17.5" stroke="#141723"/><path d="M20.52 11h2.62l-5.72 6.54L24 25h-5.27l-4.13-5.4L10 25H7.38l6.12-7L7 11h5.4l3.73 4.93L20.52 11Zm-.92 12.57h1.45L13.48 12.48h-1.56l7.68 11.09Z" fill="#141723"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="blog-detail__share-icon" aria-label="Share on Facebook">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><circle cx="18" cy="18" r="17.5" stroke="#141723"/><path d="M20.16 18.96h2.16l.36-2.88h-2.52V14.4c0-.74 0-1.44 1.44-1.44h1.08V10.3a20.3 20.3 0 0 0-1.89-.12c-1.92 0-3.23 1.18-3.23 3.34v2.56H15.3v2.88h2.16V26.4h2.7v-7.44Z" fill="#141723"/></svg>
                    </a>
                    <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener" class="blog-detail__share-icon" aria-label="Share on WhatsApp">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><circle cx="18" cy="18" r="17.5" stroke="#141723"/><path d="M24.14 11.86A8.49 8.49 0 0 0 18 9.36a8.64 8.64 0 0 0-7.5 13l-1.14 4.14 4.24-1.11A8.63 8.63 0 0 0 18 26.64h0a8.64 8.64 0 0 0 6.14-14.78ZM18 25.17a7.17 7.17 0 0 1-3.66-1l-.26-.16-2.72.71.73-2.65-.17-.27A7.17 7.17 0 1 1 18 25.17Zm3.93-5.37c-.22-.11-1.28-.63-1.48-.7s-.34-.11-.49.11-.56.7-.69.85-.25.16-.47.05a5.93 5.93 0 0 1-1.74-1.07 6.52 6.52 0 0 1-1.2-1.5c-.13-.22 0-.33.09-.44s.22-.25.33-.38a1.49 1.49 0 0 0 .22-.37.4.4 0 0 0 0-.38c-.05-.11-.49-1.17-.67-1.6s-.35-.37-.49-.37h-.42a.8.8 0 0 0-.58.27 2.43 2.43 0 0 0-.76 1.81 4.22 4.22 0 0 0 .89 2.24 9.67 9.67 0 0 0 3.71 3.28 12.54 12.54 0 0 0 1.24.46 3 3 0 0 0 1.37.09 2.24 2.24 0 0 0 1.46-1.03.81.81 0 0 0 .18-.82c-.06-.11-.22-.16-.44-.27Z" fill="#141723"/></svg>
                    </a>
                    <a href="javascript:void(0);" class="blog-detail__share-icon blog-detail__share-icon--copy" aria-label="Copy link" data-url="<?php echo esc_attr(get_permalink()); ?>">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><circle cx="18" cy="18" r="17.5" stroke="#141723"/><path d="M15.5 20.5l5-5m-3.3-1.7l1.1-1.1a2.83 2.83 0 0 1 4 4l-1.1 1.1m-5.4 1.4l-1.1 1.1a2.83 2.83 0 0 0 4 4l1.1-1.1" stroke="#141723" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>

            <!-- Related (Anda Mungkin Suka) -->
            <div class="blog-detail__related">
                <h3 class="blog-detail__related-title">Anda Mungkin Suka</h3>
                <?php
                $related_query = new WP_Query([
                    'posts_per_page' => 3,
                    'post__not_in'   => [get_the_ID()],
                    'orderby'        => 'rand',
                    'category__in'   => wp_list_pluck($categories, 'term_id'),
                ]);
                if ($related_query->have_posts()) :
                    while ($related_query->have_posts()) : $related_query->the_post();
                        $rel_cats = get_the_category();
                        $rel_cat  = !empty($rel_cats) ? esc_html($rel_cats[0]->name) : 'Update';
                ?>
                    <a href="<?php the_permalink(); ?>" class="blog-detail__related-card">
                        <div class="blog-detail__related-card-img">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', ['class' => 'blog-detail__related-card-thumb']); ?>
                            <?php else : ?>
                                <img class="blog-detail__related-card-thumb" src="<?php echo esc_url($img_dir . '/blog/blog-thumb-1.png'); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <span class="blog-detail__related-card-tag"><?php echo $rel_cat; ?></span>
                        </div>
                        <h4 class="blog-detail__related-card-title"><?php the_title(); ?></h4>
                        <time class="blog-detail__related-card-date"><?php echo lemomo_date_id(); ?></time>
                    </a>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>

        </aside>

        <!-- Main Content -->
        <article class="blog-detail__content">
            <div class="blog-detail__meta">
                <span class="blog-detail__meta-tag"><?php echo $cat_name; ?></span>
                <time class="blog-detail__meta-date"><?php echo lemomo_date_id(); ?></time>
            </div>

            <h1 class="blog-detail__title"><?php the_title(); ?></h1>

            <div class="blog-detail__body">
                <?php
                $content = get_the_content();
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]&gt;', $content);

                $text_len    = mb_strlen(strip_tags($content));
                $has_headings = (bool) preg_match('/<h[23][^>]*>/', $content);
                $need_demo   = ($text_len < 200 || !$has_headings);

                if (!$need_demo) {
                    echo $content;
                } else {
                    if ($text_len > 0) { echo $content; }
                ?>

                <p>Ramadan selalu menjadi momen penuh keberkahan. Tahun ini, Lemomo menghadirkan sesuatu yang jauh lebih besar dari sekadar promo biasa. Melalui Challenge Ramadan spesial, Lemomo menyiapkan total hadiah mencapai ratusan juta rupiah yang siap dibagikan kepada para ratusan pengguna beruntung.</p>

                <p>Hadiah yang ditawarkan bukan hadiah biasa. Ada Honda Beat, motor listrik, berbagai barang elektronik premium, emas, hingga paket liburan ke Kepulauan Seribu. Deretan hadiah ini menjadikan Ramadan bersama Lemomo terasa jauh lebih spektakuler, meriah, dan penuh antusiasme. Momentum Ramadan kali ini bukan hanya tentang transaksi, tetapi tentang peluang nyata membawa pulang hadiah besar yang benar-benar bernilai.</p>

                <p class="blog-detail__read-also"><strong>Baca Juga:</strong> <a href="#">Di Dunia Penuh Janji, Lemomo Datang Membawa Bukti: Saatnya Belanja Jadi Aktivitas Bernilai</a></p>

                <h3>Blind Box Ramadan Edisi Terbatas, 100% Pasti Dapat Hadiah</h3>

                <p>Sorotan utama dalam campaign ini adalah hadirnya Blind Box Ramadan Edisi Terbatas yang dirancang khusus menyambut bulan ramadan. Setiap pengguna yang berhasil menyelesaikan misi akumulasi transaksi selama periode campaign, berhak mendapatkan kesempatan membuka blind box eksklusif ini dan yang paling penting, 100% pasti mendapatkan hadiah.</p>

                <p>Setiap blind box yang dibuka dipastikan berisi hadiah. Tidak ada hadiah kosong. Tidak ada istilah zonk. Jenis hadiah memang ditentukan secara acak saat blind box dibuka, namun kepastian mendapatkan hadiah menjadi nilai utama yang membuat program ini terasa begitu menguntungkan.</p>

                <p>Setelah misi terpenuhi, pengguna cukup menghubungi Customer Service melalui aplikasi. Tim akan melakukan proses verifikasi akun dan rekap transaksi kumulatif. Jika lolos, pengguna akan menerima kode penukaran unik yang hanya berlaku satu kali. Begitu kode dimasukkan, hadiah langsung muncul secara otomatis. Satu akun hanya berlaku untuk satu kode, dan seluruh blind box dijamin berisi hadiah tanpa pengecualian.</p>

                <h3>Berbagi Lebih Bermakna &amp; Menang Hadiah Spesial Ramadan</h3>

                <p>Selain challenge blind box, Lemomo juga mengajak pengguna untuk merayakan Ramadan melalui campaign berbagi momen kebaikan. Program ini mengajak setiap peserta untuk mengabadikan kebersamaan bersama keluarga hingga aksi kebaikan sederhana yang dilakukan selama bulan Ramadan.</p>

                <p>Peserta cukup mengikuti akun resmi Lemomo, mengunduh dan melakukan registrasi aplikasi, lalu mengunggah foto momen Ramadan di Instagram atau TikTok. Unggahan dapat berupa kebersamaan keluarga maupun aksi berbagi kepada sesama. Sertakan caption yang positif dan penuh makna, serta gunakan hashtag resmi campaign sebagai bagian dari gerakan berbagi yang lebih luas. Hadiah yang disiapkan juga tidak kalah menarik, mulai dari paket liburan, emas, hingga merchandise eksklusif. Program ini menjadi kombinasi sempurna antara semangat berbagi dan kesempatan memenangkan hadiah bernilai tinggi.</p>

                <p>Ramadan hanya hadir setahun sekali, dan kesempatan memenangkan hadiah besar tidak selalu datang dua kali. Segera ikuti Challenge Ramadan Lemomo dan pastikan kamu menjadi bagian dari momen spesial ini. Untuk informasi lengkap mengenai periode, syarat, serta detail campaign, kunjungi dan pantau media sosial resmi Lemomo. Siapa tahu, Ramadan tahun ini menjadi titik awal keberuntungan besar untukmu!</p>

                <?php } ?>
            </div>
        </article>

    </div>

    <!-- Artikel Lainnya (More Articles) -->
    <section class="blog-detail__more">
        <div class="blog-detail__more-bg">
            <svg class="blog-detail__more-bg-svg" viewBox="0 0 1920 551" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <?php for ($i = 0; $i < 40; $i++) : ?>
                    <ellipse cx="960" cy="<?php echo 551 - $i * 13; ?>" rx="1300" ry="<?php echo 280 - $i * 5; ?>" fill="url(#grad<?php echo $i; ?>)" opacity="0.5"/>
                <?php endfor; ?>
                <defs>
                    <?php for ($i = 0; $i < 40; $i++) : ?>
                        <linearGradient id="grad<?php echo $i; ?>" x1="0" y1="0" x2="1920" y2="0" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#F78F1D"/>
                            <stop offset="0.5" stop-color="#E43594"/>
                            <stop offset="1" stop-color="#8058A5"/>
                        </linearGradient>
                    <?php endfor; ?>
                </defs>
            </svg>
        </div>
        <div class="blog-detail__more-inner container">
            <h2 class="blog-detail__more-title">Artikel Lainnya</h2>
            <div class="blog-detail__more-grid">
                <?php
                $more_query = new WP_Query([
                    'posts_per_page' => 4,
                    'post__not_in'   => [get_the_ID()],
                    'paged'          => max(1, get_query_var('paged')),
                ]);
                if ($more_query->have_posts()) :
                    while ($more_query->have_posts()) : $more_query->the_post();
                        $m_cats = get_the_category();
                        $m_cat  = !empty($m_cats) ? esc_html($m_cats[0]->name) : 'Update';
                ?>
                    <a href="<?php the_permalink(); ?>" class="blog-detail__more-card">
                        <div class="blog-detail__more-card-img">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large'); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url($img_dir . '/blog/blog-thumb-1.png'); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <span class="blog-detail__more-card-tag"><?php echo $m_cat; ?></span>
                        </div>
                        <div class="blog-detail__more-card-body">
                            <div class="blog-detail__more-card-info">
                                <h3 class="blog-detail__more-card-title"><?php the_title(); ?></h3>
                                <p class="blog-detail__more-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 12, '...'); ?></p>
                            </div>
                            <time class="blog-detail__more-card-date"><?php echo lemomo_date_id(); ?></time>
                        </div>
                    </a>
                <?php
                    endwhile;
                endif;
                ?>
            </div>

            <?php if ($more_query->max_num_pages > 1) : ?>
            <nav class="blog-detail__pagination">
                <?php
                $current_page = max(1, get_query_var('paged'));
                $total_pages  = $more_query->max_num_pages;

                if ($current_page > 1) : ?>
                    <a href="<?php echo get_pagenum_link($current_page - 1); ?>" class="blog-detail__pagination-arrow blog-detail__pagination-arrow--prev" aria-label="Previous">
                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none"><path d="M5 1L1 5l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                <?php endif;

                for ($p = 1; $p <= $total_pages; $p++) : ?>
                    <a href="<?php echo get_pagenum_link($p); ?>" class="blog-detail__pagination-num <?php echo ($p === $current_page) ? 'blog-detail__pagination-num--active' : ''; ?>"><?php echo $p; ?></a>
                <?php endfor;

                if ($current_page < $total_pages) : ?>
                    <a href="<?php echo get_pagenum_link($current_page + 1); ?>" class="blog-detail__pagination-arrow blog-detail__pagination-arrow--next" aria-label="Next">
                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none"><path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                <?php endif; ?>
            </nav>
            <?php endif;
            wp_reset_postdata();
            ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
