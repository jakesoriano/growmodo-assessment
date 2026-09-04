#!/usr/bin/env bash
# Symlink theme and plugin into Local WP wp-content for development.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
THEME_SRC="$ROOT/theme/estatein"
PLUGIN_SRC="$ROOT/plugin/estatein-core"
WP_CONTENT="$ROOT/app/public/wp-content"

ln -sfn "$THEME_SRC" "$WP_CONTENT/themes/estatein"
ln -sfn "$PLUGIN_SRC" "$WP_CONTENT/plugins/estatein-core"

echo "Symlinked theme and plugin into $WP_CONTENT"
