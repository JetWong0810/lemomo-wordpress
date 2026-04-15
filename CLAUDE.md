# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Lemomo is a WordPress custom theme for an Indonesian interest-based e-commerce app's marketing site. Design is Figma-driven; content is ACF-driven; video/FAQ/event data comes from an external public App API with Transient caching.

The only theme that matters is `wp-content/themes/lemomo/`. The other `twenty*` themes and the `akismet` / `hello.php` plugins are WordPress defaults (gitignored via `.gitignore`).

## Common commands

All SCSS/JS commands run inside `wp-content/themes/lemomo/`.

```bash
# Boot local stack (WP on :8888, phpMyAdmin on :8889)
docker compose up -d

# SCSS watch (dev) — keep this running while editing styles
cd wp-content/themes/lemomo && npm run watch:scss

# Build compiled assets (minified, no sourcemap) — run before committing / before deploy
cd wp-content/themes/lemomo && npm run build

# Tail PHP errors
docker exec lemomo_wp tail -f /var/www/html/wp-content/debug.log

# Flush rewrites (use when hitting 404s after adding a page template)
docker exec -it lemomo_wp bash -c "wp rewrite flush --allow-root"

# Clear all Lemomo API transients (use when external API data doesn't refresh)
docker exec -it lemomo_wp bash -c "wp transient delete --all --allow-root"
```

There is **no test suite, no linter, no build pipeline beyond `sass` + `cp`**. The `dev` / `build` npm scripts also copy `src/js/main.js` → `assets/js/main.js` — the JS is not bundled, just copied, so you can edit either source or the copy and re-run if needed. `assets/css/main.css` and `assets/js/main.js` are compiled/copied output — never edit them by hand.

## Architecture

### Source → output split

- **Edit** `src/scss/main.scss` and `src/js/main.js`.
- **Never edit** `assets/css/main.css` or `assets/js/main.js` — they are generated. `functions.php` enqueues the `assets/` versions with `filemtime()` cache-busting.
- SCSS is a single file organized top-to-bottom: variables → reset → container → header → sections (hero, features, how-to, testimonials, …) → footer → responsive. New page styles are appended **before** the Responsive block at the bottom.

### Template layout (follow when adding a page)

```
front-page.php                → home (composes 4 template-parts/home/*.php sections)
page-{slug}.php               → one-off page templates (explore, faq, event, about)
template-parts/{page}/*.php   → section partials used via get_template_part()
single.php                    → blog detail (has its own TOC / share / related sidebar)
inc/api.php                   → all external API calls (required from functions.php)
```

When adding a new page, the full flow is: (1) create `page-{slug}.php`, (2) create sections under `template-parts/{slug}/`, (3) register ACF fields (see next section), (4) export the field group JSON to `acf-json/`, (5) append styles in `src/scss/main.scss` before the Responsive block and **also add mobile breakpoints** in the Responsive block, (6) `npm run build`.

### ACF: two registration paths

Field groups are registered in two places — use the right one:

1. **Via ACF admin UI, then auto-exported to JSON** in `wp-content/themes/lemomo/acf-json/`. `functions.php` wires `acf/settings/save_json` and `acf/settings/load_json` to this directory, so editing a field group in the admin writes the JSON for version control. Most content-editor-facing fields (hero, features, testimonials, etc.) live here.
2. **Via `acf_add_local_field_group()` in `functions.php`** under `acf/include_fields`. Used for the FAQ page group and the API Options sub-page (`api_app_base_url`). Prefer this when the field group is mostly structural and rarely edited by content editors.

`functions.php` also stubs `get_field`/`the_field`/`have_rows` when ACF is inactive, so templates don't fatal. Read `get_field('api_app_base_url', 'option')` from the `global-settings` → `api-settings` Options sub-page.

**When editing ACF fields via the admin UI, always export the group JSON to `acf-json/` so it's captured in git.** See `.cursor/skills/lemomo-project/SKILL.md` for the authoritative field-name map.

### External App API (`inc/api.php`)

All external data goes through `lemomo_app_api_request()` → public GET against `get_field('api_app_base_url', 'option')` (default `http://49.232.128.174:48082`). No auth. Everything is wrapped in Transients.

