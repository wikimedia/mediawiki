.PHONY: help test
all test:
	phpunit
install:
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit
help:
	# Options:
	# 	test (default)		Run the unit tests
	#	install			Install PHPUnit from phpunit.de
	#	help			You're looking at it!
