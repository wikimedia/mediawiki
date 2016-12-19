/* eslint-env mocha, node */
var assert = require( 'assert' ),
	createAccountPage = require( './pages/createAccountPage' ),
	userLoginPage = require( './pages/userLoginPage' ),
	userLogoutPage = require( './pages/userLogoutPage' ),
	preferencesPage = require( './pages/preferencesPage' );

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
		createAccountPage.createAccount( username, password );

		// change real name
		preferencesPage.chageRealName( realName );

		// check
		assert.equal( preferencesPage.realName.getValue(), realName );

	} );

} );
