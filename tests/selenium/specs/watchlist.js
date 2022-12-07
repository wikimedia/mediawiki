'use strict';

const assert = require( 'assert' );
const Api = require( 'wdio-mediawiki/Api' );
const WatchlistPage = require( '../pageobjects/watchlist.page' );
const WatchablePage = require( '../pageobjects/watchable.page' );
const LoginPage = require( 'wdio-mediawiki/LoginPage' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'Special:Watchlist', function () {
	let bot;

	before( async () => {
		// Default bot is the admin that we also use for viewing via LoginPage.loginAdmin()
		bot = await Api.bot();
	} );

	beforeEach( async function () {
		await LoginPage.loginAdmin();
	} );

	// Skipped on 2022-12-07 because of T324237
	it.skip( 'should show page with new edit @daily', async function () {
		const title = Util.getTestString( 'Title-' );

		// create
		await bot.edit( title, Util.getTestString() );

		await WatchablePage.watch( title );

		// edit
		await bot.edit( title, Util.getTestString() );

		await WatchlistPage.open();

		// We are viewing Special:Watchlist with the same account that made the edit,
		// but by default Special:Watchlist includes both seen and unseen changes, so
		// it'll show up anyway. The title we just edited will be first because the edit
		// was the most recent.
		assert.strictEqual( await WatchlistPage.titles[ 0 ].getText(), title );
	} );

} );
