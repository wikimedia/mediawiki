#
# This Makefile is used to test some MediaWiki functions. If you
# want to install MediaWiki, point your browser to ./config/
#

# Configuration:
PROVE_BIN="prove"

# Describe our tests:
BASE_TEST=$(wildcard t/*.t)
INCLUDES_TESTS=$(wildcard t/inc/*t)

# Build groups:
ALL_TESTS=$(BASE_TEST) $(INCLUDES_TESTS)

test: t/Test.php
	$(PROVE_BIN) $(ALL_TESTS)

fast: t/Test.php
	$(PROVE_BIN) $(ALL_TESTS)

verbose: t/Test.php
	$(PROVE_BIN) -v $(ALL_TESTS) | egrep -v '^ok'
