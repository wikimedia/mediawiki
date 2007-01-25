#
# This Makefile is used to test some MediaWiki functions. If you
# want to install MediaWiki, point your browser to ./config/
#
test: Test.php
	prove -r t

verbose:
	prove -v -r t | egrep -v '^ok'
