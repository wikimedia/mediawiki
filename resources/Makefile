#!/bin/bash
MW_INSTALL_PATH ?= ../..

nodecheck:
	@scripts/nodecheck.sh

kss: nodecheck
	# FIXME: Use more up to date Ruby version
	kss-node mediawiki.ui mediawiki.ui/docs -l mediawiki.ui/vector.less -t styleguide-template
	# Open the resulting style guide (try using xdg-open or open):
	command -v xdg-open >/dev/null 2>&1 || { open ${PWD}/mediawiki.ui/docs/index.html; exit 1; }
	xdg-open ${PWD}/mediawiki.ui/docs/index.html
