PHP ?= '7.2'
UP ?= 1
DOWN ?= 1

docs:
	wget http://apigen.org/apigen.phar
	chmod +x apigen.phar
	php apigen.phar generate --source=src --destination=docs --template-theme=bootstrap
	rm apigen.phar

image:
	docker build -t matthiasmullie/minify .

up:
	docker-compose up -d $(PHP)

down:
	docker-compose stop -t0 $(PHP)

test:
	[ $(UP) -eq 1 ] && make up || true
	$(eval cmd='docker-compose run $(PHP) vendor/bin/phpunit')
	eval $(cmd); status=$$?; [ $(DOWN) -eq 1 ] && make down; exit $$status
