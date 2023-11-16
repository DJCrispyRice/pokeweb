app-install:
	@echo "Installation du projet"
	@make app-stop
	@make app-start
	@make generate-env-local
	@make api-install
	@make front-install
	@make project-rights
	@make xdebug-config-file
	@make db-install
	@echo "     ----- PROJET INSTALLE"

app-start:
	@echo "     ----- Lancement du projet"
	@docker compose up -d --build
	@echo "     ----- Projet lancé"

app-stop:
	@echo "     ----- Arrêt du projet"
	@docker compose stop
	@docker compose rm -f api
	@docker compose rm -f front
	@echo "     ----- Projet arrêté"

generate-env-local:
	@echo "     ----- Génération du fichier .env.local"
	@rm -f api/.env.local
	@docker compose exec api bash -c 'echo "DATABASE_USER=$$POSTGRES_USER"' >> api/.env.local
	@docker compose exec api bash -c 'echo "DATABASE_PASSWORD=$$POSTGRES_PASSWORD"' >> api/.env.local
	@docker compose exec api bash -c 'echo "DATABASE_HOST=$$POSTGRES_HOST"' >> api/.env.local
	@docker compose exec api bash -c 'echo "DATABASE_PORT=$$POSTGRES_PORT"' >> api/.env.local
	@docker compose exec api bash -c 'echo "DATABASE_NAME=$$POSTGRES_NAME"' >> api/.env.local
	@echo "    ----- .env.local généré"

api-install:
	@echo "     ----- Installation des packages Composer"
	@docker compose exec -u root api /bin/bash -c "composer install"
	@echo "     ----- Packages installés"

## JS
front-install:
	@echo "     ----- Installation des packages JS"
	@docker compose exec front /bin/sh -c "yarn"
	@echo "     ----- Packages installés"

project-rights:
	@echo "     ----- Application des droits du projet"
	@docker compose exec -u root api chown -R docker: .
	@docker compose exec -u root front chown -R node: .
	@echo "     ----- Droits appliqués"

xdebug-config-file:
	@echo "    -> Création de la configuration xdebug"
	@cp .docker/api/php/custom.xdebug.ini.example .docker/api/php/50_xdebug.ini
	@echo "xdebug.client_host=$$(cat /etc/resolv.conf | grep nameserver | cut -d ' ' -f 2)" >> .docker/api/php/50_xdebug.ini
	@echo "    ------ Fichier xdebug généré"

cs-fixer:
	@echo "     ----- Lancement des correctifs syntaxique PHP"
	@docker compose exec -u docker api php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
	@echo "     ----- Correctifs faits"

## DB related commands
db-drop:
	@echo "     ----- Drop de la database"
	@docker compose exec -u docker api php bin/console doctrine:database:drop --if-exists --force
	@echo "     ----- Done"

db-create:
	@echo "     ----- Création de la database"
	@docker compose exec -u docker api php bin/console doctrine:database:create
	@echo "     ----- Done"

db-diff:
	@echo "     ----- Exécution des migrations"
	@docker compose exec -u docker api php bin/console --no-interaction doctrine:migrations:diff
	@echo "     ----- Done"

db-migrate:
	@echo "     ----- Exécution des migrations"
	@docker compose exec -u docker api php bin/console --no-interaction doctrine:migrations:migrate
	@echo "     ----- Done"

db-install:
	@echo "     ----- Installation de la database"
	@make db-drop
	@make db-create
	@make db-migrate
	@docker compose exec -u docker api php bin/console app:create-types
	@docker compose exec -u docker api php bin/console app:create-pokemons
	@docker compose exec -u docker api php bin/console app:create-attacks
	@echo "     ----- Done"
