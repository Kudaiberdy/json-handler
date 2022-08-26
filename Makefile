install:
	composer install

autoload:
	composer dump-autoload

validate:
	composer validate

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app tests

nodev-install:
	composer install --no-dev

rsync:
	rsync -e "ssh -i $HOME/.ssh/key -o StrictHostKeyChecking=no" --archive --compress --delete . kudaiberdy@shmidt.icu:/home/kudaiberdy/test

