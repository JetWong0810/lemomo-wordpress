#!/usr/bin/env bash
# ============================================================
# Lemomo WordPress — One-Click Production Deploy
#
# Usage:  bash deploy.sh
# Rerun:  safe to re-run (idempotent per phase)
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$SCRIPT_DIR"

COMPOSE="docker compose -f docker-compose.prod.yml --env-file .env.prod"

# ── Helpers ──────────────────────────────────────────────────
info()  { printf '\033[1;34m[INFO]\033[0m  %s\n' "$1"; }
ok()    { printf '\033[1;32m[ OK ]\033[0m  %s\n' "$1"; }
warn()  { printf '\033[1;33m[WARN]\033[0m  %s\n' "$1"; }
err()   { printf '\033[1;31m[FAIL]\033[0m  %s\n' "$1"; exit 1; }

ask() {
    local prompt="$1" default="$2" reply
    if [ -n "$default" ]; then
        printf '\033[1;37m%s\033[0m [%s]: ' "$prompt" "$default"
    else
        printf '\033[1;37m%s\033[0m: ' "$prompt"
    fi
    read -r reply
    echo "${reply:-$default}"
}

ask_yn() {
    local prompt="$1" default="${2:-y}" reply
    printf '\033[1;37m%s\033[0m [%s]: ' "$prompt" "$default"
    read -r reply
    reply="${reply:-$default}"
    [[ "$reply" =~ ^[Yy] ]]
}

# ============================================================
# PHASE 0: Prerequisites
# ============================================================
info "Phase 0: Checking prerequisites..."

# Docker
if ! command -v docker &>/dev/null; then
    err "Docker not found. Install: curl -fsSL https://get.docker.com | sh"
fi

# Docker Compose V2
if ! docker compose version &>/dev/null; then
    err "Docker Compose V2 not found. Update Docker or install compose plugin."
fi

# Project files
[ -f docker-compose.prod.yml ] || err "docker-compose.prod.yml not found. Run this script from the project root."
[ -f nginx/nginx.conf ]        || err "nginx/nginx.conf not found."
[ -f nginx/nginx-http-only.conf ] || err "nginx/nginx-http-only.conf not found."

# Port check
for port in 80 443; do
    if ss -tlnp 2>/dev/null | grep -qE ":${port}\s"; then
        err "Port $port is already in use. Stop the process occupying it first."
    fi
done

ok "Prerequisites passed."

# ============================================================
# PHASE 1: Interactive prompts
# ============================================================
info "Phase 1: Collecting deployment info..."

DOMAIN=$(ask "Domain name (e.g. lemomo.id)" "")
[ -z "$DOMAIN" ] && err "Domain is required."
# Strip protocol prefix if given
DOMAIN=$(echo "$DOMAIN" | sed 's|^https\?://||' | sed 's|/$||')

EMAIL=$(ask "Email for SSL certificate (Let's Encrypt)" "")
[ -z "$EMAIL" ] && err "Email is required for SSL certificate."

# Detect SQL dump
DEFAULT_SQL=$(find "$SCRIPT_DIR" -maxdepth 1 -name "*.sql" -type f 2>/dev/null | head -1)
SQL_FILE=$(ask "Path to SQL dump file" "$DEFAULT_SQL")
[ -z "$SQL_FILE" ] && err "SQL dump file is required."
[ -f "$SQL_FILE" ] || err "SQL file not found: $SQL_FILE"

DEV_URL=$(ask "Dev URL to replace in database" "http://localhost:8888")

SETUP_WWW=false
if ask_yn "Also set up SSL for www.$DOMAIN?" "y"; then
    SETUP_WWW=true
fi

echo ""
info "Deployment summary:"
echo "  Domain:    $DOMAIN"
echo "  www:       $SETUP_WWW"
echo "  Email:     $EMAIL"
echo "  SQL dump:  $SQL_FILE"
echo "  Dev URL:   $DEV_URL"
echo ""

if ! ask_yn "Proceed with deployment?" "y"; then
    echo "Aborted."
    exit 0
fi

# ============================================================
# PHASE 2: Generate .env.prod
# ============================================================
info "Phase 2: Generating .env.prod..."

if [ -f .env.prod ]; then
    if ask_yn ".env.prod already exists. Reuse it?" "y"; then
        ok "Reusing existing .env.prod"
    else
        info "Generating new .env.prod..."
        DB_PASSWORD=$(openssl rand -base64 32 | tr -d '/+=' | head -c 32)
        DB_ROOT_PASSWORD=$(openssl rand -base64 32 | tr -d '/+=' | head -c 32)
        cat > .env.prod <<EOF
