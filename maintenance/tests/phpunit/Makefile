.PHONY: help test phpunit install

all test: phpunit

phpunit:
	phpunit --configuration suite.xml

install:
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit

tap:
	phpunit --configuration suite.xml --tap

help:
	# Targets:
	#   phpunit (default)   Run all the tests with phpunit
	#   install             Install PHPUnit from phpunit.de
	#   tap                 Run the tests individually through Test::Harness's prove(1)     
	#   help                You're looking at it!
