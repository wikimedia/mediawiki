MEDIAWIKI_LOAD_URL ?= http://localhost/w/load.php

kss: kssnodecheck
# Generates CSS of mediawiki.ui and mediawiki.ui.button using ResourceLoader, then applies it to the
# KSS style guide
	$(eval KSS_RL_TMP := $(shell mktemp /tmp/tmp.XXXXXXXXXX))
# Keep module names in strict alphabetical order, so CSS loads in the same order as ResourceLoader's addModuleStyles does; this can affect rendering.
# See OutputPage::makeResourceLoaderLink.
	@curl -sG "${MEDIAWIKI_LOAD_URL}?modules=mediawiki.legacy.commonPrint|mediawiki.legacy.shared|mediawiki.ui|mediawiki.ui.anchor|mediawiki.ui.button|mediawiki.ui.checkbox|mediawiki.ui.input&only=styles" > $(KSS_RL_TMP)
	@node_modules/.bin/kss-node ../../resources/src/mediawiki.ui static/ --css $(KSS_RL_TMP) -t styleguide-template
	@rm $(KSS_RL_TMP)

kssopen: kss
	@echo Opening the generated style guide...
	@command -v xdg-open >/dev/null 2>&1 || { open ${PWD}/static/index.html; exit 0; }
	@xdg-open ${PWD}/static/index.html

kssnodecheck:
	@scripts/kss-node-check.sh
