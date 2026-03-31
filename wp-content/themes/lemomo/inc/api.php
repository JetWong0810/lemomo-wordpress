<?php
defined('ABSPATH') || exit;

/**
 * 获取视频 Episode 列表（带 Transient 缓存）
 */
function lemomo_get_video_episodes(): array {
    $cached = get_transient('lemomo_video_episodes');
    if ($cached !== false) return $cached;

    $api_url   = get_field('video_api_url', 'option');
    $api_token = get_field('video_api_token', 'option');

    if (empty($api_url)) return [];

    $args = ['timeout' => 10];
    if (!empty($api_token)) {
        $args['headers'] = ['Authorization' => 'Bearer ' . $api_token];
    }

    $response = wp_remote_get($api_url, $args);

    if (is_wp_error($response)) {
        error_log('[Lemomo API] ' . $response->get_error_message());
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!is_array($data)) return [];

    set_transient('lemomo_video_episodes', $data, HOUR_IN_SECONDS);
    return $data;
}

/**
 * 通用接口请求（GET）
 */
function lemomo_api_get(string $url, array $headers = []): array {
    $response = wp_remote_get($url, [
        'timeout' => 10,
        'headers' => $headers,
    ]);

    if (is_wp_error($response)) return [];

    $data = json_decode(wp_remote_retrieve_body($response), true);
    return is_array($data) ? $data : [];
}
