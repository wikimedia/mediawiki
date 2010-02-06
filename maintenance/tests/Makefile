# See
# http://lists.wikimedia.org/pipermail/wikitech-l/2010-February/046657.html
# for why prove(1) is the default target and not phpunit(1)

.PHONY: help test
all test: tap

tap:
	prove -e 'phpunit --tap' *Test*.php

phpunit:
	phpunit

install:
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit

help:
	# Options:
	# 	test (default)		Run the tests individually through Test::Harness's prove(1)
	#	phpunit			Run all the tests with phpunit
	#	install			Install PHPUnit from phpunit.de
	#	help			You're looking at it!
