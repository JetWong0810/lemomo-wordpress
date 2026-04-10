# Lemomo WordPress — API 接口对接文档

> **更新时间：** 2026-04-08  
> **接口环境：** 所有接口均为公开接口，无需 Token。  
> **Base URL（默认）：** `http://49.232.128.174:48082`（可在 WordPress 后台 → Theme Options → `api_app_base_url` 覆盖）  
> **请求方式：** 全部为 `GET`，WordPress 服务端通过 `wp_remote_get` 发起，结果缓存于 Transient。

---

## 接口总览

| # | 接口路径 | 用途 | 缓存 Key | 缓存时长 |
|---|---------|------|----------|---------|
| 1 | `GET /app-api/activity/lesson/list/0` | Explore 页面教程视频列表 | `lemomo_app_list_0` | 1 小时 |
| 2 | `GET /app-api/activity/lesson/list/{id}/get` | 单条 Lesson 详情（含视频地址） | `lemomo_app_detail_{id}` | 1 小时 |
| 3 | `GET /app-api/activity/lesson/list/11` | Event 页面线下活动列表 | `lemomo_app_list_11` | 1 小时 |
| 4 | `GET /app-api/promotion/article/page` | FAQ 文章列表 | `lemomo_faq_articles` | 1 小时 |
| 5 | `GET /app-api/promotion/article-category/list-all-simple` | FAQ 文章分类名称 | `lemomo_faq_categories` | 12 小时 |

---

## 页面维度

### 1. Explore 页面（`page-explore.php`）

**功能：** 展示教程视频列表（支持分页），每个视频卡片可点击播放。

#### 接口 1 — 视频列表

```
GET /app-api/activity/lesson/list/0
```

**原始响应格式：**
```json
{
  "code": 200,
  "data": [
    {
      "id": 12,
      "type": 0,
      "title": "Tutorial: Download dan Daftar Akun Baru",
      "picUrl": "https://example.com/thumb.jpg"
    }
  ],
  "msg": "success"
}
```

**PHP 处理后（`lemomo_get_lessons_list(0)`）：**  
- 取 `data` 数组  
- `picUrl` 经 `lemomo_safe_pic_url()` 过滤（过滤 `me-south-1.amazonaws.com` / `127.0.0.1` / `localhost`，不可访问时返回空字符串）  
- 整体缓存到 `lemomo_app_list_0`

**最终传入模板的字段（`lemomo_get_video_episodes()`）：**
```php
[
  'id'             => 12,
  'title'          => 'Tutorial: ...',
  'thumbnail'      => 'https://...jpg',   // picUrl（过滤后）
  'video_url'      => 'https://...mp4',   // 来自详情接口 url 字段
  'description'    => '纯文本简介',        // 来自详情接口 message 字段，strip_tags
  'episode_number' => 1,                  // 分页内序号
  'view_count'     => '',                 // 当前接口不返回，留空
  'create_time'    => 1710000000,         // 来自详情接口 createTime
]
```

> 分页逻辑：前端通过 `?ep_page=N` 传递页码，PHP 在完整列表上做 `array_slice`，每页 12 条。

---

#### 接口 2 — 视频详情（逐条调用）

```
GET /app-api/activity/lesson/list/{id}/get
```

**原始响应格式：**
```json
{
  "code": 200,
  "data": {
    "id": 12,
    "title": "Tutorial: Download dan Daftar Akun Baru",
    "picUrl": "https://example.com/thumb.jpg",
    "message": "<p>Deskripsi video...</p>",
    "url": "https://example.com/video.mp4",
    "createTime": 1710000000
  },
  "msg": "success"
}
```

**字段说明：**

| 字段 | 类型 | 用途 |
|------|------|------|
| `id` | int | 唯一标识 |
| `title` | string | 视频标题 |
| `picUrl` | string | 封面图 URL |
| `message` | string | 简介（含 HTML） |
| `url` | string | **视频播放地址**，赋给卡片 `data-video` 属性 |
| `createTime` | int | 创建时间戳（毫秒） |

**缓存：** 每条单独缓存到 `lemomo_app_detail_{id}`，1 小时。

---

### 2. Event 页面（`template-parts/event/grid.php`）

**功能：** 展示线下活动卡片，有视频的活动可点击播放。

#### 接口 3 — 线下活动列表

```
GET /app-api/activity/lesson/list/11
```

**原始响应格式：**（同接口 1，`type` 字段值为 `11`）
```json
{
  "code": 200,
  "data": [
    {
      "id": 35,
      "type": 11,
      "title": "CFD Bareng Lemomo",
      "picUrl": "https://example.com/event-thumb.jpg"
    }
  ],
  "msg": "success"
}
```

> **注意：** 列表接口不返回视频地址，需对每条 `id` 追加调用**接口 2**（`/list/{id}/get`）获取视频 URL。

