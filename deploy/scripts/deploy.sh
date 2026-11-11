#!/usr/bin/env bash
set -euo pipefail
APP_DIR=/var/www/deafjobsindia
RELEASE="$1"
test -f "$RELEASE/artisan"
test -f "$RELEASE/vendor/autoload.php"
test -f "$RELEASE/public/build/manifest.json"
test -f "$APP_DIR/shared/.env"
test -d "$APP_DIR/shared/storage"
ln -sfn "$APP_DIR/shared/.env" "$RELEASE/.env"
rm -rf "$RELEASE/storage"
ln -sfn "$APP_DIR/shared/storage" "$RELEASE/storage"
cd "$RELEASE"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
ln -sfn "$RELEASE" "$APP_DIR/current.next"
mv -Tf "$APP_DIR/current.next" "$APP_DIR/current"
echo "Activated release; perform smoke tests."
