MEDIAWIKI_LOAD_URL ?= http://localhost/w/load.php
MEDIAWIKI_UI=${PWD}/../../resources/src/mediawiki.ui

kss: nodecheck
# FIXME: Use more up-to-date Ruby version

# Generates CSS of mediawiki.ui and mediawiki.ui.button using ResourceLoader, then applies it to the
# KSS style guide
	$(eval KSS_RL_TMP := $(shell mktemp /tmp/tmp.XXXXXXXXXX))
	@curl -sG "${MEDIAWIKI_LOAD_URL}?modules=mediawiki.ui|mediawiki.ui.button&only=styles" > $(KSS_RL_TMP)
	@node_modules/.bin/kss-node ${MEDIAWIKI_UI} build --css $(KSS_RL_TMP) -t styleguide-template
	@rm $(KSS_RL_TMP)

kssopen: kss
	@echo Opening the generated style guide...
	@command -v xdg-open >/dev/null 2>&1 || { open build/index.html; exit 0; }
	@xdg-open build/index.html

nodecheck:
	@scripts/nodecheck.sh
