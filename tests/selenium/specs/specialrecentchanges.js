'use strict';

const assert = require( 'assert' );
const Api = require( 'wdio-mediawiki/Api' );
const RecentChangesPage = require( '../pageobjects/recentchanges.page' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'Special:RecentChanges', function () {
	let content, name, bot;

	before( async () => {
		bot = await Api.bot();
	} );

	beforeEach( function () {
		browser.deleteAllCookies();
		content = Util.getTestString();
		name = Util.getTestString();
	} );

	it( 'shows page creation', function () {
		browser.call( async () => {
			await bot.edit( name, content );
		} );

		RecentChangesPage.open();

		assert.strictEqual( RecentChangesPage.titles[ 0 ].getText(), name );
	} );

} );
