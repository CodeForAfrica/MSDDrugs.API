#!/bin/sh
set -eu
if [ ! -f /app/public/index.php ]; then
    echo "Private application snapshot is not mounted at /app; refusing to start" >&2
    exit 1
fi
set -a
for env_file in /app/.profile.d/00-global-env.sh /app/.profile.d/01-app-env.sh; do
    if [ -f "$env_file" ]; then . "$env_file"; fi
done
set +a
export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
exec /usr/local/bin/clone3-fallback docker-php-entrypoint "$@"
