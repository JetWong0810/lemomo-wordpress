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
    $css_file = get_template_directory() . '/assets/css/main.css';
    $css_ver  = file_exists($css_file) ? filemtime($css_file) : time();

    wp_enqueue_style(
        'lemomo-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'lemomo-style',
        get_template_directory_uri() . '/assets/css/main.css',
        ['lemomo-fonts'],
        $css_ver
    );

    $js_file = get_template_directory() . '/assets/js/main.js';
    $js_ver  = file_exists($js_file) ? filemtime($js_file) : time();

    wp_enqueue_script(
        'lemomo-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        $js_ver,
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

// ─── ACF Field Groups (Code Registration) ───────────────────────────────────
add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'      => 'group_faq_page',
        'title'    => 'FAQ Page Fields',
        'fields'   => [
            [
                'key'   => 'field_faq_title',
                'label' => 'FAQ 标题',
                'name'  => 'faq_title',
                'type'  => 'text',
                'default_value' => 'Ada Pertanyaan?',
            ],
            [
                'key'   => 'field_faq_search_placeholder',
                'label' => '搜索占位文字',
                'name'  => 'faq_search_placeholder',
                'type'  => 'text',
                'default_value' => 'Ada Pertanyaan?',
            ],
            [
                'key'   => 'field_faq_categories',
                'label' => 'FAQ 分类',
                'name'  => 'faq_categories',
                'type'  => 'repeater',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key'   => 'field_faq_cat_name',
                        'label' => '分类名称',
                        'name'  => 'category_name',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_faq_items',
                        'label' => 'FAQ 项目',
                        'name'  => 'faq_items',
                        'type'  => 'repeater',
                        'layout' => 'row',
                        'sub_fields' => [
                            [
                                'key'   => 'field_faq_question',
                                'label' => '问题',
                                'name'  => 'question',
                                'type'  => 'text',
                            ],
                            [
                                'key'   => 'field_faq_answer',
                                'label' => '回答',
                                'name'  => 'answer',
                                'type'  => 'textarea',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'page-faq.php',
                ],
            ],
        ],
    ]);
});

// ─── CPT: 媒体报道（lemomo_media）────────────────────────────────────────────
add_action('init', function () {
    register_post_type('lemomo_media', [
        'labels' => [
            'name'          => '媒体报道',
            'singular_name' => '媒体报道',
            'add_new_item'  => '添加媒体报道',
            'edit_item'     => '编辑媒体报道',
            'all_items'     => '所有媒体报道',
        ],
        'public'        => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-megaphone',
        'supports'      => ['title', 'thumbnail'],
        'has_archive'   => false,
        'rewrite'       => ['slug' => 'media'],
        'menu_position' => 6,
    ]);
});

// 外链 URL meta 字段（lemomo_media + post 共用）
add_action('add_meta_boxes', function () {
    $render_meta_box = function ($post) {
        $url = get_post_meta($post->ID, '_lemomo_media_url', true);
        wp_nonce_field('lemomo_media_url_nonce', 'lemomo_media_nonce');
        echo '<p style="margin-bottom:6px;color:#666;">点击卡片跳转的目标地址（在新标签页打开），留空则跳转详情页</p>';
        echo '<input type="url" name="lemomo_media_url" value="' . esc_attr($url) . '" style="width:100%;padding:6px 8px;" placeholder="https://...">';
    };

    add_meta_box('lemomo_media_url', '外链 URL', $render_meta_box, 'lemomo_media', 'normal', 'high');
    add_meta_box('lemomo_post_external_url', '外链 URL', $render_meta_box, 'post', 'normal', 'high');
});

$save_external_url = function ($post_id) {
    if (!isset($_POST['lemomo_media_nonce'])) return;
    if (!wp_verify_nonce($_POST['lemomo_media_nonce'], 'lemomo_media_url_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $url = isset($_POST['lemomo_media_url']) ? esc_url_raw($_POST['lemomo_media_url']) : '';
    update_post_meta($post_id, '_lemomo_media_url', $url);
};
add_action('save_post_lemomo_media', $save_external_url);
add_action('save_post_post', $save_external_url);

// ─── Indonesian Date Helper ──────────────────────────────────────────────────
function lemomo_date_id($post = null) {
    $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $ts = get_the_time('U', $post);
    return date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

// ─── External API Helper ─────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/api.php';

// ─── ACF API Settings Fields ─────────────────────────────────────────────────
add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_api_settings',
        'title'  => 'API 接口配置',
        'fields' => [
            [
                'key'           => 'field_api_app_base_url',
                'label'         => 'App API 域名',
                'name'          => 'api_app_base_url',
                'type'          => 'text',
                'default_value' => 'http://49.232.128.174:48082',
                'placeholder'   => 'http://49.232.128.174:48082',
                'instructions'  => '所有接口均为公开接口，无需登录。不含末尾斜线。',
            ],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'api-settings']]],
    ]);
});

// ─── Localize AJAX nonce ──────────────────────────────────────────────────────
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('lemomo-main', 'lemomo_ajax', [
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('lemomo_clear_cache'),
    ]);
}, 20);

// ─── Clear All API Cache (AJAX) ───────────────────────────────────────────────
add_action('wp_ajax_lemomo_clear_cache', function () {
    check_ajax_referer('lemomo_clear_cache', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error('权限不足', 403);
    lemomo_clear_api_cache();
    wp_send_json_success('所有 API 缓存已清除');
});

add_action('wp_ajax_refresh_video_cache', function () {
    check_ajax_referer('refresh_video_cache', 'nonce');
    lemomo_clear_api_cache();
    wp_send_json_success('缓存已刷新');
});
