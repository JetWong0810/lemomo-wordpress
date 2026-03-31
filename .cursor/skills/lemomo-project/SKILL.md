---
name: lemomo-project
description: >-
  Lemomo WordPress 项目专属规范。当修改该项目任何文件时触发，包括 PHP 模板、SCSS、JS、ACF 字段配置。
  提供品牌色变量、ACF 字段名映射、模板结构、外部接口约定，避免重复查找和猜测。
---

# Lemomo 项目规范

## 项目信息

- **项目名**：Lemomo App Promotion Website
- **主题路径**：`wp-content/themes/lemomo/`
- **本地访问**：http://localhost:8888
- **数据库管理**：http://localhost:8889（phpMyAdmin）

## Figma 设计稿

- **Figma 文件**：`figma.com/design/C6fdxipEgJgB2JS3aNnQGY`
- **首页 Node ID**：`21:40` (Lemomo Home)
- **本地缓存**：`.figma-cache/homepage-21-40.yaml`（完整节点树）
- **设计 Token 摘要**：`.figma-cache/design-tokens.md`（颜色、字体、布局速查）

> 后续微调首页时，优先读取 `.figma-cache/design-tokens.md`，无需再调 Figma MCP。
> 如需获取其他页面设计数据，使用 `get_figma_data` fileKey=`C6fdxipEgJgB2JS3aNnQGY`

## 品牌色（SCSS 变量 — 从 Figma 提取）

```scss
$color-nav-text:     #9A3A96;   // 导航文字（紫）
$color-nav-active:   #F15C21;   // 导航选中（橙）
$color-dark:         #2D0D30;   // 正文暗色
$color-features:     #B03F8F;   // 区块标题色
$color-step:         #9A3A96;   // 步骤圆圈色
$color-yellow:       #EBAD1F;   // 强调黄
$color-footer-icon:  #F16521;   // Footer 图标橙
$color-white:        #FFFFFF;

$gradient-hero-bg:      linear-gradient(-41deg, #D41BD1 16%, #9B2468 56%, #992264 100%);
$gradient-hero-overlay: linear-gradient(152deg, transparent, rgba(255,94,80,0.54) 44%, #EB7A25 69%, #E8A82A 100%);
$gradient-cta-btn:      linear-gradient(171deg, #AA3F92 0%, #EC7A25 100%);
$gradient-feature-tab:  linear-gradient(90deg, #91268F 0%, #B1408F 41%, #9E1E62 100%);
$gradient-footer-label: linear-gradient(90deg, #F37630 0%, #DF3889 100%);
```

## 字体

- **主字体**：Albert Sans（400/500/600/700）
- **辅助字体**：Inter（400/700）
- Google Fonts URL：`Albert+Sans:wght@400;500;600;700&family=Inter:wght@400;700`

## 模板文件映射

| 页面 | 模板文件 | 说明 |
|------|---------|------|
| 首页 | `front-page.php` | 引用 4 个 template-parts |
| Explore（视频） | `page-explore.php` | 含外部接口视频列表 |
| 通用页面 | `page.php` | 待创建 |
| About Us | `page-about.php` | 待创建 |
| Event | `page-event.php` | 待创建 |
| FAQ | `page-faq.php` | 待创建 |
| Blog 列表 | `archive.php` | 待创建 |
| Blog 详情 | `single.php` | 待创建 |

## Template Parts 结构

```
template-parts/
└── home/
    ├── hero.php          # Hero（渐变背景+纹理+overlay+手机图+标题+CTA）
    ├── features.php      # 功能区（手机图+标题描述+紫色渐变标签面板）
    ├── how-to.php        # 使用步骤（波浪bg+时间线+5步骤+手机图）
    ├── testimonials.php  # 用户评价（波浪bg+引号+标题+3卡片轮播）
    └── cta.php           # CTA 区块（保留备用，首页已不使用）
```

