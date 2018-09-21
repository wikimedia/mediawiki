const assert = require( 'assert' ),
	Api = require( 'wdio-mediawiki/Api' ),
	RecentChangesPage = require( '../pageobjects/recentchanges.page' ),
	Util = require( 'wdio-mediawiki/Util' ),
	RunJobs = require( 'wdio-mediawiki/RunJobs' );

describe( 'Special:RecentChanges', function () {
	let content,
		name;

	beforeEach( function () {
		browser.deleteCookie();
		content = Util.getTestString();
		name = Util.getTestString();
	} );

	it( 'shows page creation', function () {
		browser.call( function () {
			return Api.edit( name, content );
		} );
		RunJobs.run();

		RecentChangesPage.open();

		assert.strictEqual( RecentChangesPage.titles[ 0 ].getText(), name );
	} );

} );