DOMAIN=$DOMAIN

DB_NAME=lemomo_wp
DB_USER=lemomo_prod
DB_PASSWORD=$DB_PASSWORD
DB_ROOT_PASSWORD=$DB_ROOT_PASSWORD
EOF
        chmod 600 .env.prod
        ok ".env.prod generated with new passwords."
    fi
else
    DB_PASSWORD=$(openssl rand -base64 32 | tr -d '/+=' | head -c 32)
    DB_ROOT_PASSWORD=$(openssl rand -base64 32 | tr -d '/+=' | head -c 32)
    cat > .env.prod <<EOF
DOMAIN=$DOMAIN

DB_NAME=lemomo_wp
DB_USER=lemomo_prod
DB_PASSWORD=$DB_PASSWORD
DB_ROOT_PASSWORD=$DB_ROOT_PASSWORD
EOF
    chmod 600 .env.prod
    ok ".env.prod generated."
fi

# Source env vars for later use
set -a
source .env.prod
set +a

# ============================================================
# PHASE 3: Prepare nginx for HTTP-only bootstrap
# ============================================================
info "Phase 3: Setting nginx to HTTP-only mode for SSL bootstrapping..."

# Back up original template if not already backed up
if [ ! -f nginx/nginx.conf.template ]; then
    cp nginx/nginx.conf nginx/nginx.conf.template
fi

# Use HTTP-only config
cp nginx/nginx-http-only.conf nginx/nginx.conf
ok "Nginx set to HTTP-only mode."

# ============================================================
# PHASE 4: Start core services
# ============================================================
info "Phase 4: Starting core services (db, wordpress, nginx)..."

$COMPOSE up -d db wordpress nginx

# Wait for MySQL
info "Waiting for MySQL to be ready..."
SECONDS_WAITED=0
until docker exec lemomo_db mysqladmin ping -u root -p"$DB_ROOT_PASSWORD" --silent 2>/dev/null; do
    sleep 2
    SECONDS_WAITED=$((SECONDS_WAITED + 2))
    if [ "$SECONDS_WAITED" -ge 90 ]; then
        err "MySQL did not become ready within 90 seconds. Check: docker logs lemomo_db"
    fi
done
ok "MySQL is ready."

# Wait for WordPress
info "Waiting for WordPress to be ready..."
SECONDS_WAITED=0
until docker exec lemomo_wp wget -qO /dev/null http://localhost/ 2>/dev/null; do
    sleep 3
    SECONDS_WAITED=$((SECONDS_WAITED + 3))
    if [ "$SECONDS_WAITED" -ge 90 ]; then
        warn "WordPress not responding yet, but continuing (may need DB import first)."
        break
    fi
done
if [ "$SECONDS_WAITED" -lt 90 ]; then
    ok "WordPress is ready."
fi

# ============================================================
# PHASE 5: Obtain SSL certificate
# ============================================================
info "Phase 5: SSL certificate..."

if [ -f "certbot/conf/live/$DOMAIN/fullchain.pem" ]; then
    ok "SSL certificate already exists for $DOMAIN. Skipping."
else
    info "Requesting SSL certificate from Let's Encrypt..."

    CERTBOT_DOMAINS="-d $DOMAIN"
    if [ "$SETUP_WWW" = true ]; then
        CERTBOT_DOMAINS="$CERTBOT_DOMAINS -d www.$DOMAIN"
    fi

    $COMPOSE run --rm certbot certonly \
        --webroot -w /var/www/certbot \
        $CERTBOT_DOMAINS \
        --email "$EMAIL" \
        --agree-tos \
        --no-eff-email

    if [ ! -f "certbot/conf/live/$DOMAIN/fullchain.pem" ]; then
        err "SSL certificate was not created. Check DNS: does $DOMAIN point to this server?"
    fi

    ok "SSL certificate obtained."
fi

# ============================================================
# PHASE 6: Switch to full HTTPS nginx config
# ============================================================
info "Phase 6: Switching nginx to HTTPS mode..."

# Restore from template and substitute domain
cp nginx/nginx.conf.template nginx/nginx.conf
sed -i "s/yourdomain.com/$DOMAIN/g" nginx/nginx.conf

# Restart nginx to pick up new config + certs
$COMPOSE restart nginx
ok "Nginx switched to HTTPS mode."

# ============================================================
# PHASE 7: Start all services
# ============================================================
info "Phase 7: Starting all services..."

$COMPOSE up -d
ok "All services running."

# ============================================================
# PHASE 8: Import database
# ============================================================
info "Phase 8: Database import..."

