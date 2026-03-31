# Lemomo WordPress

Lemomo 官网 WordPress 自定义主题项目 —— 印尼首个基于兴趣的电商平台。

## 技术栈

- **WordPress** 自定义主题（PHP 模板）
- **SCSS** → CSS（Sass 编译）
- **Vanilla JS**（轮播、汉堡菜单）
- **ACF Pro**（可选，字段驱动内容）
- **Docker Compose**（本地开发环境）

## 本地开发

```bash
# 启动容器
docker-compose up -d

# 访问
# WordPress: http://localhost:8080
# phpMyAdmin: http://localhost:8081

# 编译 SCSS
cd wp-content/themes/lemomo
npm install
npm run build    # 单次编译
npm run watch    # 监听模式
```

## 项目结构

```
├── docker-compose.yml
├── wp-content/
│   └── themes/
│       └── lemomo/              # 自定义主题
│           ├── front-page.php   # 首页模板
│           ├── header.php
│           ├── footer.php
│           ├── functions.php
│           ├── template-parts/
│           │   └── home/
│           │       ├── hero.php
│           │       ├── features.php
│           │       ├── how-to.php
│           │       └── testimonials.php
│           ├── src/
│           │   ├── scss/main.scss
│           │   └── js/main.js
│           └── assets/
│               ├── css/
│               ├── js/
│               └── images/
```

## 设计稿

基于 Figma 设计实现，设计 token 缓存在 `.figma-cache/`（已 gitignore）。
