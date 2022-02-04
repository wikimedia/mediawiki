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

	beforeEach( async function () {
		await browser.deleteAllCookies();
		content = Util.getTestString();
		name = Util.getTestString();
	} );

	it( 'shows page creation', async function () {
		// Don't try to run wikitext-specific tests if the test namespace isn't wikitext by default.
		if ( await Util.isTargetNotWikitext( name ) ) {
			this.skip();
		}

		await bot.edit( name, content );
		await browser.waitUntil( async () => {
			const result = await bot.request( {
				action: 'query',
				list: 'recentchanges',
				rctitle: name
			} );
			return result.query.recentchanges.length > 0;
		} );

		await RecentChangesPage.open();

		assert.strictEqual( await RecentChangesPage.titles[ 0 ].getText(), name );
	} );

} );
