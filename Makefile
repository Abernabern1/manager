init: down-clear build up
restart: down up

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

down-clear:
	docker-compose down -v  --remove-orphans

migrations-make:
	docker-compose run app-php-cli php bin/console doctrine:migrations:diff --no-interaction

migrations-run:
	docker-compose run app-php-cli php bin/console doctrine:migrations:migrate --no-interaction
