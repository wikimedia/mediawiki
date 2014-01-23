MEDIAWIKI_LOAD_URL ?= http://localhost/w/load.php

kss: nodecheck
# FIXME: Use more up-to-date Ruby version

# Generates CSS of mediawiki.ui and mediawiki.ui.button using ResourceLoader, then applies it to the
# KSS style guide
	$(eval KSS_RL_TMP := $(shell mktemp /tmp/tmp.XXXXXXXXXX))
	@curl -sG "${MEDIAWIKI_LOAD_URL}?modules=mediawiki.ui|mediawiki.ui.button&only=styles" > $(KSS_RL_TMP)
	@node_modules/.bin/kss-node mediawiki.ui mediawiki.ui/docs --css $(KSS_RL_TMP) -t styleguide-template
	@echo Opening the generated style guide...
	@command -v xdg-open >/dev/null 2>&1 || { open ${PWD}/mediawiki.ui/docs/index.html; exit 1; }
	@xdg-open ${PWD}/mediawiki.ui/docs/index.html
	@rm $(KSS_RL_TMP)

nodecheck:
	@scripts/nodecheck.sh
