FROM php:8.2-cli

# 1. Instalamos dependencias del sistema (Node, NPM, Zip, etc)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# 2. Instalamos extensiones de PHP (súper importante la de pdo_mysql para tu base de datos)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. AUMENTAMOS LOS LÍMITES DE SUBIDA DE ARCHIVOS PARA PHP (¡La magia para los audiolibros!)
RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini

# 5. Le decimos a Docker dónde trabajar
WORKDIR /app

# 6. Copiamos todos tus archivos del proyecto al contenedor
COPY . .

# 7. Ejecutamos los comandos para instalar y compilar todo (igual que haces tú a mano)
RUN composer install --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# 8. Damos permisos a las carpetas de Laravel
RUN chmod -R 777 storage bootstrap/cache

# 9. Arrancamos el servidor forzando el uso de la shell de forma explícita
CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=10000"]
