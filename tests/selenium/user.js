/* eslint-env mocha, node */
'use strict';
var assert = require( 'assert' ),
	CreateAccountPage = require( './pages/CreateAccountPage' ),
	UserLoginPage = require( './pages/UserLoginPage' ),
	UserLogoutPage = require( './pages/UserLogoutPage' ),
	PreferencesPage = require( './pages/PreferencesPage' );

describe( 'User', function () {

	var password,
		username;

	beforeEach( function () {
		password = Math.random().toString();
		username = Math.random().toString();
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
		PreferencesPage.chageRealName( realName );

		// check
		assert.equal( PreferencesPage.realName.getValue(), realName );

	} );

} );