**最终传入模板的字段：**
```php
[
  'id'        => 35,
  'title'     => 'CFD Bareng Lemomo',
  'date'      => '',                        // 列表接口不返回，留空
  'location'  => 'Offline',
  'desc'      => '纯文本简介',               // 来自详情接口 message，strip_tags
  'image'     => 'https://...jpg',          // picUrl（过滤后）
  'video_url' => 'https://...mp4',          // 来自详情接口 url，若空则不渲染播放按钮
  'is_url'    => true,
  'type'      => 'offline',
]
```

**Fallback 优先级：** API 数据 → ACF `event_list` 字段 → 静态兜底数据

---

### 3. FAQ 页面（`page-faq.php`）

**功能：** 展示按分类分组的常见问题，支持分类切换和搜索。

#### 接口 4 — FAQ 文章列表

```
GET /app-api/promotion/article/page?status=0&pageNo=1&pageSize=200
```

**原始响应格式：**
```json
{
  "code": 200,
  "data": {
    "list": [
      {
        "id": 101,
        "title": "Apa itu Lemomo?",
        "content": "<p>Lemomo adalah...</p>",
        "categoryId": 8,
        "sort": 1
      }
    ],
    "total": 42
  },
  "msg": "success"
}
```

**字段说明：**

| 字段 | 类型 | 用途 |
|------|------|------|
| `id` | int | 文章 ID |
| `title` | string | 问题标题 |
| `content` | string | 答案（含 HTML） |
| `categoryId` | int | 所属分类 ID |
| `sort` | int | 排序权重（升序） |

**PHP 处理（`lemomo_get_faq_articles()`）：**  
- 按 `sort` 升序排序，`sort` 相同时按 `id` 升序  
- 按 `categoryId` 分组，分类名通过接口 5 映射  
- `content` 同时保留两个版本：`answer_html`（`wp_kses_post` 净化）和 `answer`（`strip_tags`）  
- 缓存到 `lemomo_faq_articles`，1 小时

**最终传入模板的数据结构：**
```php
[
  [
    'category_id'   => 8,
    'category_name' => 'Akun',
    'faq_items'     => [
      [
        'question'    => 'Apa itu Lemomo?',
        'answer'      => '纯文本答案',           // strip_tags
        'answer_html' => '<p>富文本答案</p>',    // wp_kses_post
      ],
      // ...
    ],
  ],
  // 更多分类...
]
```

**Fallback 优先级：** API 数据 → ACF `faq_categories` 字段 → 静态兜底数据

---

#### 接口 5 — FAQ 分类名称

```
GET /app-api/promotion/article-category/list-all-simple
```

**原始响应格式：**
```json
{
  "code": 200,
  "data": [
    { "id": 6, "name": "Transaksi" },
    { "id": 8, "name": "Akun" }
  ],
  "msg": "success"
}
```

**PHP 处理（`lemomo_get_faq_categories()`）：**  
- 优先调用接口，失败（空返回）时使用内置 fallback 映射  
- 返回 `[id => name]` 的关联数组，供 `lemomo_get_faq_articles()` 映射分类名

**内置 Fallback 映射：**
```php
[
  6  => 'Transaksi',
  7  => 'Pengembalian',
  8  => 'Akun',
  13 => 'Lainnya',
  14 => 'Umum',
  15 => 'Umum',
]
```

**缓存：** `lemomo_faq_categories`，12 小时。

---

## 图片过滤规则

所有接口返回的 `picUrl` 在缓存前经 `lemomo_safe_pic_url()` 过滤：

| 条件 | 处理 |
|------|------|
| URL 包含 `.me-south-1.amazonaws.com` | 返回空字符串（中东区 S3 桶，外部无法访问） |
| URL 包含 `127.0.0.1` 或 `localhost` | 返回空字符串（服务器内网地址） |
| 其余 | 原样返回 |

图片为空时，模板使用本地占位图兜底（`/assets/images/explore-thumb.png` 或 `event-card1-56586a.png`）。

---

## 缓存管理

所有缓存使用 WordPress Transient API 存储于数据库 `wp_options` 表。

| Transient Key 模式 | 说明 |
|-------------------|------|
| `lemomo_app_list_{type}` | 指定 type 的 lesson 列表 |
| `lemomo_app_detail_{id}` | 指定 id 的 lesson 详情 |
| `lemomo_faq_articles` | FAQ 文章（分组后） |
| `lemomo_faq_categories` | FAQ 分类名称映射 |

**手动清除：** WordPress 后台或通过调用 `lemomo_clear_api_cache()`（AJAX action：`lemomo_clear_cache`）。

---

## Lesson Type 常量

| 常量 | 值 | 说明 |
|------|----|------|
| `LEMOMO_LESSON_TYPE_TUTORIAL` | `0` | 教程视频（Explore 页面） |
| `LEMOMO_LESSON_TYPE_NEWS` | `1` | 官网新闻（暂未使用） |
| `LEMOMO_LESSON_TYPE_OFFLINE` | `11` | 线下活动（Event 页面） |
