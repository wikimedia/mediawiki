.PHONY: help test phpunit install coverage warning destructive parser noparser safe databaseless list-groups
.DEFAULT: warning

SHELL = /bin/sh
CONFIG_FILE = $(shell pwd)/suite.xml
PHP = php
PU = ${PHP} phpunit.php --configuration ${CONFIG_FILE} ${FLAGS}

all test: warning

warning:
	# Use 'make help' to get usage
	@echo "WARNING -- some tests are DESTRUCTIVE and will alter your wiki."
	@echo "DO NOT RUN THESE TESTS on a production wiki."
	@echo ""
	@echo "Until the default suites are made non-destructive, you can run"
	@echo "the destructive tests like so:"
	@echo "  make destructive"
	@echo ""
	@echo "Some tests are expected to be safe, you can run them with"
	@echo "  make safe"
	@echo ""
	@echo "You are recommended to run them with read-only credentials, though."
	@echo ""
	@echo "If you don't have a database running, you can still run"
	@echo "  make databaseless"
	@echo ""

destructive: phpunit

phpunit:
	${PU}

install:
	php install-phpunit.sh

tap:
	${PU} --tap

coverage:
	${PU} --coverage-html ../../../docs/code-coverage

parser:
	${PU} --group Parser

noparser:
	${PU} --exclude-group Parser,Broken

safe:
	${PU} --exclude-group Broken,Destructive

databaseless:
	${PU} --exclude-group Broken,Destructive,Database

list-groups:
	${PU} --list-groups

help:
	# Usage:
	#   make <target> [OPTION=value]
	#
	# Targets:
	#   phpunit (default)   Run all the tests with phpunit
	#   install             Install PHPUnit from phpunit.de
	#   tap                 Run the tests individually through Test::Harness's prove(1)     
	#   help                You're looking at it!
	#   coverage            Run the tests and generates an HTML code coverage report
	#                       You will need the Xdebug PHP extension for the later.
	#   [no]parser          Skip or only run Parser tests
	#
	#   list-groups         List availabe Tests groups.
	#
	#  Options:
	#   CONFIG_FILE         Path to a PHPUnit configuration file (default: suite.xml)
	#   FLAGS               Additional flags to pass to PHPUnit
	#   PHP                 Path to php
