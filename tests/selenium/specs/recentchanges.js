import { mwbot } from 'wdio-mediawiki/Api.js';
import BlankPage from 'wdio-mediawiki/BlankPage.js';
import RecentChangesPage from '../pageobjects/recentchanges.page.js';
import { getTestString, isTargetNotWikitext } from 'wdio-mediawiki/Util.js';

describe( 'Special:RecentChanges', () => {
	let content, name, bot;

	before( async () => {
		bot = await mwbot();
	} );

	beforeEach( async () => {
		await browser.deleteAllCookies();
		content = getTestString();
		name = getTestString();
	} );

	it( 'shows page creation', async function () {

		// First try to load a blank page, so the next command works.
		await BlankPage.open();
		// Don't try to run wikitext-specific tests if the test namespace isn't wikitext by default.
		if ( await isTargetNotWikitext( name ) ) {
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
		await RecentChangesPage.liveUpdates.waitForDisplayed();
		await RecentChangesPage.liveUpdates.click();
		await browser.waitUntil(
			async () => ( await RecentChangesPage.titles[ 0 ].getText() ) === name,
			{ timeout: 10000 }
		);
		await expect( RecentChangesPage.titles[ 0 ] ).toHaveText( name );
	} );

} );
