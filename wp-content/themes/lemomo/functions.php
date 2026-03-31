<?php
defined('ABSPATH') || exit;

// ─── ACF Fallback ────────────────────────────────────────────────────────────
if (!function_exists('get_field')) {
    function get_field($selector, $post_id = false) { return null; }
}
if (!function_exists('the_field')) {
    function the_field($selector, $post_id = false) {}
}
if (!function_exists('have_rows')) {
    function have_rows($selector, $post_id = false) { return false; }
}

// ─── Theme Setup ────────────────────────────────────────────────────────────
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);
    add_theme_support('custom-logo', [
        'height'      => 72,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'lemomo'),
    ]);
});

// ─── Enqueue Assets ─────────────────────────────────────────────────────────
add_action('wp_enqueue_scripts', function () {
    $theme_ver = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'lemomo-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;500;600;700&family=Inter:wght@400;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'lemomo-style',
        get_template_directory_uri() . '/assets/css/main.css',
        ['lemomo-fonts'],
        $theme_ver
    );

    wp_enqueue_script(
        'lemomo-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        $theme_ver,
        true
    );
});

// ─── ACF JSON 同步目录 ───────────────────────────────────────────────────────
add_filter('acf/settings/save_json', function () {
    return get_template_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
});

// ─── ACF Options Page ────────────────────────────────────────────────────────
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => '全局设置',
        'menu_title' => '全局设置',
        'menu_slug'  => 'global-settings',
        'capability' => 'edit_posts',
        'icon_url'   => 'dashicons-admin-settings',
    ]);

    acf_add_options_sub_page([
        'page_title'  => '接口配置',
        'menu_title'  => '接口配置',
        'menu_slug'   => 'api-settings',
        'parent_slug' => 'global-settings',
    ]);
}

// ─── External API Helper ─────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/api.php';

// ─── Clear Video Cache (AJAX) ────────────────────────────────────────────────
add_action('wp_ajax_refresh_video_cache', function () {
    check_ajax_referer('refresh_video_cache', 'nonce');
    delete_transient('lemomo_video_episodes');
    wp_send_json_success('视频缓存已刷新');
});
