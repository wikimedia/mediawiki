kss: nodecheck
# FIXME: Use more up-to-date Ruby version
	@node_modules/.bin/kss-node mediawiki.ui mediawiki.ui/docs -l mediawiki.ui/vector.less -t styleguide-template
	@echo Opening the generated style guide...
	@command -v xdg-open >/dev/null 2>&1 || { open ${PWD}/mediawiki.ui/docs/index.html; exit 1; }
	@xdg-open ${PWD}/mediawiki.ui/docs/index.html

nodecheck:
	@scripts/nodecheck.sh
