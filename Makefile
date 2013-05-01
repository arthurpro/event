# Default task
all: install

# Install dependencies
install:
	@composer install --dev

# Run test suite
tests:
	@./vendor/bin/phpunit ./test
