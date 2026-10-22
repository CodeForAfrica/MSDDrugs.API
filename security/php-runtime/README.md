# Interim PHP incident runtime

This codifies the manual AWS incident mitigation deployed on 22 October 2026. It is a **runtime-only** image: no repository application code, credentials or installed dependencies are copied into it. The build context is allowlisted accordingly. This Dockerfile also changes Dokku builds from the legacy PHP buildpack to Dockerfile mode; review the mount prerequisites before deploying.

The image builds official PHP source commit `712e60f9edfd03f0960fc4fc3c2c375f42b90aa2` and verifies archive SHA256 `c85c8cae6cd97c8688eb0bb2385d2341b74606153d19c3c8688183c3bc760d47`. The pinned PHP Apache base and signed Debian archive repositories reproduce the tested runtime. It reports `7.4.34-dev`, upstream's branch identifier, **not a supported release**. This targets CVE-2026-109618 and CVE-2026-109619 only. PHP7.4, the base OS and the old framework still need modernization or retirement.

## Production prerequisites

Keep application code, installed vendor dependencies, private configuration and writable state in the existing root-protected host snapshot `/var/lib/dokku/security-php-20261022/<dokku-app>`. Mount it at `/app`, and the saved session directory at `/tmp`. Startup refuses to run without the application entry point. Never put `.env` or production credentials into this repository or its image.

The launcher loads the existing Dokku `.profile.d/00-global-env.sh` and `01-app-env.sh`, retaining database configuration. It then selects the patched executable PATH. Apache workers retain UID/GID32767; keep writable storage/cache/upload directories owned appropriately. Apache serves ports80/5000, honours existing application rewrite files and denies dotfiles and private CodeIgniter directories.

Build on a dedicated modern amd64 builder (or an emulation-capable modern builder), not these production EC2 hosts: `docker build --platform linux/amd64 -t php-incident-runtime .`. Transfer the resulting image with `docker save`/`docker load`, then tag/deploy it through Dokku with the existing private mounts and service links. The old production Docker/Dokku versions are a separate modernization task.

Do not deploy this image without the private `/app` mount. Review existing database/search Docker links, nginx upstreams and port mappings. No Composer update, migration, queue, scheduler or webhook-registration command is included.

**Source deployment limitation:** mounting `/app` pins source/config to that private snapshot. Merging this PR or rebuilding an image does not update mounted application code. Reconcile this override with the normal deployment pipeline before the next routine source/config release. This is a reproducible incident measure, not the final application deployment architecture.

## Validation and rollback

The exact PHP source build passed CLI and actual Apache SAPI/module checks. Upstream suite:221pass,18skip,two expected failures,zero unexpected failures. Incident application checks passed35 Laravel dependency tests and13 CodeIgniter safe endpoints. Real-configuration candidate and nginx smoke tests passed before/after manual cutover. Full business-flow acceptance and load testing remain owner work; repository HEAD may differ from deployed source.

On the affected host, `sudo dokku tags:deploy <dokku-app> pre-security-20261022` restores the saved previous runtime. Keep the snapshot and session mounts; they include the original buildpack files. Recheck nginx and writable state. Rollback restores vulnerable PHP and is an emergency availability option. NHIF has a separate dependent medicine-prices alias whose upstream must also be refreshed after redeployment/rollback.

Do not invoke the M-Wash bot's `index.php` under CLI: that branch modifies its Telegram webhook. Use an empty HTTP GET for its smoke test. Do not send valid SMPP/message payloads during testing.

## Source provenance

- `tanzania-htools-tz-msd-drug-tool-api`: 981 source files identical; 0 differ from current repository HEAD.

Tracking: https://github.com/CodeForAfrica/infrastructure-as-code/issues/151
