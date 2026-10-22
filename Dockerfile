FROM php:7.4.33-apache-bullseye@sha256:c9d7e608f73832673479770d66aacc8100011ec751d1905ff63fae3fe2e0ca6d
LABEL org.opencontainers.image.source="https://github.com/php/php-src" org.opencontainers.image.revision="712e60f9edfd03f0960fc4fc3c2c375f42b90aa2" security.scope="Targeted CVE-2026-109618 and CVE-2026-109619 backport; PHP 7.4 remains EOL"
ADD https://github.com/php/php-src/archive/712e60f9edfd03f0960fc4fc3c2c375f42b90aa2.tar.gz /tmp/php-src.tar.gz
RUN echo 'c85c8cae6cd97c8688eb0bb2385d2341b74606153d19c3c8688183c3bc760d47  /tmp/php-src.tar.gz' | sha256sum -c - \
 && printf '%s\n' 'deb https://archive.debian.org/debian bullseye main' 'deb https://archive.debian.org/debian-security bullseye-security main' > /etc/apt/sources.list && apt-get -o Acquire::Check-Valid-Until=false update && apt-get install -y --no-install-recommends $PHPIZE_DEPS bison re2c apache2-dev libargon2-dev libsodium-dev libsqlite3-dev libcurl4-openssl-dev libssl-dev libreadline-dev libonig-dev libxml2-dev zlib1g-dev libbz2-dev libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
 && mkdir -p /usr/src/php && tar -xzf /tmp/php-src.tar.gz --strip-components=1 -C /usr/src/php \
 && cd /usr/src/php && ./buildconf --force \
 && ./configure --with-config-file-path=/usr/local/etc/php --with-config-file-scan-dir=/usr/local/etc/php/conf.d --with-mhash --with-pic --enable-ftp --enable-mbstring --enable-mysqlnd --with-password-argon2 --with-sodium=shared --with-pdo-sqlite=/usr --with-sqlite3=/usr --with-curl --with-iconv --with-openssl --with-readline --with-zlib --disable-phpdbg --with-pear --with-libdir=lib/x86_64-linux-gnu --disable-cgi --with-apxs2 --enable-bcmath --with-bz2 --with-mysqli --with-pdo-mysql --with-pdo-pgsql --with-pgsql --with-zip --enable-opcache --enable-sockets --enable-gd --with-jpeg --with-freetype \
 && make -j2 && make install \
 && docker-php-ext-enable opcache \
 && a2enmod rewrite \
 && php -v && php -m \
 && rm -f /tmp/php-src.tar.gz && rm -rf /var/lib/apt/lists/*

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
