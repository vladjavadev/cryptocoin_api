## Init commit
# Coin api

Prototype realization of coin api

Used a [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,
with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside!

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. clone repository: https://github.com/vladjavadev/cryptocoin_api.git
2. Run `cd coin_api/` to open root directory
3. Run `docker compose build --no-cache` to build fresh images
4. Run `docker compose up -d` to run php-app and database container
5. Run `php scripts/webhook.php` to simulate the process of invoking web hooks.
6. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
7. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Routes
1. [coin list](https://localhost/api/v1/coins)
2. [coin/{coin_id}](https://localhost/api/v1/coin/90)

**Enjoy!**