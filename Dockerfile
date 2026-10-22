FROM php:8.3.36-apache@sha256:756c2884677bedea30afaa95811b42cd3ff251203291c5820c1ad209e86c9fb4
LABEL org.opencontainers.image.source="https://github.com/docker-library/php" org.opencontainers.image.version="8.3.36" security.scope="Official maintained PHP 8.3.36 release with legacy workload extension superset"
RUN apt-get update && apt-get install -y --no-install-recommends libbz2-dev libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j4 bcmath bz2 gd mysqli pdo_mysql pdo_pgsql pgsql sockets zip \
 && a2enmod rewrite \
 && php -v && php -m \
 && rm -rf /var/lib/apt/lists/*

# Old Docker seccomp returns EPERM for clone3; additive filter forces libc fallback.
COPY security/php-runtime/clone3-fallback.c /tmp/clone3-fallback.c
RUN cc -O2 -Wall -Wextra -Werror -static /tmp/clone3-fallback.c -o /usr/local/bin/clone3-fallback \
 && strip /usr/local/bin/clone3-fallback && rm /tmp/clone3-fallback.c

# Runtime-only incident image: application source/configuration stays in private host mounts.
WORKDIR /app
RUN printf '\nListen 5000\n' >> /etc/apache2/ports.conf && usermod -u 32767 www-data && groupmod -g 32767 www-data
COPY security/php-runtime/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY security/php-runtime/security.ini /usr/local/etc/php/conf.d/zz-security.ini
COPY security/php-runtime/entrypoint.sh /usr/local/bin/security-php-entrypoint
RUN chmod 755 /usr/local/bin/security-php-entrypoint
ENTRYPOINT ["security-php-entrypoint"]
EXPOSE 5000
CMD ["apache2-foreground"]