Key data shapes (see `docs/api-integration.md` for the full contract):

- `lemomo_get_video_episodes($page_no, $page_size)` — Explore page. Fetches lesson list `type=0`, then per-item detail to get `url` (video), `message` (description). Returns `[id, title, thumbnail, video_url, description, episode_number, view_count, create_time]`.
- `lemomo_get_offline_events()` — Event page. Lesson list `type=11`.
- `lemomo_get_faq_articles()` — FAQ page. Fetches article list + category names, groups by `categoryId`, keeps both `answer` (stripped) and `answer_html` (`wp_kses_post`).

Transient keys follow the pattern `lemomo_app_list_{type}`, `lemomo_app_detail_{id}`, `lemomo_faq_articles`, `lemomo_faq_categories`. `lemomo_clear_api_cache()` drops all of them in one SQL — it's exposed as the AJAX action `lemomo_clear_cache` (admin-only, nonce-protected) and is wired into the front-end via `wp_localize_script` as `window.lemomo_ajax`.

**Image URLs returned from the API are filtered by `lemomo_safe_pic_url()`** — URLs containing `.me-south-1.amazonaws.com`, `127.0.0.1`, or `localhost` are replaced with an empty string. Templates fall back to local placeholder images (`assets/images/explore-thumb.png`, `event-card1-56586a.png`) when empty.

### Custom post type

`lemomo_media` — 媒体报道 (press mentions). Registered in `functions.php`. Supports `title` + `thumbnail` only, plus a meta box for an external `_lemomo_media_url` (click-through URL). Slug `/media/`, `has_archive => false`.

### Helpers

- `lemomo_date_id($post)` — formats `get_the_time()` as Indonesian date (`4 April 2026`). Use this instead of `get_the_date()` for any user-facing date in templates.

## Figma + design tokens

Design source: `figma.com/design/C6fdxipEgJgB2JS3aNnQGY`. The complete design-token palette (brand colors, gradients, fonts) is documented in `.cursor/skills/lemomo-project/SKILL.md` and should be used verbatim as SCSS variables. If a color or gradient doesn't have a variable yet, add one there rather than hardcoding a hex.

Figma node cache lives in `.figma-cache/` (gitignored). For new pages, read `.figma-cache/design-tokens.md` before querying Figma MCP again.

## Responsive requirement

Every page **must** support both desktop and mobile. Breakpoints in use: `> 1100px` (default), `≤ 1100px`, `≤ 1024px` (grid reflow), `≤ 768px` (single column + hamburger + `scroll-snap-type` for carousels — JS should skip transform animations below this width), `≤ 480px`. See `.cursor/skills/lemomo-project/SKILL.md` §「响应式开发规范」 for the full checklist.

## Security in templates

All dynamic output must go through the appropriate escaper: `esc_html()` for text, `esc_url()` for URLs/attributes, `esc_attr()` for HTML attributes, `wp_kses_post()` for rich content from the API. Image fields from ACF return arrays — use `$image['url']` and `$image['alt']`.

## Local ↔ production

- `docker-compose.yml` is local only. `docker-compose.prod.yml` is the server stack (nginx reverse proxy + certbot + `.env` for secrets + `WP_HOME` / `WP_SITEURL` forced to `https://${DOMAIN}` via `WORDPRESS_CONFIG_EXTRA`).
- Before deploying, always run `npm run build` so `assets/css/main.css` is minified without a source map.
- Database migration uses WP Migrate (UI plugin) or a `mysqldump` + `sed s|http://localhost:8888|https://...|g` pass. See `docs/deployment.md`.

## Further reading

- Phase-by-phase build checklist: `DEVELOPMENT.md`
- Local env setup & troubleshooting FAQ: `SETUP.md`
- Full API contract (payloads, caching, filtering rules): `docs/api-integration.md`
- Production deploy runbook: `docs/deployment.md`
- **Project conventions + ACF field map + brand tokens + CSS class inventory**: `.cursor/skills/lemomo-project/SKILL.md` — read this when touching anything non-trivial.
