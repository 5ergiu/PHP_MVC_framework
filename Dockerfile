FROM php:8.0-fpm-alpine

ARG PHP_EXTENSIONS="dom mbstring curl gd intl pdo mysqli pdo_mysql session zip"

# Install dependencies
RUN apk add --update \
		autoconf \
		curl \
		ca-certificates \
		freetype-dev \
		gcc \
		icu-dev \
		libcurl \
        curl-dev \
		libffi-dev \
		libgcrypt-dev \
		libjpeg-turbo-dev \
		libmcrypt-dev \
		libpng-dev \
		libpq \
		libressl-dev \
		libxslt-dev \
		libzip-dev \
		linux-headers \
		make \
		musl-dev \
		nginx \
        oniguruma-dev \
		supervisor && \
		pecl install -f xdebug && \
    docker-php-ext-enable xdebug && \    
	docker-php-ext-install $PHP_EXTENSIONS && \
    echo -e "xdebug.mode=develop\n\
    xdebug.var_display_max_children = -1\n\
    xdebug.var_display_max_depth = -1\n\
    xdebug.var_display_max_data = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \ 
	docker-php-source delete && \
	apk del --purge gcc musl-dev linux-headers libffi-dev make autoconf && \
	rm -rf /var/cache/apk/* && \
	rm /etc/nginx/conf.d/default.conf && \
	curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
	php -r "unlink('composer-setup.php');"

# Symling php8 => php
RUN ln -s /usr/bin/php8 /usr/bin/php

# Configure nginx
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY .docker/fpm-pool.conf /etc/php8/php-fpm.d/www.conf

# Configure supervisord
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/blog

# Add application
WORKDIR /var/www/blog
COPY . /var/www/blog/

# Expose the port nginx is reachable on
EXPOSE 80

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
