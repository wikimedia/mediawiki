const assert = require( 'assert' ),
	CreateAccountPage = require( '../pageobjects/createaccount.page' ),
	PreferencesPage = require( '../pageobjects/preferences.page' ),
	UserLoginPage = require( 'wdio-mediawiki/LoginPage' ),
	Api = require( 'wdio-mediawiki/Api' ),
	Util = require( 'wdio-mediawiki/Util' );

describe( 'User', function () {
	var password,
		username;

	before( function () {
		// disable VisualEditor welcome dialog
		UserLoginPage.open();
		browser.localStorage( 'POST', { key: 've-beta-welcome-dialog', value: '1' } );
	} );

	beforeEach( function () {
		browser.deleteCookie();
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
