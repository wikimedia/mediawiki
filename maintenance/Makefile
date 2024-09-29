help:
	@echo "Run 'make test' to run the parser tests."
	@echo "Run 'make doc' to run the doxygen generation."

test:
	php tests/parser/parserTests.php --quiet

doc:
	php mwdocgen.php --all
	npm run doc
	@echo 'PHP documentation (by Doxygen) in ./docs/html/'
	@echo 'JS documentation (by JSDoc) in ./docs/js/'
