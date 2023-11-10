app-install:
	@echo "Installation du projet"
	@make app-stop
	@make app-start
	@make generate-env-local
	@make api-install
	@make front-install
	@make project-rights
	@make xdebug-config-file
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
	@echo "xdebug.client_host=$$(cat /etc/resolv.conf | grep nameserver | cut -d ' ' -f 2)" >> .docker/php8/conf.d/50_xdebug.ini
	@echo "    ------ Fichier xdebug généré"