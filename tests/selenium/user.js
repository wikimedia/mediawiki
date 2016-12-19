/* eslint-env mocha, node */
/* global browser */
var assert = require( 'assert' ),
	createAccountPage = require( './pages/createAccountPage' ),
	userLoginPage = require( './pages/userLoginPage' ),
	userLogoutPage = require( './pages/userLogoutPage' );

describe( 'User', function () {

	var password,
		username;

	beforeEach( function () {
		password = Math.random().toString();
		username = Math.random().toString();
	} );

	it( 'should be able to create account', function () {

		// create
		createAccountPage.createAccount( username, password );

		// check
		assert.equal( createAccountPage.heading.getText(), 'Welcome, ' + username + '!' );

	} );

	it( 'should be able to log in', function () {

		// create
		createAccountPage.createAccount( username, password );

		// logout
		userLogoutPage.open();

		// log in
		userLoginPage.login( username, password );

		// check
		assert.equal( userLoginPage.userPage.getText(), username );

	} );

	it( 'should be able to change preferences', function () {

		var realName = Math.random().toString();

		// create
		browser.url( '/index.php?title=' + 'Special:CreateAccount' );
		browser.setValue( '#wpName2', username );
		browser.setValue( '#wpPassword2', password );
		browser.setValue( '#wpRetype', password );
		browser.click( '#wpCreateaccount' );

		// change real name
		browser.url( '/index.php?title=' + 'Special:Preferences' );
		browser.setValue( '#mw-input-wprealname', realName );
		browser.click( '#prefcontrol' );

		// check
		assert.equal( browser.getValue( '#mw-input-wprealname' ), realName );

	} );

} );
