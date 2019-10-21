const assert = require( 'assert' );
const CreateAccountPage = require( '../pageobjects/createaccount.page' );
const PreferencesPage = require( '../pageobjects/preferences.page' );
const UserLoginPage = require( 'wdio-mediawiki/LoginPage' );
const Api = require( 'wdio-mediawiki/Api' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'User', function () {
	let password, username;

	beforeEach( function () {
		browser.deleteAllCookies();
		username = Util.getTestString( 'User-' );
		password = Util.getTestString();
	} );

	it( 'should be able to create account', function () {
		// create
		CreateAccountPage.createAccount( username, password );

		// check
		assert.strictEqual( CreateAccountPage.heading.getText(), `Welcome, ${username}!` );
	} );

	it( 'should be able to log in @daily', function () {
		// create
		browser.call( function () {
			return Api.createAccount( username, password );
		} );

		// log in
		UserLoginPage.login( username, password );

		// check
		assert.strictEqual( UserLoginPage.userPage.getText(), username );
	} );

	// Disabled due to flakiness (T199446)
	it.skip( 'should be able to change preferences', function () {
		var realName = Util.getTestString();

		// create
		browser.call( function () {
			return Api.createAccount( username, password );
		} );

		// log in
		UserLoginPage.login( username, password );

		// change
		PreferencesPage.changeRealName( realName );

		// check
		assert.strictEqual( PreferencesPage.realName.getValue(), realName );
	} );
} );
