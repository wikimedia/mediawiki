MW_INSTALL_PATH ?= ../..

lsg:
	# FIXME: Use more up to date Ruby version
	kss-node resources/mediawiki.ui/ resources/mediawiki.ui/docs --template resources/docs.template && rm -R resources/mediawiki.ui/docs/public
