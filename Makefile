server:
	php -S 0.0.0.0:8000 -t src/www

scratch:
	php scratch/scratch.php

test:
	./vendor/bin/phpunit

install:
	composer install && composer dump-autoload