.PHONY: install cache reinstall-project deploy-staging build ide-helper database migrate tests
.DEFAULT_GOAL:=help

enter:
	docker exec -it decor.app bash

migrate-fresh:
	docker-compose run --rm --entrypoint "php artisan migrate:fresh --seed" app

reset:
	$(MAKE) migrate-fresh
	rm -rf public/uploads/**/*

scout-import:
	cp .env .env.local
	cp .env.prod .env
	- php artisan scout:import
	cp .env.local .env

migrate:
	docker-compose run --rm --entrypoint "php artisan migrate" app

help: ## Prints help about targets.
	@printf "Usage:             make [\033[34mtarget\033[0m]\n"
	@printf "Default:           \033[34m%s\033[0m\n" $(.DEFAULT_GOAL)
	@printf "Targets:\n"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf " \033[34m%-17s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
