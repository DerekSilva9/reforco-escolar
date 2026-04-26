#!/usr/bin/env bash

# Makefile para Docker - Reforço Escolar

.PHONY: help build up down logs stop restart shell artisan composer npm

help: ## Exibe ajuda dos comandos disponíveis
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build das imagens Docker
	docker-compose build

up: ## Iniciar containers
	docker-compose up -d
	@echo "✅ Containers iniciados!"
	@echo "🌐 Acesse: http://localhost"
	@echo "📧 MailHog: http://localhost:8025"

down: ## Parar containers
	docker-compose down

logs: ## Ver logs da aplicação
	docker-compose logs -f app

logs-nginx: ## Ver logs do Nginx
	docker-compose logs -f nginx

logs-db: ## Ver logs do banco de dados
	docker-compose logs -f db

stop: ## Parar containers (mantém dados)
	docker-compose stop

restart: ## Reiniciar containers
	docker-compose restart

shell: ## Acessar shell do container da aplicação
	docker-compose exec app bash

artisan: ## Executar comando artisan (use: make artisan cmd="migrate")
	docker-compose exec app php artisan $(cmd)

composer: ## Executar comando composer (use: make composer cmd="install")
	docker-compose exec app composer $(cmd)

npm: ## Executar comando npm (use: make npm cmd="install")
	docker-compose exec app npm $(cmd)

migrate: ## Executar migrations
	docker-compose exec app php artisan migrate

seed: ## Executar seeders
	docker-compose exec app php artisan db:seed

cache-clear: ## Limpar cache
	docker-compose exec app php artisan cache:clear

config-cache: ## Fazer cache de config
	docker-compose exec app php artisan config:cache

view-cache: ## Fazer cache de views
	docker-compose exec app php artisan view:cache

test: ## Executar testes
	docker-compose exec app composer test

tinker: ## Acessar tinker (REPL do Laravel)
	docker-compose exec app php artisan tinker

mysql: ## Acessar MySQL
	docker-compose exec db mysql -u laravel -p reforco_escolar

redis: ## Acessar Redis CLI
	docker-compose exec redis redis-cli

ps: ## Ver status dos containers
	docker-compose ps

setup: up migrate ## Setup completo (up + migrate)
	@echo "✅ Setup concluído!"

rebuild: down build up ## Rebuild completo
	@echo "✅ Rebuild concluído!"

clean: down ## Limpar tudo (remove containers e volumes)
	docker-compose down -v
	@echo "✅ Limpeza concluída!"

install-deps: ## Instalar todas as dependências
	docker-compose exec app composer install
	docker-compose exec app npm install
	docker-compose exec app npm run build

dev-assets: ## Build assets em desenvolvimento
	docker-compose exec app npm run dev

prod-assets: ## Build assets para produção
	docker-compose exec app npm run build

backup-db: ## Fazer backup do banco de dados
	docker-compose exec db mysqldump -u laravel -p reforco_escolar > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "✅ Backup salvo em backup_*.sql"

restore-db: ## Restaurar banco de dados (use: make restore-db file="backup.sql")
	docker-compose exec -T db mysql -u laravel -p reforco_escolar < $(file)
	@echo "✅ Banco restaurado de $(file)"

.DEFAULT_GOAL := help
