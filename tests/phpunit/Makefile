.PHONY: help test phpunit coverage warning destructive parser noparser safe databaseless list-groups
.DEFAULT: warning

SHELL = /bin/sh
CONFIG_FILE = ${PWD}/suite.xml
PHP = php
PU = ${PHP} phpunit.php --configuration ${CONFIG_FILE} ${FLAGS}

all test: warning

warning:
	@echo "Run 'make help' to get usage"
	@echo ""
	@echo "WARNING -- some tests are DESTRUCTIVE and will alter your wiki."
	@echo "DO NOT RUN THESE TESTS on a production wiki."
	@echo ""
	@echo "Until the default tests are made non-destructive, you can run"
	@echo "the destructive tests like so:"
	@echo ""
	@echo "    make destructive"
	@echo ""
	@echo "Some tests are expected to be safe, you can run them with"
	@echo ""
	@echo "    make safe"
	@echo ""
	@echo "You are recommended to run the tests with read-only credentials."
	@echo ""
	@echo "If you don't have a database running, you can still run"
	@echo ""
	@echo "    make databaseless"
	@echo ""

destructive: phpunit

phpunit:
	${PU}

tap:
	${PU} --tap

coverage:
	${PU} --coverage-html ../../docs/code-coverage

parser:
	${PU} --group Parser
parserfuzz:
	@echo "******************************************************************"
	@echo "* This WILL kill your computer by eating all memory AND all swap *"
	@echo "*                                                                *"
	@echo "* If you are on a production machine. ABORT NOW!!                *"
	@echo "*  Press control+C to stop                                       *"
	@echo "*                                                                *"
	@echo "******************************************************************"
	${PU} --group Parser,ParserFuzz
noparser:
	${PU} --exclude-group Parser,Broken,ParserFuzz,Stub

safe:
	${PU} --exclude-group Broken,ParserFuzz,Destructive,Stub

databaseless:
	${PU} --exclude-group Broken,ParserFuzz,Destructive,Database,Stub

database:
	${PU} --exclude-group Broken,ParserFuzz,Destructive,Stub --group Database

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
	#   list-groups         List available Tests groups.
	#
	#  Options:
	#   CONFIG_FILE         Path to a PHPUnit configuration file (default: suite.xml)
	#   FLAGS               Additional flags to pass to PHPUnit
	#   PHP                 Path to php