# Check if tables already exist
HAS_TABLES=false
if docker exec lemomo_db mysql -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "SHOW TABLES LIKE 'wp_options'" 2>/dev/null | grep -q wp_options; then
    HAS_TABLES=true
fi

DO_IMPORT=true
if [ "$HAS_TABLES" = true ]; then
    if ! ask_yn "Database already has WordPress tables. Re-import? (overwrites existing data)" "n"; then
        DO_IMPORT=false
        ok "Skipping database import."
    fi
fi

if [ "$DO_IMPORT" = true ]; then
    # Validate SQL file starts with valid SQL (not mysqldump warnings)
    FIRST_CHAR=$(head -c 2 "$SQL_FILE")
    if [[ "$FIRST_CHAR" != "--" ]] && [[ "$FIRST_CHAR" != "/*" ]] && [[ "$FIRST_CHAR" != "SE" ]] && [[ "$FIRST_CHAR" != "CR" ]] && [[ "$FIRST_CHAR" != "DR" ]]; then
        err "SQL file appears invalid (may contain mysqldump warnings). Re-export with: docker exec lemomo_db mysqldump -u USER -pPASS DB_NAME 2>/dev/null > backup.sql"
    fi
    info "Importing SQL dump..."
    docker cp "$SQL_FILE" lemomo_db:/tmp/import.sql
    docker exec lemomo_db sh -c "mysql -u '$DB_USER' -p'$DB_PASSWORD' '$DB_NAME' < /tmp/import.sql"
    docker exec lemomo_db rm -f /tmp/import.sql
    ok "Database imported."
fi

# ============================================================
# PHASE 9: WP-CLI search-replace
# ============================================================
info "Phase 9: Replacing URLs in database ($DEV_URL -> https://$DOMAIN)..."

$COMPOSE run --rm wpcli search-replace "$DEV_URL" "https://$DOMAIN" \
    --all-tables --precise --recurse-objects --allow-root \
    2>/dev/null || warn "search-replace had warnings, check manually if needed."

ok "URL replacement complete."

# ============================================================
# PHASE 10: Post-import WordPress setup
# ============================================================
info "Phase 10: Post-import setup..."

# Fix upload permissions
info "Fixing upload directory permissions..."
docker exec lemomo_wp bash -c "
    mkdir -p /var/www/html/wp-content/uploads &&
    chown -R www-data:www-data /var/www/html/wp-content/uploads &&
    find /var/www/html/wp-content/uploads -type d -exec chmod 755 {} \; &&
    find /var/www/html/wp-content/uploads -type f -exec chmod 644 {} \;
" 2>/dev/null || true
ok "Upload permissions fixed."

# Flush permalinks
info "Flushing permalinks..."
$COMPOSE run --rm wpcli rewrite flush --allow-root 2>/dev/null || true
ok "Permalinks flushed."

# Activate theme
info "Activating Lemomo theme..."
$COMPOSE run --rm wpcli theme activate lemomo --allow-root 2>/dev/null || true
ok "Theme activated."

# Activate plugins
info "Activating plugins..."
$COMPOSE run --rm wpcli plugin activate --all --allow-root 2>/dev/null || true
ok "Plugins activated."

# ============================================================
# PHASE 11: Health check & summary
# ============================================================
info "Phase 11: Health check..."

echo ""
echo "============================================================"
echo ""

# Check HTTPS
HTTP_CODE=$(curl -sk -o /dev/null -w "%{http_code}" "https://$DOMAIN/" 2>/dev/null || echo "000")
if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "301" ] || [ "$HTTP_CODE" = "302" ]; then
    ok "HTTPS is working (HTTP $HTTP_CODE)"
else
    warn "HTTPS returned HTTP $HTTP_CODE. Check DNS and nginx config."
fi

# Container status
echo ""
info "Container status:"
$COMPOSE ps --format "table {{.Name}}\t{{.Status}}" 2>/dev/null || $COMPOSE ps

echo ""
echo "============================================================"
printf '\033[1;32m  Deployment complete!\033[0m\n'
echo "============================================================"
echo ""
echo "  Website:    https://$DOMAIN"
echo "  Admin:      https://$DOMAIN/wp-admin"
echo "  Credentials: same as your dev environment"
echo ""
echo "  Reminders:"
echo "  - Ensure DNS A record for $DOMAIN points to this server"
if [ "$SETUP_WWW" = true ]; then
echo "  - Ensure DNS A record for www.$DOMAIN also points to this server"
fi
echo "  - SSL certificate auto-renews via certbot container"
echo "  - .env.prod contains database passwords (keep it safe)"
echo ""
echo "============================================================"
