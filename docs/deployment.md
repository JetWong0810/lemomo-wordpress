# 生产环境部署指南

> 本项目提供 `deploy.sh` 一键部署脚本，运维人员只需准备好服务器环境，运行脚本并按提示操作即可完成部署。

---

## 一、环境要求

| 项目 | 要求 |
|------|------|
| 服务器系统 | Ubuntu 22.04 / Debian 12 |
| Docker | 24.0+（含 Docker Compose V2，`docker compose` 命令可用） |
| 域名 | A 记录已解析到服务器公网 IP（裸域名和 www 均需配置） |
| 端口 | 80、443 已在安全组/防火墙中放行 |
| 磁盘空间 | 至少 5 GB 可用 |

### 验证 Docker 环境

```bash
docker --version          # 应显示 24.0 或更高
docker compose version    # 应显示 Docker Compose v2.x
```

### 安装 Docker（如未安装）

```bash
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
# 注销重新登录使 docker 组生效
```

---

## 二、部署步骤

### 1. 上传文件到服务器

将交付的压缩包上传到服务器并解压：

```bash
# 方式一：scp 上传压缩包
scp lemomo-wordpress-delivery.tar.gz root@服务器IP:/opt/
ssh root@服务器IP
cd /opt
tar xzf lemomo-wordpress-delivery.tar.gz

# 方式二：如果文件已在本地，直接 rsync
scp -r lemomo-wordpress/ root@服务器IP:/opt/
```

确认目录结构：

```bash
ls /opt/lemomo-wordpress/
# 应包含：deploy.sh  docker-compose.prod.yml  lemomo_backup.sql  nginx/  wp-content/  docs/
```

### 2. 运行部署脚本

```bash
cd /opt/lemomo-wordpress
bash deploy.sh
```

脚本会交互式提示输入以下信息：

| 提示 | 说明 | 示例 |
|------|------|------|
| Domain name | 网站域名 | `lemomo.id` |
| Email | Let's Encrypt SSL 证书注册邮箱 | `ops@lemomo.id` |
| Path to SQL dump | 数据库文件路径（自动检测） | 回车使用默认 |
| Dev URL to replace | 开发环境 URL（默认值即可） | 回车使用默认 |
| Also set up SSL for www? | 是否同时配置 www 子域名 | `y` |

脚本自动完成以下工作（约 3-5 分钟）：

| 阶段 | 操作 | 说明 |
|------|------|------|
| 环境检查 | 检查 Docker、端口、文件 | 不通过会提示具体原因 |
| 生成配置 | 创建 `.env.prod` | 自动生成随机数据库密码 |
| SSL 证书 | 自动申请 Let's Encrypt 免费证书 | 需域名 DNS 已生效 |
| 启动服务 | MySQL + WordPress + Nginx + Certbot | 自动等待各服务就绪 |
| 导入数据 | 导入数据库 + 替换 URL | 自动处理序列化数据 |
| 后置配置 | 权限修复、固定链接、激活主题和插件 | 确保站点可正常访问 |
| 健康检查 | 验证 HTTPS 响应 | 输出部署摘要 |

### 3. 验证

脚本执行完毕后，逐项检查：

- [ ] 打开 `https://你的域名` 确认首页正常显示
- [ ] 打开 `https://你的域名/wp-admin` 确认后台可登录
- [ ] 各页面检查：首页 / Blog / Explore / About / Event / FAQ
- [ ] 检查图片是否正常显示
- [ ] 检查视频内容是否正常加载（Explore 和 Event 页面）

> 后台登录使用开发环境的账号密码（已随数据库一起导入）。

---

## 三、脚本重新运行

`deploy.sh` 是幂等的，可以安全地重新运行：

- `.env.prod` 已存在 → 询问是否复用
- SSL 证书已存在 → 自动跳过
- 数据库已有数据 → 询问是否重新导入
- URL 替换、权限修复 → 安全重复执行

遇到部署失败时，排查问题后直接重新运行 `bash deploy.sh` 即可。

---

## 四、SSL 证书续签

`certbot` 容器每 12 小时自动尝试续签，**无需手动操作**。

Let's Encrypt 证书有效期 90 天，自动续签会在到期前 30 天内完成。

手动触发续签（一般不需要）：

```bash
cd /opt/lemomo-wordpress
docker compose -f docker-compose.prod.yml run --rm certbot renew
docker compose -f docker-compose.prod.yml restart nginx
```

---

## 五、日常运维命令

