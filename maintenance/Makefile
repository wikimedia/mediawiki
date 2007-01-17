.PHONY: help test test-light
help:
	# Run 'make test' to run the parser tests.
	# Run 'make doc' to run the phpdoc generation.
	# Run 'make doxydoc' (unsupported doxygen generation).

test:
	php parserTests.php

doc:
	php mwdocgen.php -all
	echo 'Doc generation done. Look at ./docs/html/'

doxydoc:
	cd .. && doxygen maintenance/mwdoxygen.cfg
	echo 'Doc generation done. Look at ./docs/html/'

