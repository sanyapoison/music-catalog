# Используем PHP образ
FROM php:8.2-fpm

# Установка системных зависимостей и Composer
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    sqlite3 \
    libsqlite3-dev

# Установка расширений PHP
RUN docker-php-ext-install pdo pdo_sqlite

# Установка Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www/html

# Копируем приложение в контейнер
COPY . /var/www/html

# Установка зависимостей Laravel
RUN composer install

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Запуск команды Laravel
CMD ["php-fpm"]
