'use strict';
var assert = require( 'assert' ),
	CreateAccountPage = require( './pages/createAccountPage' ),
	UserLoginPage = require( './pages/userLoginPage' ),
	UserLogoutPage = require( './pages/userLogoutPage' ),
	PreferencesPage = require( './pages/preferencesPage' );

describe( 'User', function () {

	var password,
		username;

	beforeEach( function () {
		username = 'Selenium' + Math.random().toString();
		password = Math.random().toString();
	} );

	it( 'should be able to create account', function () {

		// create
		CreateAccountPage.createAccount( username, password );

		// check
		assert.equal( CreateAccountPage.heading.getText(), 'Welcome, ' + username + '!' );

	} );

	it( 'should be able to log in', function () {

		// create
		CreateAccountPage.createAccount( username, password );

		// logout
		UserLogoutPage.open();

		// log in
		UserLoginPage.login( username, password );

		// check
		assert.equal( UserLoginPage.userPage.getText(), username );

	} );

	it( 'should be able to change preferences', function () {

		var realName = Math.random().toString();

		// create
		CreateAccountPage.createAccount( username, password );

		// change real name
		PreferencesPage.changeRealName( realName );

		// check
		assert.equal( PreferencesPage.realName.getValue(), realName );

	} );

} );
