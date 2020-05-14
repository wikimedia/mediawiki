'use strict';

const assert = require( 'assert' );
const Api = require( 'wdio-mediawiki/Api' );
const WatchlistPage = require( '../pageobjects/watchlist.page' );
const WatchablePage = require( '../pageobjects/watchable.page' );
const LoginPage = require( 'wdio-mediawiki/LoginPage' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'Special:Watchlist', function () {
	let username, password, bot;

	before( async () => {
		username = Util.getTestString( 'user-' );
		password = Util.getTestString( 'password-' );
		bot = await Api.bot();
		await Api.createAccount( bot, username, password );
	} );

	beforeEach( function () {
		browser.deleteAllCookies();
		LoginPage.login( username, password );
	} );

	it( 'should show page with new edit', function () {
		const title = Util.getTestString( 'Title-' );

		// create
		browser.call( async () => {
			await bot.edit( title, Util.getTestString() );
		} );

		WatchablePage.watch( title );

		// edit
		browser.call( async () => {
			await bot.edit( title, Util.getTestString() );
		} );

		WatchlistPage.open();

		assert.strictEqual( WatchlistPage.titles[ 0 ].getText(), title );
	} );

} );
