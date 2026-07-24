#!/bin/bash

echo "🚀 Starting Laravel application..."

# Wait for MySQL to be ready
if [ "$DB_CONNECTION" = "mysql" ]; then
    echo "⏳ Waiting for MySQL to be ready..."
    until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "select 1" > /dev/null 2>&1; do
        printf '.'
        sleep 1
    done
    echo "✅ MySQL is ready!"
fi

# Run migrations (non-blocking)
echo "🔄 Running migrations..."
php artisan migrate --force || echo "⚠️  Migrations failed (dependencies may not be ready yet)"

# Create storage symlink if it doesn't exist
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Clear cache (non-blocking)
echo "🧹 Clearing caches..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "✅ Application ready!"

# Execute the main command
exec "$@"
