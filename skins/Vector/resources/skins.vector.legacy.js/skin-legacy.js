/** @interface MediaWikiPageReadyModule */
const
	collapsibleTabs = require( './collapsibleTabs.js' ),
	/** @type {MediaWikiPageReadyModule} */
	pageReady = require( /** @type {string} */( 'mediawiki.page.ready' ) ),
	vector = require( './vector.js' );

function main() {
	collapsibleTabs.init();
	$( vector.init );
	pageReady.loadSearchModule( 'mediawiki.searchSuggest' );
}

main();
