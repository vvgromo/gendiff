install: 
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src bin tests

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src bin tests

test:
	composer run-script phpunit tests