.PHONY: help test phpunit install

all test: phpunit

phpunit:
	phpunit --configuration suite.xml

install:
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit

help:
	# Targets:
	#   phpunit (default)   Run all the tests with phpunit
	#   install             Install PHPUnit from phpunit.de
	#   help                You're looking at it!
