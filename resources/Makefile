# Build static HTML
kss: nodecheck
# FIXME: Use more up-to-date Ruby version
	@node_modules/.bin/kss-node mediawiki.ui mediawiki.ui/docs -l mediawiki.ui/vector.less -t styleguide-template
	@echo Opening the generated style guide...
	@command -v xdg-open >/dev/null 2>&1 || { open ${PWD}/mediawiki.ui/docs/index.html; exit 1; }
	@xdg-open ${PWD}/mediawiki.ui/docs/index.html

# Build HTML fragments for transclusion into Special:StyleGuide.
special: nodecheck
	@node_modules/.bin/kss-node mediawiki.ui mediawiki.ui/docs/fragments -l mediawiki.ui/vector.less -t special-styleguide-template

nodecheck:
	@scripts/nodecheck.sh
