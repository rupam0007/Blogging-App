#!/bin/bash

# Wait for database to be ready
echo "Waiting for database..."
for i in {1..30}; do
    if php artisan migrate:status &> /dev/null; then
        echo "Database is ready!"
        break
    fi
    echo "Attempt $i: Database not ready, waiting..."
    sleep 2
done

# Run migrations
echo "Running migrations..."
php artisan migrate --force || echo "Migrations failed or already up to date"

# Start the application
echo "Starting application..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
