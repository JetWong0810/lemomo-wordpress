# Lemomo WordPress 本地开发环境

## 环境要求

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) （已安装）
- [Node.js](https://nodejs.org/) 16+（用于 SCSS 编译）

---

## 首次安装

### 1. 克隆 / 下载项目

```bash
cd /Users/jetwong/Projects/personal
# 项目已在 lemomo-wordpress/ 目录下
```

### 2. 启动 Docker 容器

确保 Docker Desktop 已运行，然后在项目根目录执行：

```bash
cd lemomo-wordpress
docker compose up -d
```

首次执行会拉取镜像（WordPress + MySQL + phpMyAdmin），约需 3-10 分钟，取决于网速。

启动成功后输出：

```
Container lemomo_db   Started
Container lemomo_wp   Started
Container lemomo_pma  Started
```

### 3. 完成 WordPress 安装向导

浏览器打开 http://localhost:8888，按提示填写：

- 站点标题：Lemomo
- 用户名：自定义
- 密码：自定义（建议记录下来）
- 电子邮件：自定义

点击「安装 WordPress」，完成后登录后台。

### 4. 启用主题

后台 → 外观 → 主题 → 找到 **Lemomo** → 启用

### 5. 安装 ACF 插件

后台 → 插件 → 安装插件 → 搜索 `Advanced Custom Fields` → 安装并启用

> 如需 Repeater、Options Page 等高级功能，需购买 ACF Pro（https://www.advancedcustomfields.com/pro/）

### 6. 安装 SCSS 编译依赖

```bash
cd wp-content/themes/lemomo
npm install
```

---

## 日常开发启动

每次开始开发时执行以下步骤：

**第一步：启动 Docker**

```bash
# 方式一：打开 Docker Desktop 应用，等待状态变为 Running
# 方式二：命令行
cd /Users/jetwong/Projects/personal/lemomo-wordpress
docker compose up -d
```

**第二步：启动 SCSS 监听**

```bash
cd /Users/jetwong/Projects/personal/lemomo-wordpress/wp-content/themes/lemomo
npm run watch:scss
```

保持此终端运行，修改 `src/scss/main.scss` 会自动编译到 `assets/css/main.css`。

**第三步：开始开发**

- WordPress 后台：http://localhost:8888/wp-admin
- 前台预览：http://localhost:8888
- 数据库管理：http://localhost:8889

---

## 停止环境

```bash
cd /Users/jetwong/Projects/personal/lemomo-wordpress
docker compose down
```

数据库数据会持久化保存在 Docker Volume 中，下次启动不会丢失。

---

## 常用命令

### Docker 容器管理

```bash
# 启动
docker compose up -d

# 停止
docker compose down

# 查看容器状态
docker compose ps

# 查看日志
docker compose logs -f wordpress

# 进入 WordPress 容器
docker exec -it lemomo_wp bash
```

### SCSS 编译

```bash
# 开发模式（监听文件变化，自动编译）
npm run watch:scss

# 生产构建（压缩，无 source map）
npm run build
```

### 调试

```bash
# 实时查看 PHP 错误日志
docker exec lemomo_wp tail -f /var/www/html/wp-content/debug.log

# 修复上传目录权限（图片上传失败时）
docker exec lemomo_wp chmod 777 /var/www/html/wp-content/uploads

# 刷新 WordPress 固定链接（出现 404 时）
docker exec -it lemomo_wp bash -c "wp rewrite flush --allow-root"

# 清除所有 Transient 缓存（接口数据不更新时）
docker exec -it lemomo_wp bash -c "wp transient delete --all --allow-root"
```

---

## 项目目录结构

```
lemomo-wordpress/
├── docker-compose.yml              # Docker 环境配置
├── SETUP.md                        # 本文档
├── .cursor/
│   └── skills/lemomo-project/      # 项目专属 Cursor AI 规范
└── wp-content/
    └── themes/
        └── lemomo/                 # 自定义主题
            ├── style.css           # 主题声明
            ├── functions.php       # 主题注册、ACF、AJAX
            ├── inc/
            │   └── api.php         # 外部接口 + 缓存
            ├── header.php
            ├── footer.php
            ├── index.php
            ├── front-page.php      # 首页
            ├── page-explore.php    # Explore 视频页
            ├── template-parts/
            │   └── home/
            │       ├── hero.php
            │       ├── features.php
            │       └── cta.php
            ├── src/
            │   ├── scss/main.scss  # 源样式（在此编辑）
            │   └── js/main.js      # 源脚本（在此编辑）
            ├── assets/
            │   ├── css/main.css    # 编译产物（勿手动编辑）
            │   └── js/main.js      # 编译产物
            └── package.json
```

---

## 访问地址

| 服务 | 地址 | 说明 |
|------|------|------|
| 网站前台 | http://localhost:8888 | 开发预览 |
| WordPress 后台 | http://localhost:8888/wp-admin | 内容管理 |
| phpMyAdmin | http://localhost:8889 | 数据库管理 |

---

## 常见问题

**Q：启动后访问 localhost:8888 显示空白或报错**
A：等待约 30 秒，MySQL 容器初始化需要时间，刷新页面即可。

**Q：修改了 SCSS 但页面样式没变化**
A：确认 `npm run watch:scss` 正在运行，或手动执行 `npm run build`，再强制刷新浏览器（Cmd+Shift+R）。

**Q：docker compose up 报错 "Cannot connect to the Docker daemon"**
A：Docker Desktop 未启动，打开应用等待状态变为 Running 后重试。

**Q：图片上传失败**
A：执行 `docker exec lemomo_wp chmod 777 /var/www/html/wp-content/uploads`

**Q：页面出现 404**
A：后台 → 设置 → 固定链接 → 直接点「保存更改」（重建规则）。
