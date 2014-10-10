The Makefile, package.json, scripts, styleguide-template, and
mediawiki.ui/styleguide.md files and directories here and in
resources/src/mediawiki.ui/ support the automatic generation
of CSS documentation from the source LESS files using kss for
node.js, https://github.com/kneath/kss

To build and open in your web browser, run:

MEDIAWIKI_LOAD_URL=mediawiki_hostname/w/load.php make kssopen

For example,

MEDIAWIKI_LOAD_URL=1.2.3.4/w/load.php make kssopen

If MediaWiki is running on localhost, you can omit MEDIAWIKI_LOAD_URL.

To rebuild without opening the web browser, run:

MEDIAWIKI_LOAD_URL=mediawiki_hostname/w/load.php make

When modifying styleGuideModules.txt, keep the list in strict alphabetical order (with no extra formatting), so CSS loads in the same order as ResourceLoader's addModuleStyles does; this can affect rendering.
