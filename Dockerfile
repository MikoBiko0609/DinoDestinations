# Use an official PHP image with Apache
FROM php:8.0-apache

# Copy all project files to the Apache server directory
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Ensure required PHP extensions are installed (e.g., mysqli for MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose port 8080 for Render
EXPOSE 8080

# Start the Apache server
CMD ["apache2-foreground"]
