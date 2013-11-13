#!/usr/bin/env bash
MW_INSTALL_PATH ?= ../..

kss:
	# FIXME: Use more up to date Ruby version
	kss-node resources/mediawiki.ui/ resources/mediawiki.ui/docs -l resources/mediawiki.ui/vector.less
	open ${PWD}/resources/mediawiki.ui/docs/index.html

