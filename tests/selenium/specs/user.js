'use strict';
const assert = require( 'assert' ),
	CreateAccountPage = require( '../pageobjects/createaccount.page' ),
	PreferencesPage = require( '../pageobjects/preferences.page' ),
	UserLoginPage = require( '../pageobjects/userlogin.page' );

describe( 'User', function () {

	var password,
		username;

	before( function () {
		UserLoginPage.login( browser.options.username, browser.options.password );
		// disable VisualEditor welcome dialog
		browser.localStorage( 'POST', { key: 've-beta-welcome-dialog', value: '1' } );
	} );

	beforeEach( function () {
		username = `User-${Math.random().toString()}`;
		password = Math.random().toString();
	} );

	it( 'should be able to create account', function () {

		// create
		CreateAccountPage.createAccount( username, password );

		// check
		assert.equal( CreateAccountPage.heading.getText(), 'Account created' );

	} );

	it( 'should be able to log in', function () {

		// create
		browser.call( function () {
			return CreateAccountPage.apiCreateAccount( username, password );
		} );

		// log in
		UserLoginPage.login( username, password );

		// check
		assert.equal( UserLoginPage.userPage.getText(), username );

	} );

	it( 'should be able to change preferences', function () {

		var signature = Math.random().toString();

		// create
		browser.call( function () {
			return CreateAccountPage.apiCreateAccount( username, password );
		} );

		// log in
		UserLoginPage.login( username, password );

		// change
		PreferencesPage.changeSignature( signature );

		// check
		assert.equal( PreferencesPage.existingSignature.getText(), signature );

	} );

} );
