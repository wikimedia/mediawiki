help:
	@echo "Run 'make test' to run the parser tests."
	@echo "Run 'make doc' to run the doxygen generation."
	@echo "Run 'make man' to run the doxygen generation with man pages."

test:
	php tests/parser/parserTests.php --quiet

doc:
	php mwdocgen.php --all
	./mwjsduck-gen
	@echo 'PHP documentation (by Doxygen) in ./docs/html/'
	@echo 'JS documentation (by JSDuck) in ./docs/js/'

man:
	php mwdocgen.php --all --generate-man
	@echo 'Doc generation done. Look at ./docs/html/ and ./docs/man'
	@echo 'You might want to update your MANPATH currently:'
	@echo 'MANPATH: $(MANPATH)'
