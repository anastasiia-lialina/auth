include .env
export

DB_CONFIG_FILE = config/db.php
PARAMS_CONFIG_FILE = config/params.php

##################
# Docker compose
##################

build:
	docker-compose build

start:
	docker-compose start

stop:
	docker-compose stop

up:
	docker-compose up -d --remove-orphans

down:
	docker-compose down

restart: stop start
rebuild: down build up

dc_ps:
	docker-compose ps

dc_logs:
	docker-compose logs -f

dc_down:
	docker-compose down -v --rmi=all --remove-orphans

dc_restart:
	make dc_stop dc_start

php_exec:
	docker-compose exec php sh


##################
# App
##################

lib_update:
	docker-compose run --rm php composer update --prefer-dist

app_install:
	docker-compose run --rm php composer install

##################
# Config
##################
app_config:
	sed -i "s/'dsn' => '.*',/'dsn' => 'mysql:host=$(MYSQL_HOST);dbname=$(MYSQL_DATABASE)',/g" $(DB_CONFIG_FILE)
	sed -i "s/'username' => '.*',/'username' => '$(MYSQL_USER)',/g" $(DB_CONFIG_FILE)
	sed -i "s/'password' => '.*',/'password' => '$(MYSQL_ROOT_PASSWORD)',/g" $(DB_CONFIG_FILE)
	sed -i "4i\	'secret_key' => '$(APP_SECRET_KEY)',\n" $(PARAMS_CONFIG_FILE)

install:
	make app_config make