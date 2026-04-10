<?php
defined('ABSPATH') || exit;

/**
 * Lemomo API 辅助函数
 *
 * 所有接口均为公开接口，无需 token。
 * App API Base：http://49.232.128.174:48082
 */

// ─── 常量 ─────────────────────────────────────────────────────────────────────
define('LEMOMO_APP_API_BASE',    'http://49.232.128.174:48082');
define('LEMOMO_LESSON_TYPE_TUTORIAL', 0);   // 新手教程（Explore 视频）
define('LEMOMO_LESSON_TYPE_NEWS',     1);   // 官网新闻
define('LEMOMO_LESSON_TYPE_OFFLINE', 11);   // 线下活动

// ─── 图片 URL 过滤 ────────────────────────────────────────────────────────────

/**
 * 过滤无法访问的 S3 域名（me-south-1 中东桶）。
 */
function lemomo_safe_pic_url(string $url): string {
    if (empty($url)) return '';
    if (strpos($url, '.me-south-1.amazonaws.com') !== false) return '';
    // 过滤 127.0.0.1 内网地址（picUrl 带有服务器内网地址时无法访问）
    if (strpos($url, '127.0.0.1') !== false || strpos($url, 'localhost') !== false) return '';
    return $url;
}

// ─── 通用请求 ─────────────────────────────────────────────────────────────────

/**
 * App API 公开 GET 请求，无需鉴权。
 *
 * @param string $endpoint  相对路径，如 'app-api/activity/lesson/list/0'
 * @param array  $query     URL 查询参数
 * @return array            完整响应体
 */
