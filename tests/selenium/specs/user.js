'use strict';
const assert = require( 'assert' ),
	CreateAccountPage = require( '../pageobjects/createaccount.page' ),
	UserLoginPage = require( '../pageobjects/userlogin.page' ),
	PreferencesPage = require( '../pageobjects/preferences.page' );

describe( 'User', function () {

	var password,
		username;

	beforeEach( function () {
		username = `User-${Math.random().toString()}`;
		password = Math.random().toString();
	} );

	it( 'should be able to create account', function () {

		// create
		CreateAccountPage.createAccount( username, password );

		// check
		assert.equal( CreateAccountPage.heading.getText(), `Welcome, ${username}!` );

	} );

	it( 'should be able to log in', function () {

		// create
		CreateAccountPage.apiCreateAccount( username, password );

		// log in
		UserLoginPage.login( username, password );

		// check
		assert.equal( UserLoginPage.userPage.getText(), username );

	} );

	it( 'should be able to change preferences', function () {

		var realName = Math.random().toString();

		// create
		CreateAccountPage.apiCreateAccount( username, password );

		// log in
		UserLoginPage.login( username, password );

		// change
		PreferencesPage.changeRealName( realName );

		// check
		assert.equal( PreferencesPage.realName.getValue(), realName );

	} );

} );
