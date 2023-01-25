SHELL := /bin/bash

tests:
	symfony console doctrine:database:drop --force --env=test || true
	symfony console doctrine:database:create --env=test
	symfony console doctrine:migrations:migrate -n --env=test
	symfony console doctrine:fixtures:load -n --env=test
	symfony php -d xdebug.mode=coverage bin/phpunit $@
.PHONY: tests

fix:
	symfony php vendor/bin/phpstan analyse -l 1 src tests
	symfony php vendor/bin/php-cs-fixer fix src
	symfony php vendor/bin/php-cs-fixer fix tests
	symfony php vendor/bin/phpcs -qp
	symfony php vendor/bin/phpcbf -qp
.PHONY: fix