## ACF 字段名速查

> 调用方式：`get_field('字段名')` 或 `get_field('字段名', 'option')`

### 全局 Options Page（`'option'`）

| 字段 Label | 字段名 | 类型 | 说明 |
|-----------|--------|------|------|
| Header CTA 文字 | `header_cta_text` | Text | 导航栏按钮文字 |
| Header CTA 链接 | `header_cta_link` | URL | 导航栏按钮链接 |
| Footer 版权文字 | `footer_copyright` | Text | 底部版权 |
| 视频接口地址 | `video_api_url` | Text | Episode 列表接口 |
| 视频接口 Token | `video_api_token` | Text | Bearer Token |

### 首页字段（绑定 front-page 模板）

| 字段 Label | 字段名 | 类型 |
|-----------|--------|------|
| Hero 标题 | `hero_title` | Text |
| Hero 副标题 | `hero_subtitle` | Text |
| Hero 图片 | `hero_image` | Image |
| Hero CTA 文字 | `hero_cta_text` | Text |
| Hero CTA 链接 | `hero_cta_link` | URL |
| 功能区标题 | `features_title` | Text |
| 功能区描述 | `features_description` | Textarea |
| 功能区图片 | `features_image` | Image |
| 功能列表 | `features_list` | Repeater |
| └─ 列表项文字 | `label` | Text |
| 使用步骤标题 | `how_to_title` | Text |
| 使用步骤图片 | `how_to_image` | Image |
| 使用步骤列表 | `how_to_steps` | Repeater |
| └─ 步骤标题 | `step_title` | Text |
| └─ 步骤描述 | `step_description` | Textarea |
| 评价区标题 | `testimonials_title` | Text |
| 评价列表 | `testimonials_list` | Repeater |
| └─ 评价内容 | `testimonial_text` | Textarea |
| └─ 评价人姓名 | `testimonial_name` | Text |
| └─ 评价人头像 | `testimonial_avatar` | Image |
| CTA 标题 | `cta_title` | Text |
| CTA 描述 | `cta_text` | Textarea |
| CTA 按钮文字 | `cta_button_text` | Text |
| CTA 按钮链接 | `cta_button_link` | URL |

### Explore 页字段（绑定 page-explore 模板）

| 字段 Label | 字段名 | 类型 |
|-----------|--------|------|
| 页面标题 | `explore_title` | Text |
| 页面副标题 | `explore_subtitle` | Textarea |
| 主视频封面图 | `explore_main_thumbnail` | Image |
| 主视频播放地址 | `explore_main_video_url` | URL |

## 外部接口约定

**视频 Episode 列表**

- 函数：`lemomo_get_video_episodes()`（位于 `inc/api.php`）
- Transient key：`lemomo_video_episodes`，缓存 1 小时
- 接口地址：后台 → 全局设置 → 接口配置 → 视频接口地址

返回数组结构（根据实际接口文档更新）：

```php
[
    [
        'title'     => '视频标题',
        'thumbnail' => 'https://...',
        'video_url' => 'https://...',
    ],
]
```

## CSS 类名规范（BEM）

```
.{页面/组件}              → Block
.{页面/组件}__{元素}      → Element
.{页面/组件}--{状态}      → Modifier
```

已有类名：

