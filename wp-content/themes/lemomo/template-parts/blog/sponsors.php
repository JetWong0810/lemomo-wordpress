<?php
$sponsors = get_field('blog_sponsors', 'option');
if (empty($sponsors) || !is_array($sponsors)) return;
?>

<section class="blog-sponsors">
    <div class="container">
        <h2 class="blog-sponsors__title">Sponsors</h2>
        <div class="blog-sponsors__grid">
            <?php foreach ($sponsors as $sponsor) :
                $img = $sponsor['image'] ?? null;
                if (!$img) continue;
                $src = is_array($img) ? $img['url'] : $img;
                $alt = is_array($img) ? ($img['alt'] ?: 'Sponsor') : 'Sponsor';
            ?>
            <div class="blog-sponsors__card">
                <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
