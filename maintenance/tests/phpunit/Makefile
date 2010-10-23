.PHONY: help test phpunit install coverage warning destructive parser noparser list-groups
.DEFAULT: warning

SHELL = /bin/sh
CONFIG_FILE = $(shell pwd)/suite.xml
FLAGS = 
PU = php phpunit.php --configuration ${CONFIG_FILE}

all test: warning

warning:
	# Use 'make help' to get usage
	@echo "WARNING -- these tests are DESTRUCTIVE and will alter your wiki."
	@echo "DO NOT RUN THESE TESTS on a production wiki."
	@echo ""
	@echo "Until the default suites are made non-destructive, you can run"
	@echo "the destructive tests like so:"
	@echo ""
	@echo "  make destructive"
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
	${PU} --exclude-group Parser

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
