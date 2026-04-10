# 生产环境部署指南

> 适用场景：将本地开发完成的 Lemomo WordPress 项目部署到云服务器，配置域名 + HTTPS。

---

## 环境要求

| 项目 | 要求 |
|------|------|
| 服务器系统 | Ubuntu 22.04 / Debian 12（推荐） |
| Docker | 24.0+ |
| Docker Compose | 2.20+（`docker compose` 命令） |
| 域名 | 已完成 A 记录解析到服务器公网 IP |
| 端口 | 80、443 已开放（安全组/防火墙放行） |

---

## 文件说明

```
lemomo-wordpress/
├── docker-compose.yml          # 本地开发专用，不用于生产
├── docker-compose.prod.yml     # 生产部署配置
├── .env.example                # 环境变量模板
├── nginx/
│   └── nginx.conf              # Nginx 反向代理 + SSL 配置
└── certbot/
    ├── conf/                   # SSL 证书存放目录（git 已忽略）
    └── www/                    # Let's Encrypt webroot 验证目录
```

---

## 一、本地准备（交付前在本机执行）

### 1. 编译生产版 CSS/JS

```bash
cd wp-content/themes/lemomo
npm run build
cd ../../..
```

> `npm run build` 会压缩 CSS 并去掉 source-map，适合生产环境。

### 2. 导出数据库

```bash
docker exec lemomo_db mysqldump -u lemomo -plemomo123 lemomo_wp > lemomo_backup.sql
```

### 3. 替换数据库中的本地 URL

```bash
sed -i 's|http://localhost:8888|https://yourdomain.com|g' lemomo_backup.sql
```

> 将 `yourdomain.com` 替换为实际域名。

---

## 二、服务器初始化

### 1. 安装 Docker

```bash
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
newgrp docker
```

### 2. 拉取代码

```bash
cd /opt
git clone <你的 git 仓库地址> lemomo-wordpress
cd lemomo-wordpress
```

### 3. 创建生产环境变量文件

```bash
cp .env.example .env.prod
nano .env.prod
```

填写以下内容（密码使用强密码，可用 `openssl rand -base64 32` 生成）：

```bash
DOMAIN=yourdomain.com

DB_NAME=lemomo_wp
DB_USER=lemomo_prod
DB_PASSWORD=<32位随机密码>
DB_ROOT_PASSWORD=<32位随机密码>
```

### 4. 修改 nginx.conf 中的域名

```bash
sed -i 's/yourdomain.com/你的真实域名/g' nginx/nginx.conf
```

---

## 三、申请 SSL 证书（首次）

Let's Encrypt 证书通过 HTTP-01 验证，需要先让 80 端口可以响应验证请求。

### 1. 临时只启动 HTTP 模式

注释掉 `nginx/nginx.conf` 中的 443 server 块（`# server { listen 443 ssl ...`），只保留 80 的配置，然后启动：

```bash
docker compose -f docker-compose.prod.yml --env-file .env.prod up -d nginx db wordpress
```

### 2. 申请证书

```bash
docker compose -f docker-compose.prod.yml run --rm certbot certonly \
  --webroot -w /var/www/certbot \
  -d yourdomain.com -d www.yourdomain.com \
  --email your@email.com \
  --agree-tos \
  --no-eff-email
```

成功后证书文件位于 `certbot/conf/live/yourdomain.com/`。

### 3. 恢复 nginx.conf 443 配置并重启

取消 443 server 块的注释，然后：

```bash
docker compose -f docker-compose.prod.yml restart nginx
```

---

## 四、完整启动所有服务

```bash
docker compose -f docker-compose.prod.yml --env-file .env.prod up -d
```

验证容器状态：

```bash
docker compose -f docker-compose.prod.yml ps
```

预期输出：

```
NAME             STATUS
lemomo_db        running
lemomo_wp        running
lemomo_nginx     running
lemomo_certbot   running
```

---

## 五、导入数据库

将本地导出的 `lemomo_backup.sql` 上传到服务器，然后执行：

```bash
# 上传（本机执行）
scp lemomo_backup.sql root@<服务器IP>:/opt/lemomo-wordpress/

# 服务器端导入
source /opt/lemomo-wordpress/.env.prod
docker exec -i lemomo_db mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME < /opt/lemomo-wordpress/lemomo_backup.sql
```

---

## 六、WordPress 后台最终确认

1. 访问 `https://yourdomain.com/wp-admin`，用本地相同的账号密码登录
2. 后台 → 设置 → 常规 → 确认「WordPress 地址」和「站点地址」均为 `https://yourdomain.com`
3. 后台 → 设置 → 固定链接 → 直接点「保存更改」（重建 rewrite 规则）
4. 后台 → 外观 → 主题 → 确认 Lemomo 主题已启用
5. 后台 → 插件 → 确认 ACF / ACF Pro 已启用

---

## 七、SSL 证书自动续签

`certbot` 容器已配置每 12 小时自动尝试续签，无需手动操作。

手动触发续签（可选）：

```bash
docker compose -f docker-compose.prod.yml run --rm certbot renew
docker compose -f docker-compose.prod.yml restart nginx
```

---

## 八、日常运维命令

```bash
# 查看容器状态
docker compose -f docker-compose.prod.yml ps

# 查看 WordPress 日志
docker logs -f lemomo_wp

# 查看 Nginx 日志
docker logs -f lemomo_nginx

# 重启单个服务
docker compose -f docker-compose.prod.yml restart wordpress

# 停止所有服务（数据不丢失）
docker compose -f docker-compose.prod.yml down

# 更新代码后重新部署
git pull
docker compose -f docker-compose.prod.yml --env-file .env.prod up -d --force-recreate wordpress nginx

# 备份数据库
source .env.prod
docker exec lemomo_db mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > backup_$(date +%Y%m%d).sql

# 修复上传目录权限
docker exec lemomo_wp chmod 777 /var/www/html/wp-content/uploads

# 清除 Transient 缓存（接口数据不更新时）
docker compose -f docker-compose.prod.yml run --rm wpcli transient delete --all --allow-root
```

---

## 九、常见问题

**Q：访问域名显示 `502 Bad Gateway`**  
A：WordPress 容器未就绪，等待 30 秒后刷新；或执行 `docker logs lemomo_wp` 查看错误。

**Q：HTTPS 证书报错 / 浏览器显示不安全**  
A：确认 certbot 证书申请成功，检查 `certbot/conf/live/yourdomain.com/` 目录是否存在证书文件；确认 `nginx.conf` 中证书路径与域名一致。

**Q：图片/媒体无法上传**  
A：执行 `docker exec lemomo_wp chmod 777 /var/www/html/wp-content/uploads`

**Q：页面内容是旧的本地数据**  
A：检查数据库是否成功导入，后台 → 设置 → 固定链接 → 保存更改。

**Q：后台登录后跳转回 localhost**  
A：数据库中 siteurl/home 未更新，执行步骤二-3 的 `sed` 命令重新替换后再导入。

**Q：服务器更新代码后样式没变化**  
A：本地先执行 `npm run build`，将编译后的 `assets/css/main.css` 和 `assets/js/main.js` 一起提交，再 `git pull`。
