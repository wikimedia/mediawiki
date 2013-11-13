#!/usr/bin/env bash
MW_INSTALL_PATH ?= ../..

kss:
	# FIXME: Use more up to date Ruby version
	kss-node mediawiki.ui docs -l mediawiki.ui/vector.less
	open ${PWD}/docs/index.html
