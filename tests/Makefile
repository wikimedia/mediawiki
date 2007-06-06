.PHONY: help test
all test:
	php run-test.php ArticleTest.php
	php run-test.php GlobalTest.php
	php run-test.php DatabaseTest.php
	php run-test.php ImageFunctionsTest.php
	php run-test.php SearchMySQL4Test.php
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
