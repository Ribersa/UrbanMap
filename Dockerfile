FROM php:8.2-apache

# Instal depedensi sistem dan Node.js (untuk build asset Vite/Livewire)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Aktifkan mod_rewrite Apache (penting untuk routing Laravel)
RUN a2enmod rewrite

# Ubah DocumentRoot Apache agar menunjuk ke folder 'public' Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set folder kerja
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin seluruh file proyek ke dalam container
COPY . .

# Install dependensi PHP (tanpa package dev)
RUN composer install --optimize-autoloader --no-dev

# Install dependensi Node dan build asset (Tailwind/Vite)
RUN npm install
RUN npm run build

# Atur hak akses folder untuk Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Buka port 80
EXPOSE 80
