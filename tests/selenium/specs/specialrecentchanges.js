const assert = require( 'assert' ),
	Api = require( 'wdio-mediawiki/Api' ),
	RecentChangesPage = require( '../pageobjects/recentchanges.page' );

describe( 'Special:RecentChanges', function () {
	let content,
		name;

	function getTestString() {
		return Math.random().toString() + '-öäü-♠♣♥♦';
	}

	beforeEach( function () {
		browser.deleteCookie();
		content = getTestString();
		name = getTestString();
	} );

	it( 'shows page creation', function () {
		browser.call( function () {
			return Api.edit( name, content );
		} );

		RecentChangesPage.open();

		assert.strictEqual( RecentChangesPage.titles[ 0 ].getText(), name );
	} );

} );
