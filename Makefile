RUN=docker-compose run --rm --no-deps

.PHONY: all configure pull start stop db assets lint test security clean help

all: configure pull start vendor db node_modules assets clean

configure: .env docker-compose.override.yaml ## Install default config files required to start the project

.env:
	@cp $@.dist $@ && echo "File $@ copied."

docker-compose.override.yaml:
	@cp $@.dist $@ && echo "File $@ copied."

pull:
	@docker-compose pull

start: ## Start the containers
	@docker-compose up -d

stop: ## Stop the containers
	@docker-compose down --remove-orphans

vendor: ## Install the composer vendors (set APP_ENV=prod to install only non-dev)
ifeq ($(APP_ENV),prod)
	$(RUN) php composer install --no-dev --no-scripts --no-suggest --no-progress --prefer-dist -o --apcu-autoloader
else
	$(RUN) php composer install --no-suggest --no-progress
endif

node_modules: ## Install the yarn vendors
	@$(RUN) node yarn install --quiet --no-progress

db: ## Set-up the whole database and fixtures (use cached database if any)
	@$(RUN) php sh -c "\
        php bin/console doctrine:database:drop --if-exists --force && \
        php bin/console doctrine:database:create && \
        php bin/console doctrine:schema:update --force && \
        php bin/console hautelook:fixtures:load --append -n"

public/bundles:
	@$(RUN) php php bin/console assets:install --symlink --relative

assets: node_modules public/bundles ## Build assets (set APP_ENV=prod to build as release)
ifeq ($(APP_ENV),prod)
	@$(RUN) node yarn encore production
else
	@$(RUN) node yarn encore dev
endif

check: ## Run syntax check and code validator
	@$(RUN) php sh -c "\
	    php bin/console lint:twig templates/ && \
	    php bin/console lint:yaml --parse-tags config/ && \
	    php vendor/bin/php-cs-fixer fix --dry-run --diff && \
	    php bin/console doctrine:schema:validate"

test: db ## Run the tests after a database reset
	@APP_ENV=test $(RUN) php php vendor/bin/phpunit

security: ## Run the security check
	@$(RUN) php php vendor/bin/security-checker security:check

clean: ## Clean-up the symfony cache and flush redis
	@docker-compose exec php sh -c "\
	    rm -fR var/cache/* && \
	    chmod a+w -fR var/cache"

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-15s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
