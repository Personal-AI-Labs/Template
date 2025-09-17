#!/bin/sh
# Exit immediately if a command exits with a non-zero status.
set -e

# This is the crucial part:
# On container startup, we fix the permissions of any directories
# that the application needs to write to.
#
# IMPORTANT: Add the paths to your application's writable directories here.
# For example, for a Laravel application, you would uncomment the following line:
# chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
#
# Add your own application's cache, log, or upload directories below.
# Example:
# chown -R www-data:www-data /var/www/html/logs /var/www/html/cache

# Execute the command passed to this script (i.e., the Dockerfile's CMD)
exec "$@"