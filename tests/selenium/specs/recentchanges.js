'use strict';

const assert = require( 'assert' );
const Api = require( 'wdio-mediawiki/Api' );
const RecentChangesPage = require( '../pageobjects/recentchanges.page' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'Special:RecentChanges', () => {
	let content, name, bot;

	before( async () => {
		bot = await Api.bot();
	} );

	beforeEach( async () => {
		await browser.deleteAllCookies();
		content = Util.getTestString();
		name = Util.getTestString();
	} );

	it( 'shows page creation', async () => {
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
		await RecentChangesPage.liveUpdates.click();
		await browser.waitUntil(
			async () => ( await RecentChangesPage.titles[ 0 ].getText() ) === name,
			{ timeout: 10000 }
		);
		assert.strictEqual( await RecentChangesPage.titles[ 0 ].getText(), name );
	} );

} );
