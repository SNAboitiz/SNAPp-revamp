#!/bin/bash

# Process manager script for running web server and queue worker
set -e

# Cleanup function to kill all child processes
cleanup() {
    echo "Shutting down processes..."
    kill $(jobs -p) 2>/dev/null || true
    wait
    exit 0
}

# Set up signal handlers
trap cleanup SIGTERM SIGINT

echo "Starting Laravel Queue Worker..."
# Start the queue worker in background
php artisan queue:work --sleep=3 --tries=3 --max-jobs=1000 --max-time=3600 &
QUEUE_PID=$!
echo "Queue worker started with PID: $QUEUE_PID"

echo "Starting Web Server on port $PORT..."
# Start the web server in background
php -S 0.0.0.0:$PORT -t public/ &
WEB_PID=$!
echo "Web server started with PID: $WEB_PID"

# Function to check if processes are running
check_processes() {
    if ! kill -0 $QUEUE_PID 2>/dev/null; then
        echo "Queue worker died, restarting..."
        php artisan queue:work --sleep=3 --tries=3 --max-jobs=1000 --max-time=3600 &
        QUEUE_PID=$!
        echo "Queue worker restarted with PID: $QUEUE_PID"
    fi

    if ! kill -0 $WEB_PID 2>/dev/null; then
        echo "Web server died, restarting..."
        php -S 0.0.0.0:$PORT -t public/ &
        WEB_PID=$!
        echo "Web server restarted with PID: $WEB_PID"
    fi
}

# Keep the script running and monitor processes
while true; do
    check_processes
    sleep 30
done
