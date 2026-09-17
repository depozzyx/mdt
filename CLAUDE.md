# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A WordPress 5.5.5 site for MDT Group (mdtgroup.com.ua), a Ukrainian flooring/interior-materials
company. The repo root **is** the WordPress installation itself (`index.php`, `wp-admin/`,
`wp-includes/`, `wp-config.php` all live at the top level) — there is no separate `public/` or
`web/` subfolder. All custom code lives in the theme at `wp-content/themes/mdt/`.

`wp-config.php` is tracked in git and contains real production DB credentials. Treat it as
sensitive; don't dump its contents into logs, commits, or shared output.

## Local development (Docker)

A Docker setup for running the site against a copy of the production database lives in `docker/`:

```bash
docker compose up -d --build     # start MariaDB + WordPress (PHP 7.4 + Apache)
```

Site is served at `http://localhost:8080`. Key pieces:

- `docker/wp-config-local.php` is bind-mounted **over** the real `wp-config.php` inside the
  container only — the tracked `wp-config.php` (production credentials) is never touched or
  overwritten. Local DB creds/host live there instead.
- The DB container auto-imports any `*.sql` dump placed at the repo root matching
  `docker-entrypoint-initdb.d` on first boot (see `docker-compose.yml`). `*.sql` is gitignored.
- `mod_expires` is deliberately **not** enabled in `docker/Dockerfile`, and
  `docker/no-cache.conf` forces `Cache-Control: no-store` for `.js`/`.css`. Production's
  `.htaccess` sets ~120-day caching on those files, which otherwise makes the browser silently
  keep serving stale assets after every local rebuild.
- `mod_headers` **must** stay enabled — the last block in the root `.htaccess`
  (`Header set Cache-Control "max-age=2592000"` for `.jpg`/`.jpeg`) isn't wrapped in
  `<IfModule mod_headers.c>` like the other blocks, so disabling that module causes a hard 500.

### Rebuilding theme assets for local use

Do **not** run a bare `npm run build` when iterating locally — use:

```bash
docker/rebuild-assets.sh
```

This builds the theme's webpack bundle *and* restores two things that a plain build wipes out
and can't regenerate from source (see `docker/vendor/`):

- `jquery.maskedinput.min.js` — manually vendored on production and loaded via a direct
  `<script>` tag in `footer.php`; it was never part of the webpack entry/source.
- `dist/style.css` — the committed SCSS source is **incomplete**. It's missing a chunk of rules
  that are live on production (confirmed by diffing against `mdtgroup.com.ua`'s actual inlined
  CSS — production's stylesheet is ~40% bigger than what this repo's SCSS builds, including the
  rule that hides the header's category mega-menu by default). Until the missing partials are
  found and committed, local dev uses production's real compiled CSS
  (`docker/vendor/style.css.prod-fallback`) instead of the from-source build.

`wp-content/uploads` is gitignored and not synced locally, so images/media will 404 in local dev
unless you separately copy them from the server.

## Theme asset build (webpack)

Source lives in `wp-content/themes/mdt/assets/` (package name `coppertin`); build output goes to
`wp-content/themes/mdt/assets/dist/`, which is **not committed to git** — you must build it
before the theme will render correctly (missing CSS/JS silently fall through to WordPress's
catch-all rewrite, so `dist/*.js`/`.css` requests return an HTML page with a 200 instead of a
real 404).

```bash
cd wp-content/themes/mdt/assets
npm install
NODE_OPTIONS=--openssl-legacy-provider npm run build   # webpack --mode production
npm run dev                                             # webpack-dev-server, port 9000
```

Node compatibility notes for this old (webpack 4 / Babel 7.8 / 2020-era) toolchain:

- `NODE_OPTIONS=--openssl-legacy-provider` is required on Node 17+ (webpack 4's hashing calls
  the now-removed legacy OpenSSL `md4` provider).
- The original `node-sass` dependency does not build on modern Node/Apple Silicon (needs Python 2,
  no arm64 binaries). It's been replaced with `sass` (Dart Sass) in `package.json`; `sass-loader`
  picks it up automatically since `webpack.config.js` doesn't hardcode an implementation.
- `main.js` calls jQuery plugin methods (`.scrollbar()`, `.matchHeight()`, `.slick()`,
  `.fancybox()`) whose imports (`jquery.scrollbar`, `jquery-match-height`, `slick-carousel`,
  `@fancyapps/fancybox`) must stay at the top of `src/js/main.js` — if a future edit drops one,
  the whole comma-chained ready-handler throws partway through and everything after it (menu
  toggles, sliders, etc.) silently stops working, which is easy to misdiagnose as unrelated bugs.

There is no test suite, linter, or CI config in this repo (no `composer.json`, `phpunit.xml`,
`.eslintrc`, etc.) — nothing to run beyond the webpack build above.

## WordPress architecture notes

- **No test/staging separation in code**: single `wp-config.php`, single environment.
- **Custom post types and menus are DB-driven, not code-driven.** There's no
  `register_post_type()` in the theme; content types (products, projects, suppliers, "use" pages,
  etc. — see the `single-*.php` templates in `wp-content/themes/mdt/`) are registered through the
  **Custom Post Type UI** plugin and stored as options in the database, not in git. Likewise the
  header nav in `header.php` doesn't use `wp_nav_menu()` — it fetches a hardcoded menu ID
  (`wp_get_nav_menu_items(32, ...)`) and hardcoded page/category IDs (`$ct_id = 522` /
  `11745`), so menu structure changes only take effect by editing that specific DB record via
  wp-admin, not by editing theme code.
- **ACF Pro + ACF Multilingual (acfml)** drive most custom field data on posts/pages.
- **Multilingual via WPML** (`sitepress-multilingual-cms` + `wpml-*` plugins): content exists in
  multiple languages (see the `ua`/`ru` switcher in the header); translations are DB-driven.
- **Caching/optimization plugins**: WP Fastest Cache + Autoptimize combine/minify/cache CSS and
  JS in `wp-content/cache/` (which **is** git-tracked — a stale copy there can mask real asset
  changes; when debugging asset issues, check whether `wp-content/cache/` is serving an outdated
  bundle before assuming the theme source itself is wrong). Rank Math handles SEO; EWWW Image
  Optimizer + WP Retina 2x handle image compression/retina variants.
- **`.gitignore` excludes common binary extensions globally** (`*.png`, `*.gif`, `*.ico`, etc.),
  not just `wp-content/uploads`. This also strips images that ship as part of WordPress core
  itself (e.g. `wp-includes/images/`), since git doesn't track directories that end up empty —
  don't be surprised by 404s/missing-dir warnings for core WP asset paths on a fresh clone.
