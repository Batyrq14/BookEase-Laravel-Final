.PHONY: help build up down restart migrate seed fresh shell psql logs destroy npm-build npm-dev storage-link test

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  make %-18s %s\n", $$1, $$2}'

build: ## First-time setup (run after cloning)
	cp -n .env.example .env || true
	docker compose up -d
	docker compose exec app composer install
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate --force
	docker compose exec app php artisan db:seed --force
	docker compose exec app php artisan storage:link
	docker compose run --rm node sh -c "npm install && npm run build"
	@echo ""
	@echo "Done! http://localhost:8000"
	@echo "  Admin:    admin@example.com    / password"
	@echo "  Provider: provider@example.com / password"
	@echo "  Client:   client@example.com   / password"

up: ## Start containers
	docker compose up -d

down: ## Stop containers
	docker compose down

restart: ## Restart containers
	docker compose restart

logs: ## Tail logs
	docker compose logs -f

destroy: ## Stop and wipe database volume
	docker compose down -v

migrate: ## Run migrations
	docker compose exec app php artisan migrate

seed: ## Seed database
	docker compose exec app php artisan db:seed

fresh: ## Reset database (migrate:fresh + seed)
	docker compose exec app php artisan migrate:fresh --seed

shell: ## Shell into app container
	docker compose exec app bash

psql: ## Open PostgreSQL CLI
	docker compose exec pgsql psql -U bookease -d bookease

npm-build: ## Build frontend assets (production)
	docker compose run --rm node sh -c "npm install && npm run build"

npm-dev: ## Start frontend dev server (hot reload)
	docker compose run --rm --service-ports node sh -c "npm install && npm run dev"

storage-link: ## Create storage symlink
	docker compose exec app php artisan storage:link

test: ## Run test suite
	docker compose exec app php artisan test
