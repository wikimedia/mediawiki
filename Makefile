nodecheck:
	@scripts/nodecheck.sh

jshint: nodecheck
	@node_modules/.bin/jshint resources/* --config .jshintrc

phpunit:
	cd tests/phpunit && php phpunit.php

qunit:
	@scripts/qunit.sh

qunitdebug:
	@scripts/qunit.sh 'MobileFrontend&debug=true'

tests: jshint phpunit qunit
