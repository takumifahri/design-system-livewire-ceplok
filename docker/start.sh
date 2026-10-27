#!/bin/sh
# Dijalankan setiap container start: isi ulang cache Laravel dengan env
# container ini, lalu jalankan FrankenPHP.
set -e

# storage/ adalah volume yang bertahan antar-deploy, jadi view hasil kompilasi
# versi lama dibersihkan dulu sebelum cache dibuat ulang.
php artisan optimize:clear
php artisan optimize

exec frankenphp php-server --root /app/public --listen :80
