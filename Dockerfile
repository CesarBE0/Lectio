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

# 4. Le decimos a Docker dónde trabajar
WORKDIR /app

# 5. Copiamos todos tus archivos del proyecto al contenedor
COPY . .

# 6. Ejecutamos los comandos para instalar y compilar todo (igual que haces tú a mano)
RUN composer install --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# 7. Damos permisos a las carpetas de Laravel
RUN chmod -R 777 storage bootstrap/cache

# 8. Arrancamos el servidor usando el puerto dinámico de Render
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
