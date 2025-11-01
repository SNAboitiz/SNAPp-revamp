#!/bin/sh

set -x # Enable debugging

echo "Starting entrypoint script..."

# Ensure storage and bootstrap/cache directories are writable
echo "Setting permissions for storage and bootstrap/cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create .env if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Ensure .env is writable
chmod 666 /var/www/html/.env

# Run Composer install
echo "Running composer install..."
composer install --no-interaction --optimize-autoloader

# Install npm dependencies
echo "Installing npm dependencies..."
npm install

# Dump autoload files
echo "Dumping autoload files..."
composer dump-autoload

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Create storage link
echo "Creating storage link..."
# Remove existing link if it exists
if [ -L /var/www/html/public/storage ]; then
    echo "Removing existing storage link..."
    rm /var/www/html/public/storage
fi
php artisan storage:link

# Verify storage link was created
if [ -L /var/www/html/public/storage ]; then
    echo "Storage link created successfully"
    ls -la /var/www/html/public/storage
else
    echo "Warning: Storage link was not created"
fi

# Clear config
echo "Clearing config..."
php artisan config:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders
echo "Running seeders..."
php artisan db:seed --force

# Cache config
echo "Caching config..."
php artisan cache:clear

# Optimize the application
echo "Optimizing the application..."
php artisan optimize

# Format the code
echo "Formatting the code..."
npm run format

# Start Supervisor
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf
