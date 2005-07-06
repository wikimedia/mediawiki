.PHONY: help doc test
help:
	# Run 'make test' to run the parser tests.
	# Run 'make doc' to run the phpdoc generation.

test:
	php parserTests.php

test-light:
	php parserTests.php --color=light

doc:
	php mwdocgen.php --all
