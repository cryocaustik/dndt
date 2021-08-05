FROM php:7-fpm

COPY src/composer.lock src/composer.json /var/www/

WORKDIR /var/www

RUN apt update && apt install -y \
  build-essential \
  libpng-dev \
  libjpeg62-turbo-dev \
  libfreetype6-dev \
  locales \
  zip \
  jpegoptim optipng pngquant gifsicle \
  vim \
  unzip \
  git \
  curl

RUN apt clean && rm -rf /var/lib/apt/lists/*

# docker extensions
RUN docker-php-ext-install pdo_mysql exif pcntl
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
# RUN docker-php-ext-install gd

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY --chown=www-data src/ /var/www

# Start php-fpm server
CMD ["php-fpm"]