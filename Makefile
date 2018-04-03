.PHONY: up

test:
	./vendor/bin/phpunit --coverage-html=coverage --colors
