<?php
$title    = get_field('cta_title');
$text     = get_field('cta_text');
$btn_text = get_field('cta_button_text') ?: 'Download App';
$btn_link = get_field('cta_button_link') ?: '#';

if (!$title && !$text) return;
?>

<section class="cta-section">
    <div class="container cta-section__inner">
        <?php if ($title) : ?>
            <h2><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($text) : ?>
            <p><?php echo esc_html($text); ?></p>
        <?php endif; ?>
        <a href="<?php echo esc_url($btn_link); ?>" class="btn btn--primary btn--pill">
            <?php echo esc_html($btn_text); ?>
        </a>
    </div>
</section>
