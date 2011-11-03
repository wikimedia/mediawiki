help:
	@echo "Run 'make test' to run the parser tests."
	@echo "Run 'make doc' to run the doxygen generation."
	@echo "Run 'make man' to run the doxygen generation with man pages."

test:
	php tests/parserTests.php --quiet

doc:
	php mwdocgen.php --all
	@echo 'Doc generation done. Look at ./docs/html/'

man:
	php mwdocgen.php --all --generate-man
	@echo 'Doc generation done. Look at ./docs/html/ and ./docs/man'
	@echo 'You might want to update your MANPATH currently:'
	@echo 'MANPATH: $(MANPATH)'
