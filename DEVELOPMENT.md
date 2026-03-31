# Lemomo 开发计划

## 当前状态

- [x] Docker 本地环境搭建完成
- [x] WordPress 安装完成
- [x] 自定义主题骨架创建完成（`themes/lemomo/`）
- [x] SCSS 编译环境配置完成
- [x] Cursor AI 开发规范配置完成

---

## 阶段一：后台配置 _(预计 1 天)_

### 1.1 插件安装

后台 → 插件 → 安装插件

| 插件 | 用途 | 优先级 |
|------|------|--------|
| Advanced Custom Fields Pro | 内容字段管理（必须 Pro 版） | 必装 |
| WP Migrate | 本地↔线上迁移 | 必装 |
| Yoast SEO | SEO 基础配置 | 必装 |
| UpdraftPlus | 自动备份 | 上线前装 |

> ACF Pro 购买地址：https://www.advancedcustomfields.com/pro/
> 如暂时使用免费版，Repeater 字段和 Options Page 不可用，需先用静态内容占位

### 1.2 创建 ACF 字段组

在 ACF 后台（或导入 JSON）创建以下字段组：

**字段组 1：全局设置（Options Page）**

| Label | 字段名 | 类型 |
|-------|--------|------|
| Header CTA 文字 | `header_cta_text` | Text |
| Header CTA 链接 | `header_cta_link` | URL |
| Footer 版权文字 | `footer_copyright` | Text |
| 视频接口地址 | `video_api_url` | Text |
| 视频接口 Token | `video_api_token` | Text |

位置规则：Options Page → 接口配置

**字段组 2：首页内容**

| Label | 字段名 | 类型 | 说明 |
|-------|--------|------|------|
| Hero 标题 | `hero_title` | Text | 建议不超过 15 字 |
| Hero 副标题 | `hero_subtitle` | Text | |
| Hero 图片 | `hero_image` | Image | 建议尺寸 600×800px |
| Hero CTA 文字 | `hero_cta_text` | Text | |
| Hero CTA 链接 | `hero_cta_link` | URL | |
| 功能区标题 | `features_title` | Text | |
| 功能区描述 | `features_description` | Textarea | |
| 功能区图片 | `features_image` | Image | |
| 功能列表 | `features_list` | Repeater | |
| └─ 列表项文字 | `label` | Text | |
| CTA 标题 | `cta_title` | Text | |
| CTA 描述 | `cta_text` | Textarea | |
| CTA 按钮文字 | `cta_button_text` | Text | |
| CTA 按钮链接 | `cta_button_link` | URL | |

位置规则：页面模板 → 等于 → 首页（front-page）

**字段组 3：Explore 页内容**

| Label | 字段名 | 类型 |
|-------|--------|------|
| 页面标题 | `explore_title` | Text |
| 页面副标题 | `explore_subtitle` | Textarea |
| 主视频封面图 | `explore_main_thumbnail` | Image |
| 主视频播放地址 | `explore_main_video_url` | URL |

位置规则：页面模板 → 等于 → Explore

> 完成后：自定义字段 → 工具 → 导出字段组 → 保存 JSON 到 `wp-content/themes/lemomo/acf-json/`（自动同步，多人协作时字段不会丢失）

### 1.3 创建页面并配置

后台 → 页面 → 新建，创建以下页面：

| 页面名 | 模板 | 固定链接 |
|--------|------|---------|
| Home | 默认 | `/` |
| Explore Lemomo | Explore | `/explore` |
| About Us | 默认 | `/about` |
| Event | 默认 | `/event` |
| Blog | 默认 | `/blog` |
| FAQ | 默认 | `/faq` |

后台 → 设置 → 阅读 → 将首页设置为静态页面 → 选择 Home

### 1.4 配置导航菜单

后台 → 外观 → 菜单 → 新建菜单"Primary"
按设计稿顺序添加：Home / Explore Lemomo / About Us / Event / Blog / FAQ
菜单位置勾选：Primary Menu

---

## 阶段二：设计稿还原 _(预计 3-5 天)_

> 开发前确保 `npm run watch:scss` 在主题目录下运行

### 2.1 全局样式

- [ ] 引入 Google Fonts（Inter / Noto Sans）
- [ ] 确认品牌色变量与设计稿一致
- [ ] Header 样式还原（Logo + 导航 + CTA 按钮）
- [ ] Footer 样式还原
- [ ] 按钮样式（Primary / Outline / Pill）

### 2.2 首页

- [ ] Hero 区块：渐变背景、手机图叠层、标题、CTA 按钮
- [ ] Features 区块：图文并排、功能列表按钮组
- [ ] CTA 区块：渐变背景、标题、按钮
- [ ] 连接 ACF 字段（替换硬编码内容）
- [ ] 移动端适配

