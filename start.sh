#!/bin/bash

# Set default port if not provided
export PORT=${PORT:-8080}

# Only set if explicitly provided
export APP_URL=${APP_URL:-""}

# Force HTTPS in production environments
if [ "$APP_ENV" = "production" ] || [ "$APP_ENV" = "staging" ]; then
    export FORCE_HTTPS=true
else
    export FORCE_HTTPS=false
fi

# Ensure storage directories exist and are writable
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Run Laravel optimizations
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Ensure public directory structure and permissions
echo "Setting up public directory permissions..."
mkdir -p public/css public/js
chmod -R 755 public/css public/js

# Run database migrations (only if database is available)
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migration failed or database not available"

# Run database seeders (only if needed)
# echo "Running database seeders..."
# php artisan db:seed --force 2>/dev/null || echo "Seeding failed or not needed"

# Run Laravel optimization
echo "Running Laravel optimization..."
php artisan optimize 2>/dev/null || echo "Optimization failed"

# Clear any failed jobs from previous runs
echo "Clearing failed jobs..."
php artisan queue:clear 2>/dev/null || true

# Start supervisor to manage web server and queue worker
echo "Starting supervisor to manage web server and queue worker..."
exec ./supervisor.sh
