# 生产环境部署指南

> 本项目提供 `deploy.sh` 一键部署脚本，运维人员只需准备好服务器环境，运行脚本并按提示操作即可完成部署。

---

## 一、环境要求

| 项目 | 要求 |
|------|------|
| 服务器系统 | Ubuntu 22.04 / Debian 12 |
| Docker | 24.0+（含 Docker Compose V2） |
| 域名 | A 记录已解析到服务器公网 IP |
| 端口 | 80、443 已在安全组/防火墙中放行 |

### 安装 Docker（如未安装）

```bash
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
# 注销重新登录使 docker 组生效
```

---

## 二、开发者交付前准备

> 以下步骤由开发者在本地执行，完成后将文件交付给运维。

### 1. 编译生产版资源

```bash
cd wp-content/themes/lemomo
npm run build
cd ../../..
```

### 2. 导出数据库

```bash
docker exec lemomo_db mysqldump -u lemomo -plemomo123 lemomo_wp > lemomo_backup.sql
```

> **不要** 对 SQL 文件做 sed 替换。`deploy.sh` 使用 WP-CLI `search-replace` 自动处理 URL 替换，能正确处理 PHP 序列化数据。

### 3. 交付清单

将以下内容交给运维：

- 项目代码（git 仓库或压缩包）
- `lemomo_backup.sql` 数据库导出文件
- `wp-content/uploads/` 目录（如有本地上传的媒体文件）

---

## 三、部署（运维在服务器上执行）

### 1. 上传文件到服务器

```bash
# 上传项目代码
scp -r lemomo-wordpress/ root@SERVER_IP:/opt/

# 上传数据库导出文件
scp lemomo_backup.sql root@SERVER_IP:/opt/lemomo-wordpress/

# 上传媒体文件（如有）
scp -r wp-content/uploads/ root@SERVER_IP:/opt/lemomo-wordpress/wp-content/uploads/
```

或通过 git clone：

```bash
cd /opt
git clone <仓库地址> lemomo-wordpress
cd lemomo-wordpress
# 将 lemomo_backup.sql 放到项目根目录
```

### 2. 运行部署脚本

```bash
cd /opt/lemomo-wordpress
bash deploy.sh
```

脚本会交互式提示输入：
- **域名**（如 `lemomo.id`）
- **邮箱**（用于 Let's Encrypt SSL 证书注册）
- **SQL 文件路径**（自动检测项目根目录下的 `.sql` 文件）
- **是否配置 www 子域名**

脚本自动完成以下工作：

| 阶段 | 操作 | 说明 |
|------|------|------|
| 环境检查 | 检查 Docker、端口、文件 | 不通过会提示具体原因 |
| 生成密码 | 创建 `.env.prod` | 自动生成随机强密码 |
| SSL 证书 | 自动申请 Let's Encrypt | 需域名已解析到当前服务器 |
| 启动服务 | MySQL + WordPress + Nginx + Certbot | 自动等待各服务就绪 |
| 导入数据 | 导入 SQL + 替换 URL | WP-CLI search-replace 安全处理序列化数据 |
| 后置配置 | 权限修复、固定链接、激活主题和插件 | 确保站点可正常访问 |
| 健康检查 | 验证 HTTPS 响应 | 输出部署摘要 |

### 3. 验证

- [ ] 打开 `https://你的域名` 确认首页正常
- [ ] 打开 `https://你的域名/wp-admin` 确认后台可登录（使用开发环境的账号密码）
- [ ] 检查图片是否正常显示
- [ ] 检查 Explore / Event / FAQ 页面内容是否正常

---

## 四、脚本重新运行

`deploy.sh` 是幂等的，可以安全地重新运行：

- `.env.prod` 已存在 → 询问是否复用
- SSL 证书已存在 → 自动跳过
- 数据库已有数据 → 询问是否重新导入
- URL 替换、权限修复 → 安全重复执行

---

## 五、SSL 证书续签

`certbot` 容器每 12 小时自动尝试续签，无需手动操作。

手动触发续签：

```bash
cd /opt/lemomo-wordpress
docker compose -f docker-compose.prod.yml run --rm certbot renew
docker compose -f docker-compose.prod.yml restart nginx
```

---

## 六、日常运维命令

```bash
cd /opt/lemomo-wordpress
COMPOSE="docker compose -f docker-compose.prod.yml --env-file .env.prod"

# 查看容器状态
$COMPOSE ps

# 查看 WordPress 日志
docker logs -f lemomo_wp

# 查看 Nginx 日志
docker logs -f lemomo_nginx

# 重启单个服务
$COMPOSE restart wordpress

# 停止所有服务（数据不丢失）
$COMPOSE down

# 重新启动所有服务
$COMPOSE up -d

# 更新代码后重新部署
git pull
$COMPOSE up -d --force-recreate wordpress nginx

# 备份数据库
source .env.prod
docker exec lemomo_db mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > backup_$(date +%Y%m%d).sql

# 修复上传目录权限
docker exec lemomo_wp chown -R www-data:www-data /var/www/html/wp-content/uploads

# 清除 API 缓存
$COMPOSE run --rm wpcli transient delete --all --allow-root

# 刷新固定链接
$COMPOSE run --rm wpcli rewrite flush --allow-root
```

---

## 七、文件说明

```
lemomo-wordpress/
├── deploy.sh                   # 一键部署脚本
├── docker-compose.yml          # 本地开发专用，不用于生产
├── docker-compose.prod.yml     # 生产部署配置
├── .env.example                # 环境变量模板（参考用）
├── .env.prod                   # 生产环境变量（脚本自动生成，勿提交 git）
├── nginx/
│   ├── nginx.conf              # Nginx 完整配置（含 HTTPS）
│   ├── nginx.conf.template     # 部署时自动备份的原始模板
│   └── nginx-http-only.conf    # SSL 申请阶段的临时配置
├── certbot/
│   ├── conf/                   # SSL 证书目录（certbot 自动管理）
│   └── www/                    # ACME challenge 验证目录
└── wp-content/
    └── themes/lemomo/          # 自定义主题
```

---

## 八、常见问题

**Q：deploy.sh 报错 "Port 80 is already in use"**
A：服务器上已有其他 Web 服务占用端口，停止后重新运行脚本。

**Q：SSL 证书申请失败**
A：确认域名的 A 记录已经指向当前服务器 IP，且 DNS 已生效（可用 `dig yourdomain.com` 检查）。

**Q：访问域名显示 502 Bad Gateway**
A：WordPress 容器未就绪，等待 30 秒后刷新。或执行 `docker logs lemomo_wp` 查看错误。

**Q：图片/媒体无法上传**
A：执行 `docker exec lemomo_wp chown -R www-data:www-data /var/www/html/wp-content/uploads`

**Q：后台登录后跳转到 localhost**
A：URL 替换未完成，重新运行 `deploy.sh`，在数据库导入步骤选择重新导入。

**Q：更新代码后样式没变化**
A：开发者需在本地执行 `npm run build` 并将编译后的 `assets/css/main.css` 和 `assets/js/main.js` 一起提交，服务器 `git pull` 后重启 WordPress 容器。
