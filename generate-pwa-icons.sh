#!/bin/bash

set -euo pipefail

SOURCE_IMAGE="${1:-public/images/favicon-32x32.png}"
IMAGES_DIR="public/images"

if [ -z "$SOURCE_IMAGE" ] || [ ! -f "$SOURCE_IMAGE" ]; then
    echo "Uso: ./generate-pwa-icons.sh <caminho-da-imagem>"
    exit 1
fi

mkdir -p "$IMAGES_DIR"

if command -v magick >/dev/null 2>&1; then
    IMAGE_TOOL=(magick)
elif command -v convert >/dev/null 2>&1; then
    IMAGE_TOOL=(convert)
else
    echo "ImageMagick nao encontrado. Instale 'magick' ou 'convert' para gerar os icones."
    exit 1
fi

generate_image() {
    local output="$1"
    local width="$2"
    local height="$3"
    local scale="$4"
    local background="$5"

    local scaled_width
    local scaled_height
    scaled_width="$(printf '%.0f' "$(awk "BEGIN { print $width * $scale }")")"
    scaled_height="$(printf '%.0f' "$(awk "BEGIN { print $height * $scale }")")"

    "${IMAGE_TOOL[@]}" "$SOURCE_IMAGE" \
        -resize "${scaled_width}x${scaled_height}" \
        -background "$background" \
        -gravity center \
        -extent "${width}x${height}" \
        "$output"
}

generate_image "$IMAGES_DIR/icon-192x192.png" 192 192 0.92 "#ffffff"
generate_image "$IMAGES_DIR/icon-512x512.png" 512 512 0.92 "#ffffff"
generate_image "$IMAGES_DIR/icon-192x192-maskable.png" 192 192 0.70 "#0891b2"
generate_image "$IMAGES_DIR/icon-512x512-maskable.png" 512 512 0.70 "#0891b2"
generate_image "$IMAGES_DIR/badge-72x72.png" 72 72 0.78 "#0891b2"
generate_image "$IMAGES_DIR/shortcut-presenca-96x96.png" 96 96 0.82 "#0f766e"
generate_image "$IMAGES_DIR/shortcut-financeiro-96x96.png" 96 96 0.82 "#155e75"

echo "Icônes gerados em $IMAGES_DIR usando a logo informada."
