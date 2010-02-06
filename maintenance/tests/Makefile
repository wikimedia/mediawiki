.PHONY: help test
all test:
	phpunit

tap:
	prove -e 'phpunit --tap' *Test*.php

install:
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit

help:
	# Options:
	# 	test (default)		Run the unit tests
	#	tap			Run the tests individually through Test::Harness's prove(1)
	#	install			Install PHPUnit from phpunit.de
	#	help			You're looking at it!
