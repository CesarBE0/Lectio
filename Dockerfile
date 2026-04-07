# Usamos una imagen que ya tiene PHP y Apache
FROM php:8.2-apache

# 1. Instalamos herramientas del sistema y extensiones de PHP
# Añadimos bcmath y gd (para las portadas de los libros)
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl gnupg libpng-dev \
    && docker-php-ext-install pdo_mysql zip bcmath gd \
    && a2enmod rewrite

# 2. Instalamos Node.js y NPM
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 3. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copiamos el código y configuramos el directorio de trabajo
WORKDIR /var/www/html
COPY . .

# 5. IMPORTANTE: Configurar Git para evitar el error de "dubious ownership"
RUN git config --global --add safe.directory /var/www/html

# 6. Instalamos dependencias de PHP y JS, y compilamos los assets
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# 7. Permisos para que Laravel pueda escribir en las carpetas de caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Configuramos Apache para que apunte a la carpeta /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