function lemomo_app_api_request(string $endpoint, array $query = []): array {
    $base = rtrim(get_field('api_app_base_url', 'option') ?: LEMOMO_APP_API_BASE, '/');
    $url  = $base . '/' . ltrim($endpoint, '/');
    if (!empty($query)) {
        $url = add_query_arg(array_map('strval', $query), $url);
    }

    $response = wp_remote_get($url, ['timeout' => 15]);

    if (is_wp_error($response)) {
        error_log('[Lemomo API] ' . $endpoint . ': ' . $response->get_error_message());
        return [];
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    return is_array($data) ? $data : [];
}

// ═══════════════════════════════════════════════════════════════════════════════
// Lesson（视频 / 活动）
// ═══════════════════════════════════════════════════════════════════════════════

/**
 * 获取指定 type 的 lesson 列表（Transient 缓存 1h）。
 * 返回 [{id, type, title, picUrl}, ...]
 */
function lemomo_get_lessons_list(int $type): array {
    $key    = "lemomo_app_list_{$type}";
    $cached = get_transient($key);
    if ($cached !== false) return $cached;

    $resp = lemomo_app_api_request("app-api/activity/lesson/list/{$type}");
    $list = $resp['data'] ?? [];
    if (!is_array($list)) $list = [];

    $list = array_map(function (array $item): array {
        if (isset($item['picUrl'])) {
            $item['picUrl'] = lemomo_safe_pic_url($item['picUrl']);
        }
        return $item;
    }, $list);

    set_transient($key, $list, HOUR_IN_SECONDS);
    return $list;
}

/**
 * 获取单条 lesson 详情（含视频 URL），Transient 缓存 1h。
 * 返回 {id, title, picUrl, message, url}
 */
function lemomo_get_lesson_detail(int $id): array {
    $key    = "lemomo_app_detail_{$id}";
    $cached = get_transient($key);
    if ($cached !== false) return $cached;

    $resp = lemomo_app_api_request("app-api/activity/lesson/list/{$id}/get");
    $item = $resp['data'] ?? [];
    if (!is_array($item) || empty($item)) return [];

    if (isset($item['picUrl'])) {
        $item['picUrl'] = lemomo_safe_pic_url($item['picUrl']);
    }

    set_transient($key, $item, HOUR_IN_SECONDS);
    return $item;
}

/**
 * Explore 教程视频列表（type=0），支持分页。
 * 先拉列表，再逐条拉详情获取视频 URL（各自独立缓存）。
 */
function lemomo_get_video_episodes(int $page_no = 1, int $page_size = 20): array {
    $full_list = lemomo_get_lessons_list(LEMOMO_LESSON_TYPE_TUTORIAL);
    $offset    = ($page_no - 1) * $page_size;
    $paged     = array_slice($full_list, $offset, $page_size);

    $result = [];
    foreach ($paged as $index => $item) {
        $id     = (int)($item['id'] ?? 0);
        $detail = $id ? lemomo_get_lesson_detail($id) : [];

        $result[] = [
            'id'             => $id,
            'title'          => $item['title']  ?? '',
            'thumbnail'      => $item['picUrl'] ?? '',
            'video_url'      => $detail['url']  ?? '',
            'description'    => wp_strip_all_tags($detail['message'] ?? ''),
            'episode_number' => $offset + $index + 1,
            'view_count'     => '',
            'create_time'    => $detail['createTime'] ?? 0,
        ];
    }

    return $result;
}

/**
 * 教程视频总条数（用于分页计算）。
 */
function lemomo_get_video_episodes_total(): int {
    return count(lemomo_get_lessons_list(LEMOMO_LESSON_TYPE_TUTORIAL));
}

/**
 * 线下活动列表（type=11）。
 * 返回 ['list' => [...], 'total' => int]
 */
function lemomo_get_offline_events(): array {
    $list = lemomo_get_lessons_list(LEMOMO_LESSON_TYPE_OFFLINE);
    return ['list' => $list, 'total' => count($list)];
}

// ═══════════════════════════════════════════════════════════════════════════════
// FAQ
// ═══════════════════════════════════════════════════════════════════════════════

/**
 * 已知分类 ID → 名称映射（当公开分类接口不可用时的内置 fallback）。
 * 可在此处按实际情况修改为印尼语名称。
 */
function lemomo_faq_category_fallback(): array {
    return [
        6  => 'Transaksi',
        7  => 'Pengembalian',
        8  => 'Akun',
        13 => 'Lainnya',
        14 => 'Umum',
        15 => 'Umum',
    ];
}

/**
 * 拉取 FAQ 文章分类名称映射，返回 [id => name]（缓存 12h）。
 * 优先调用公开接口，失败时使用内置 fallback。
 */
function lemomo_get_faq_categories(): array {
    $cached = get_transient('lemomo_faq_categories');
    if ($cached !== false) return $cached;

    $resp = lemomo_app_api_request('app-api/promotion/article-category/list-all-simple');
    $list = $resp['data'] ?? [];

    $map = lemomo_faq_category_fallback();

    if (is_array($list) && !empty($list)) {
        foreach ($list as $cat) {
            if (!empty($cat['id'])) {
                $map[(int)$cat['id']] = $cat['name'] ?? '';
            }
        }
    }

    set_transient('lemomo_faq_categories', $map, 12 * HOUR_IN_SECONDS);
    return $map;
}

/**
 * 拉取 FAQ 文章列表，按分类分组（Transient 缓存 1h）。
 *
 * 接口：GET /app-api/promotion/article/page?status=0（无需 token）
 *
 * 返回：[['category_id', 'category_name', 'faq_items' => [['question','answer','answer_html']]], ...]
 */
function lemomo_get_faq_articles(): array {
    $cached = get_transient('lemomo_faq_articles');
    if ($cached !== false) return $cached;

    $resp     = lemomo_app_api_request('app-api/promotion/article/page', ['status' => 0, 'pageNo' => 1, 'pageSize' => 200]);
    $articles = $resp['data']['list'] ?? [];

    if (empty($articles)) return [];

    $cat_map = lemomo_get_faq_categories();

    usort($articles, function ($a, $b) {
        $s = ($a['sort'] ?? 0) <=> ($b['sort'] ?? 0);
        return $s !== 0 ? $s : ($a['id'] ?? 0) <=> ($b['id'] ?? 0);
    });

    $grouped = [];
    foreach ($articles as $art) {
        $cat_id   = (int)($art['categoryId'] ?? 0);
        $cat_name = $cat_map[$cat_id] ?? ('Category ' . $cat_id);

        if (!isset($grouped[$cat_id])) {
            $grouped[$cat_id] = [
                'category_id'   => $cat_id,
                'category_name' => $cat_name,
                'faq_items'     => [],
            ];
        }

        $grouped[$cat_id]['faq_items'][] = [
            'question'    => trim($art['title']   ?? ''),
            'answer'      => wp_strip_all_tags($art['content'] ?? ''),
            'answer_html' => wp_kses_post($art['content'] ?? ''),
        ];
    }

    $result = array_values($grouped);
    set_transient('lemomo_faq_articles', $result, HOUR_IN_SECONDS);
    return $result;
}

// ─── 缓存清理 ─────────────────────────────────────────────────────────────────

/**
 * 清除所有 Lemomo API 相关 Transient 缓存。
 */
function lemomo_clear_api_cache(): void {
    delete_transient('lemomo_faq_categories');
    delete_transient('lemomo_faq_articles');

    global $wpdb;
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_lemomo_app_%'
            OR option_name LIKE '_transient_timeout_lemomo_app_%'"
    );
}