```
.hero / .hero__bg / .hero__bg-gradient / .hero__bg-texture / .hero__bg-overlay
.hero__inner / .hero__media / .hero__img / .hero__content / .hero__title / .hero__subtitle
.hero__curve / .btn-cta / .btn-download
.features / .features__inner / .features__media / .features__text / .features__title / .features__desc
.features__panel / .features__panel-header / .features__panel-accent / .features__panel-label
.features__list / .features__item
.how-to / .how-to__bg / .how-to__inner / .how-to__title / .how-to__body
.how-to__steps-col / .how-to__timeline / .how-to__steps / .how-to__step
.how-to__step-num / .how-to__step-body / .how-to__step-title / .how-to__step-desc / .how-to__media
.testimonials / .testimonials__bg / .testimonials__inner / .testimonials__header
.testimonials__quote-mark / .testimonials__title / .testimonials__nav / .testimonials__nav-btn
.testimonials__track-wrapper / .testimonials__track / .testimonials__card
.testimonials__card-stars / .testimonials__card-text / .testimonials__card-author / .testimonials__card-name
.testimonials__avatar
.explore-hero / .explore-hero__title / .explore-hero__subtitle
.explore-main-video / .video-player / .video-player__thumb / .video-player__play-btn
.explore-episodes / .explore-episodes__title
.episodes-grid / .episode-card / .episode-card__thumb / .episode-card__title
.site-header / .site-header__inner / .site-logo / .site-logo__img / .site-nav / .nav-menu / .hamburger
.site-footer / .footer-top / .footer-brand / .footer-social-label / .footer-social-icons / .footer-copyright
.footer-divider / .footer-columns / .footer-col / .footer-col__list
.footer-contact-block / .footer-contact-icon / .footer-contact-label / .footer-contact-text
.footer-download-label / .footer-badges / .footer-badge
.container
```

## SCSS 文件结构

```
src/scss/main.scss   # 单文件，按以下顺序组织
├── @use 'sass:color'
├── Variables (Figma tokens)
├── Reset & Base
├── Container
├── Site Header
├── Hero Section
├── Features Section
├── How-to Section
├── Testimonials Section
├── Explore Page
├── Site Footer
└── Responsive
```

> 新增页面样式追加在 Responsive 断点之前

## 素材目录

```
assets/images/
├── logo.svg                    # Lemomo Logo（SVG）
├── hero-phones-399c27.png      # Hero 手机组合图
├── hero-bg-texture-56586a.png  # Hero 背景纹理
├── hero-bg-gradient.svg        # Hero 渐变底层
├── hero-bg-overlay.svg         # Hero 渐变叠加层
├── features-phone-2acf1e.png   # Features 手机图
├── howto-bg.svg                # How-to 波浪背景
├── howto-phone.png             # How-to 手持手机图
├── howto-glow.svg              # How-to 黄色光晕
├── steps-timeline.svg          # 步骤时间线 SVG
├── testimonials-bg.svg         # Testimonials 波浪背景
├── testimonials-header.svg     # 引号标题组合
├── testimonials-arrow-left.svg # 轮播左箭头
├── testimonials-arrow-right.svg# 轮播右箭头
├── social-icons.svg            # Footer 社交图标行
├── footer-logo-divider.svg     # Footer Logo 分割线
├── google-play-badge-56586a.png# Google Play 徽章
├── app-store-badge-56586a.png  # App Store 徽章
├── btn-download.svg            # Download 按钮形状
└── btn-cta.svg                 # CTA 按钮形状
```

## ACF 字段组 JSON 同步

ACF 字段组必须导出 JSON 到主题目录，实现版本管理和多人协作同步：

```
wp-content/themes/lemomo/acf-json/   ← JSON 文件自动读写目录
```

## 新增页面流程

1. 创建 `page-{slug}.php` 模板文件
2. 在 `template-parts/{page}/` 下创建区块文件
3. 在 ACF 后台创建字段组，绑定到该模板
4. 导出字段组 JSON 到 `acf-json/` 目录
5. 将字段名补充到本文件「ACF 字段名速查」表格
6. 在 `src/scss/main.scss` 追加该页面样式（Responsive 断点之前）
7. 运行 `npm run build` 编译
8. 勾选 `DEVELOPMENT.md` 对应任务项

## 参考文档

- 开发计划与进度：`DEVELOPMENT.md`
- Figma 设计 Token：`.figma-cache/design-tokens.md`
- Figma 完整缓存：`.figma-cache/homepage-21-40.yaml`
