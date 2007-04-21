mediawiki_version := $(shell  php -r "include('commandLine.inc'); print SpecialVersion::getVersion();")

help:
	@echo "Run 'make test' to run the parser tests."
	@echo "Run 'make doc' to run the phpdoc generation."
	@echo "Run 'make doxydoc' (unsupported doxygen generation)."

test:
	php parserTests.php --quiet

doc:
	php mwdocgen.php -all
	echo 'Doc generation done. Look at ./docs/html/'

doxydoc:
	cd .. && /bin/sed -e "s/MW_VERSION_PLACEHOLDER/$(mediawiki_version)/" maintenance/mwdoxygen.cfg | doxygen -
	echo 'Doc generation done. Look at ./docs/html/'