### 2.3 Explore 页

- [ ] 页面标题区块
- [ ] 主视频播放器（封面图 + PLAY 按钮 + 点击播放）
- [ ] Episode 网格列表（接口数据渲染）
- [ ] 视频点击切换逻辑（JS）
- [ ] 移动端适配

### 2.4 About Us 页

- [ ] 创建 `page-about.php`
- [ ] 创建 ACF 字段组
- [ ] 样式还原
- [ ] 移动端适配

### 2.5 Event 页

- [ ] 创建 `page-event.php`
- [ ] 确认是静态内容还是接口数据
- [ ] 创建 ACF 字段组
- [ ] 样式还原
- [ ] 移动端适配

### 2.6 FAQ 页

- [ ] 创建 `page-faq.php`
- [ ] ACF Repeater 字段（问题/答案对）
- [ ] 折叠展开交互（JS）
- [ ] 样式还原
- [ ] 移动端适配

### 2.7 Blog

- [ ] 创建 `archive.php`（列表页）
- [ ] 创建 `single.php`（详情页）
- [ ] 样式还原
- [ ] 移动端适配

---

## 阶段三：接口集成 _(预计 1-2 天)_

> 前提：拿到接口文档

- [ ] 更新 `inc/api.php` 中的数据字段映射
- [ ] 更新 `page-explore.php` 中 `$ep['字段名']` 对应实际返回结构
- [ ] 同步更新 `.cursor/skills/lemomo-project/SKILL.md` 中的接口数据结构
- [ ] 后台填入真实接口地址和 Token
- [ ] 验证 Episode 列表正常渲染
- [ ] 验证视频播放功能
- [ ] 测试缓存刷新按钮是否生效

---

## 阶段四：细节完善 _(预计 1 天)_

### SEO & 基础配置

- [ ] 上传 Logo（后台 → 外观 → 自定义 → 站点标识）
- [ ] 上传 Favicon
- [ ] Yoast SEO：每个页面填写 Meta Title / Description
- [ ] 图片 Alt 文字检查

### 功能完善

- [ ] 创建 `404.php` 页面
- [ ] 验证所有页面固定链接正确（设置 → 固定链接）
- [ ] 验证移动端各页面显示正常
- [ ] 跨浏览器测试（Chrome / Safari / Firefox）
- [ ] 页面加载速度测试（Google PageSpeed Insights）

### ACF 字段体验优化

- [ ] 所有字段 Label 使用中文
- [ ] 高风险字段添加 Instructions 说明
- [ ] 导出全部字段组 JSON 备份到 `acf-json/` 目录

---

## 阶段五：上线交付 _(预计 半天)_

### 5.1 客户准备

- [ ] 客户购买主机（推荐 SiteGround / Hostinger / Niagahoster）
- [ ] 主机 cPanel → Softaculous → 一键安装空白 WordPress

### 5.2 迁移

```
本地后台 → WP Migrate → 导出 → 下载 .wpress 文件
线上后台 → 安装 WP Migrate → 导入 → 上传 .wpress 文件
等待迁移完成（约 10-30 分钟）
```

### 5.3 上线后检查

- [ ] 域名解析到主机 IP
- [ ] SSL 证书配置（主机 cPanel → Let's Encrypt 免费证书）
- [ ] 更新 WordPress 地址（后台 → 设置 → 常规）
- [ ] 测试外部接口连通性
- [ ] 测试视频播放
- [ ] 测试表单（如有）
- [ ] UpdraftPlus 配置自动备份（每周一次）

### 5.4 客户交付

- [ ] 录制后台操作演示视频（5 分钟，覆盖：改文字、换图、刷新视频）
- [ ] 提供管理员账号密码
- [ ] 提供主机控制台账号密码

---

## 新增页面标准流程

每次新增一个页面，按此流程操作：

```
1. 创建 page-{slug}.php 模板文件
2. 在 template-parts/{page}/ 下创建区块文件
3. 在 ACF 后台创建字段组，绑定到该模板
4. 将字段名补充到 .cursor/skills/lemomo-project/SKILL.md 字段表
5. 在 src/scss/main.scss 追加该页面样式（Responsive 断点之前）
6. npm run build 编译
7. 后台新建页面，选择对应模板，填入内容
```

---

## 开发注意事项

- 修改样式前确认 `npm run watch:scss` 正在运行
- 新增 ACF 字段后及时导出 JSON 到 `acf-json/` 目录
- 接口字段名确认后同步更新 `SKILL.md` 字段表
- 所有动态输出必须使用 `esc_html()` / `esc_url()` 等转义函数
- 图片字段输出格式：`$image['url']` 和 `$image['alt']`
