#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/../.."
test -f artisan
test -f composer.lock
test -f package-lock.json
test -f public/build/manifest.json
php artisan migrate:status
php artisan route:list --path=admin
echo "Preflight passed; verify backups and staging separately."
