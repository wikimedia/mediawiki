const assert = require( 'assert' );
const Api = require( 'wdio-mediawiki/Api' );
const WatchlistPage = require( '../pageobjects/watchlist.page' );
const WatchablePage = require( '../pageobjects/watchable.page' );
const LoginPage = require( 'wdio-mediawiki/LoginPage' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'Special:Watchlist', function () {
	let username, password;

	before( function () {
		username = Util.getTestString( 'user-' );
		password = Util.getTestString( 'password-' );

		browser.call( function () {
			return Api.createAccount( username, password );
		} );
	} );

	beforeEach( function () {
		browser.deleteAllCookies();
		LoginPage.login( username, password );
	} );

	it( 'should show page with new edit', function () {
		const title = Util.getTestString( 'Title-' );

		browser.call( function () {
			return Api.edit( title, Util.getTestString() ); // create
		} );
		WatchablePage.watch( title );
		browser.call( function () {
			return Api.edit( title, Util.getTestString() ); // edit
		} );

		WatchlistPage.open();

		assert.strictEqual( WatchlistPage.titles[ 0 ].getText(), title );
	} );

} );
