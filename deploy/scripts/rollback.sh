#!/usr/bin/env bash
set -euo pipefail
APP_DIR=/var/www/deafjobsindia
PREVIOUS="$1"
test -f "$PREVIOUS/artisan"
ln -sfn "$PREVIOUS" "$APP_DIR/current.next"
mv -Tf "$APP_DIR/current.next" "$APP_DIR/current"
echo "Code rollback only; database migrations were not reversed."
