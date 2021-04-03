init: down-clear build up composer-install wait-for-mysql migrations-run
restart: down up

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

down-clear:
	docker-compose down -v  --remove-orphans

composer-install:
	docker-compose run app-php-cli composer install

wait-for-mysql:
	until docker-compose exec app-mysql mysqladmin ping --silent; do sleep 1; done; sleep 15

migrations-make:
	docker-compose run app-php-cli php bin/console doctrine:migrations:diff --no-interaction

migrations-run:
	docker-compose run app-php-cli php bin/console doctrine:migrations:migrate --no-interaction