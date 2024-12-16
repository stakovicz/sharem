DOCKER := docker
DOCKER_COMPOSE ?= docker compose

# Alias
CONTAINER_NAME = sharem-php-1
EXEC ?= $(DOCKER) exec -it $(CONTAINER_NAME) bash -c # -it interactive + tty


# Misc
.DEFAULT_GOAL := help


#- —— 📰 ShareM 📰 ——————————————————————————————————————————
help: #- Outputs this help screen
	@grep -hE '(^[a-zA-Z0-9_-]+:.*?#-.*$$)|(^#-)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?#- "}{printf "\033[32m%-25s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m#-/[33m/'


php-cs-fixer:  #- Check Coding standards (php-cs-fixer)
	$(EXEC) "./vendor/bin/php-cs-fixer fix --verbose --diff"

phpstan: php-stan
php-stan:  #- PHP Stan
	$(EXEC) "./vendor/bin/phpstan analyse -c phpstan.neon --memory-limit 1G"

#- —— 🧪 Tests ——————————————————————————————————————————————————————————
tests: tu tm #- Run all tests

tu:  #- Run unit tests (example : TEST_FILE=tests/VideoFactory/ConsumerTest.php make tu)
	$(EXECtty) "phpdbg -qrr ./vendor/bin/phpunit ${TEST_FILE}"

tm:  #- Run mutation tests
	$(EXECtty) "phpdbg -qrr -d memory_limit=256M  ./vendor/bin/infection --threads=8 --min-covered-msi=100 --logger-html=report/mutation-report.html"

#- —— 🎉 Pre-build services —————————————————————————————————————————————————————————
terminal: #- Access to container cli
	@$(DOCKER) exec  -it $(CONTAINER_NAME) bash || echo "❌ No shell found";
