const assert = require( 'assert' ),
	Api = require( 'wdio-mediawiki/Api' ),
	WatchlistPage = require( '../pageobjects/watchlist.page' ),
	WatchablePage = require( '../pageobjects/watchable.page' ),
	LoginPage = require( 'wdio-mediawiki/LoginPage' ),
	Util = require( 'wdio-mediawiki/Util' );

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
		browser.deleteCookie();
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
