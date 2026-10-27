#!/usr/bin/env bash
# Deploy situs docs di server dengan Docker. Dijalankan workflow
# .github/workflows/deploy.yml setelah kode ditarik ke commit terbaru main.
#
# Image tidak di-build di sini. Workflow mem-build-nya di GitHub Actions dan
# mendorongnya ke GHCR, lalu skrip ini menarik image itu. APP_IMAGE (nama image)
# dan APP_TAG (commit) dikirim workflow. Bila dijalankan manual, keduanya dibaca
# dari .env, dengan APP_TAG bawaan `latest`.
set -euo pipefail

cd "$(dirname "$0")/.."

docker compose pull app

# --no-build memastikan server tidak pernah mem-build sendiri. --wait menunggu
# healthcheck /up lolos, supaya workflow gagal bila container baru tidak mau jalan.
docker compose up -d --no-build --remove-orphans --wait --wait-timeout 120

docker image prune -f
