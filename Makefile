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
# DB
##################

create-db:
	docker-compose exec -T mysqldb mysql -u"$(MYSQL_USER)" -p"$(MYSQL_PASSWORD)" -e "create database $(MYSQL_DATABASE)"
##################
# Config
##################
app_config:
	sed -i "s/'dsn' => '.*',/'dsn' => 'mysql:host=$(MYSQL_HOST);dbname=$(MYSQL_DATABASE)',/g" $(DB_CONFIG_FILE)
	sed -i "s/'username' => '.*',/'username' => '$(MYSQL_USER)',/g" $(DB_CONFIG_FILE)
	sed -i "s/'password' => '.*',/'password' => '$(MYSQL_ROOT_PASSWORD)',/g" $(DB_CONFIG_FILE)
	sed -i "s/'secret_key' => '.*',/'secret_key' => '$(APP_SECRET_KEY)',/g" $(PARAMS_CONFIG_FILE)
	#sed -i "4i\	'secret_key' => '$(APP_SECRET_KEY)',\n" $(PARAMS_CONFIG_FILE)

##################
# Migrations
##################

migrate:
	docker-compose run --rm php yii migrate

migrate-down:
	docker-compose run --rm php yii migrate/down

migrate-create:
	docker-compose run --rm php yii migrate/create $(name)

##################
# init
##################

init:
	make up
	make app_config
	make lib_update
	make create-db
	make migrate
