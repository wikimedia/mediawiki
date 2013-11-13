MW_INSTALL_PATH ?= ../..

lsg:
	# FIXME: Use more up to date Ruby version
	kss-node resources/mediawiki.ui/ resources/mediawiki.ui/docs --less resources/mediawiki.ui/default.less --template resources/docs.template
