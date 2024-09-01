# Usa una imagen base con PHP 8.1 y Apache
FROM php:8.1-apache

# Instala las dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define el directorio de trabajo
WORKDIR /var/www/html

# Copia el archivo de configuración de la aplicación y las dependencias
COPY . .

# Instala las dependencias de PHP y Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajusta los permisos para el directorio de almacenamiento
RUN chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage

# Configura Apache para trabajar con Laravel
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Exponemos el puerto 80 para el servicio HTTP
EXPOSE 80

# Inicia Apache
CMD ["apache2-foreground"]