```bash
cd /opt/lemomo-wordpress
COMPOSE="docker compose -f docker-compose.prod.yml --env-file .env.prod"

# ─── 容器管理 ─────────────────────────────────────────────
# 查看容器状态
$COMPOSE ps

# 重启单个服务
$COMPOSE restart wordpress

# 停止所有服务（数据不丢失）
$COMPOSE down

# 启动所有服务
$COMPOSE up -d

# ─── 日志查看 ─────────────────────────────────────────────
# WordPress 日志
docker logs -f lemomo_wp

# Nginx 日志
docker logs -f lemomo_nginx

# ─── 数据库备份 ────────────────────────────────────────────
source .env.prod
docker exec lemomo_db mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME 2>/dev/null > backup_$(date +%Y%m%d).sql

# ─── 常用维护 ─────────────────────────────────────────────
# 修复上传目录权限（图片上传失败时）
docker exec lemomo_wp chown -R www-data:www-data /var/www/html/wp-content/uploads

# 清除 API 缓存（接口数据不更新时）
$COMPOSE run --rm wpcli transient delete --all --allow-root

# 刷新固定链接（页面出现 404 时）
$COMPOSE run --rm wpcli rewrite flush --allow-root

# ─── 代码更新 ─────────────────────────────────────────────
# 如果后续有代码更新，将新文件上传后执行：
$COMPOSE up -d --force-recreate wordpress nginx
```

---

## 六、文件说明

```
lemomo-wordpress/
├── deploy.sh                   # 一键部署脚本
├── docker-compose.prod.yml     # 生产 Docker 配置
├── .env.prod                   # 生产环境变量（部署脚本自动生成，含数据库密码）
├── lemomo_backup.sql           # 数据库导出（部署后可删除）
├── nginx/
│   ├── nginx.conf              # Nginx 完整配置（含 HTTPS）
│   ├── nginx.conf.template     # 域名替换前的原始模板（部署脚本自动生成）
│   └── nginx-http-only.conf    # SSL 申请阶段的临时配置
├── certbot/
│   ├── conf/                   # SSL 证书目录（certbot 自动管理）
│   └── www/                    # ACME 验证目录
└── wp-content/
    ├── themes/lemomo/          # 自定义主题
    └── uploads/                # 媒体文件
```

> `.env.prod` 包含数据库密码，请妥善保管，不要泄露。

---

## 七、常见问题

**Q：deploy.sh 报错 "Docker Compose V2 not found"**
A：服务器 Docker 版本过旧，请升级 Docker。Docker 24.0+ 自带 Compose V2。

**Q：deploy.sh 报错 "Port 80 is already in use"**
A：服务器上已有其他 Web 服务（如 Apache、Nginx）占用 80/443 端口。停止该服务后重新运行脚本。

```bash
# 查看占用端口的进程
ss -tlnp | grep ':80\s'
```

**Q：SSL 证书申请失败**
A：确认以下条件：
1. 域名 A 记录已指向服务器公网 IP
2. DNS 已生效（`dig 你的域名` 返回正确 IP）
3. 端口 80 从外部可访问（防火墙/安全组已放行）

**Q：访问域名显示 502 Bad Gateway**
A：WordPress 容器未完全启动，等待 30 秒后刷新。如持续 502：

```bash
docker logs lemomo_wp    # 查看 WordPress 错误日志
docker logs lemomo_db    # 查看数据库错误日志
```

**Q：网站图片显示不出来**
A：执行以下命令修复上传目录权限：

```bash
docker exec lemomo_wp chown -R www-data:www-data /var/www/html/wp-content/uploads
```

**Q：后台登录后页面跳转异常或跳回 localhost**
A：数据库中的 URL 未正确替换。重新运行 `bash deploy.sh`，在"Database already has WordPress tables"提示时选择 `y` 重新导入。

**Q：页面出现 404**
A：固定链接规则需要刷新：

```bash
cd /opt/lemomo-wordpress
docker compose -f docker-compose.prod.yml --env-file .env.prod run --rm wpcli rewrite flush --allow-root
```

**Q：Explore / FAQ / Event 页面内容为空**
A：这些页面的数据来自外部 API 接口。确认服务器能访问 API 地址（后台 → 全局设置 → 接口配置），并清除缓存：

```bash
cd /opt/lemomo-wordpress
docker compose -f docker-compose.prod.yml --env-file .env.prod run --rm wpcli transient delete --all --allow-root
```
