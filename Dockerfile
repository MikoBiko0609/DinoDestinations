# Use an official PHP image
FROM php:8.0-apache

# Copy all project files to the web server directory
COPY . /var/www/html/

# Expose port 8080 for Render
EXPOSE 8080

# Start the Apache server
CMD ["apache2-foreground"]
