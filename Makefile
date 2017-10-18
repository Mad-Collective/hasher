all: test coverage

test:
	vendor/bin/phpunit

coverage:
	vendor/bin/phpunit --coverage-text=coverage.txt
	@cat coverage.txt
	@rm coverage.txt

doc:
	vendor/bin/phpdoc-md generate src
