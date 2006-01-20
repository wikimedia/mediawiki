.PHONY: help test
all test:
	php RunTests.php
install:
	cvs -z9 -d:pserver:cvsread:@cvs.php.net:/repository/ co -P pear/PHPUnit
	mv pear/PHPUnit .
	rm -rf pear
clean:
	rm -rf PHPUnit pear
help:
	# Options:
	# 	test (default)		Run the unit tests
	#	install			Install PHPUnit from CVS
	#	clean			Remove local PHPUnit install
	#	help			You're looking at it!
