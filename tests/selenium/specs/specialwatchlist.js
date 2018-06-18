const assert = require( 'assert' ),
	Api = require( 'wdio-mediawiki/Api' ),
	WatchlistPage = require( '../pageobjects/watchlist.page' ),
	WatchablePage = require( '../pageobjects/watchable.page' ),
	LoginPage = require( 'wdio-mediawiki/LoginPage' );

describe( 'Special:Watchlist', function () {
	let username, password;

	function getTestString( prefix = '' ) {
		return prefix + Math.random().toString() + '-öäü-♠♣♥♦';
	}

	before( function () {
		username = getTestString( 'user-' );
		password = getTestString( 'password-' );

		browser.call( function () {
			return Api.createAccount( username, password );
		} );
	} );

	beforeEach( function () {
		browser.deleteCookie();
		LoginPage.login( username, password );
	} );

	it( 'should show page with new edit', function () {
		const title = getTestString( 'Title-' );

		browser.call( function () {
			return Api.edit( title, getTestString() ); // create
		} );
		WatchablePage.watch( title );
		browser.call( function () {
			return Api.edit( title, getTestString() ); // edit
		} );

		WatchlistPage.open();

		assert.strictEqual( WatchlistPage.titles[ 0 ].getText(), title );
	} );

} );
