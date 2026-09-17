#!/usr/bin/env bash
# Rebuilds the theme's dist/ assets and restores the pieces that aren't part
# of the committed webpack source (see README notes below). Run this instead
# of a bare `npm run build` whenever you edit theme JS/SCSS locally.
set -euo pipefail

THEME_ASSETS="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)/wp-content/themes/mdt/assets"
VENDOR_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/vendor"

cd "$THEME_ASSETS"
NODE_OPTIONS=--openssl-legacy-provider npm run build

# jquery.maskedinput.min.js: manually vendored on production, referenced
# directly by footer.php, never part of the webpack entry/source.
cp "$VENDOR_DIR/jquery.maskedinput.min.js" "$THEME_ASSETS/dist/jquery.maskedinput.min.js"

# style.css: the committed SCSS source is missing a chunk of rules that are
# live on production (confirmed by diffing against mdtgroup.com.ua's actual
# inlined CSS - it's ~90KB / ~40% bigger than what our source builds).
# Until that gap is found and added to git, use production's real compiled
# CSS for local visual accuracy instead of the incomplete from-source build.
cp "$VENDOR_DIR/style.css.prod-fallback" "$THEME_ASSETS/dist/style.css"

echo "Rebuilt dist/ and restored jquery.maskedinput.min.js + style.css (prod fallback)."
