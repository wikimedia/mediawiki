const Page = require( 'wdio-mediawiki/Page' );

/**
 * @deprecated Use wdio-mediawiki/Page and openTitle() instead.
 */
class LegacyPage extends Page {
	open( path ) {
		browser.url( browser.options.baseUrl + '/index.php?title=' + path );
	}
}

module.exports = LegacyPage;
