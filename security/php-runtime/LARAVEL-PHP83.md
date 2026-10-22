# Laravel / PHP 8.3 migration

The application uses Laravel 12, Carbon 3 and PHP ^8.3. The PHP security release used for validation is 8.3.36. Composer locks production and development dependencies; install production dependencies with `composer install --no-dev --no-scripts` before startup. Never run migrations, seeders, or key generation as part of the runtime rollout.

Laravel's native trusted-proxy middleware replaces Fideloper; existing trusted-proxy settings remain unchanged. Exception handlers accept Throwable. The Laravel helpers package preserves application helper functions removed from core Laravel.

Validation was performed in the patched PHP 8.3.36 runtime, with network disabled, dummy configuration, an in-memory SQLite connection and ephemeral storage. Anonymous root and login/documentation pages returned their expected redirects or HTTP 200. Production-only vendor overlays were also tested against the deployed source snapshots, preserving unrelated deployed code differences. These checks do not substitute for owner validation of authenticated workflows against real data.

`tests/http-smoke.php` requires APP_ENV=testing. Provide only dummy configuration and run inside a network-isolated container; it must never load production credentials. API repositories also include `tests/api-smoke.php` for dummy JWT round trips, SQL compilation with a stubbed schema, and authentication failure responses. These tests perform no database queries or writes.

Writable paths remain storage and bootstrap/cache. Preserve current uploads; the Pharmacy Council web app also uses public/uploads (and potentially uploads relative to its working directory). Replace the vendor directory atomically rather than merging into old dependencies. Keep a private rollback copy, .env, and application data outside images.

The JWT package is upgraded to 2.3 with the current service provider and JWTSubject contract. Its configuration reads JWT_SECRET without a hardcoded fallback, and defaults to HS256. Provision a private random secret of at least 32 bytes before cutover. Changing the signing key invalidates existing tokens; clients must authenticate again. Authentication controller exception responses explicitly return HTTP 401. Existing API-token route guards are unchanged.

Legacy database metadata compatibility: existing MariaDB 10.1 servers lack information_schema.columns.generation_expression. The application registers a lazy MySQL connection subclass that changes only that metadata projection to NULL on MariaDB before 10.2 or MySQL before 5.7. Modern and unrecognized server versions retain Laravel's original SQL. No database connection occurs at provider registration or schema grammar creation. Version boundary, SQL identity, and connection laziness are tested in tests/legacy-mysql-schema.php with fake PDO metadata; no live database is used. This adapter permits column inspection and does not change application queries, schemas or data.
