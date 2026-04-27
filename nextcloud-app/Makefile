.PHONY: lint-php format-php clean test-php

lint-php:
	find appinfo lib templates tests -name '*.php' -print0 | xargs -0 -n1 php -l

format-php:
	@echo "Kein PHP-Formatter in diesem Schritt konfiguriert."

clean:
	rm -rf vendor composer.lock

test-php:
	vendor/bin/phpunit -c tests/phpunit.xml
