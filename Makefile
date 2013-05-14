# Default task
all: install

# Install dependencies
install:
	@composer install --dev

# Run test suite with coverage reports
coverage:
	@./vendor/bin/phpunit --coverage-text --coverage-html ./report
	@echo "\nHTML report available at file:///host/path/to/project/report/index.html"

# Run test suite
tests:
	@./vendor/bin/phpunit
