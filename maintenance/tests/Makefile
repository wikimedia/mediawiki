# If you have problems with excessive memory usage, use the "tap" or "separate" targets.

TEST_FILES=$(shell php -r 'include( "./TestFileList.php" ); echo implode( " ", $$testFiles );')
TEST_FILE_TARGETS=$(subst .php,.target,$(TEST_FILES))

.PHONY: help test phpunit tap separate install $(TEST_FILE_TARGETS)

all test: phpunit

phpunit:
	php phpunit

tap:
	prove -e 'php phpunit --tap' *Test*.php

separate: $(TEST_FILE_TARGETS)

# Need --tap because without it, the target specification doesn't work
$(TEST_FILE_TARGETS) : %.target : %.php
	php phpunit --tap $<

install:
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit

help:
	# Targets:
	#   phpunit (default)   Run all the tests with phpunit
	#   separate            Run each test file in a separate process
	#   tap                 Run the tests individually through Test::Harness's prove(1)
	#   install             Install PHPUnit from phpunit.de
	#   help                You're looking at it!
