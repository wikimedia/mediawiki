'use strict';
const assert = require( 'assert' ),
	CreateAccountPage = require( '../pageobjects/createaccount.page' ),
	PreferencesPage = require( '../pageobjects/preferences.page' ),
	UserLoginPage = require( '../pageobjects/userlogin.page' );

describe( 'User', function () {

	var password,
		username;

	beforeEach( function () {
		username = `User-${Math.random().toString()}`;
		password = Math.random().toString();

		browser.reload();

		// disable VisualEditor welcome dialog
		UserLoginPage.open();
		browser.localStorage( 'POST', { key: 've-beta-welcome-dialog', value: '1' } );
	} );

	it( 'should be able to create account', function () {

		// create
		UserLoginPage.login( browser.options.username, browser.options.password );
		CreateAccountPage.createAccount( username, password );

		// check
		assert.equal( CreateAccountPage.heading.getText(), 'Account created' );

	} );

	it( 'should be able to log in', function () {

		// log in
		UserLoginPage.login( browser.options.username, browser.options.password );

		// check
		assert.equal( UserLoginPage.userPage.getText(), browser.options.username );

	} );

	it( 'should be able to change preferences', function () {

		var signature = Math.random().toString();

		// log in
		UserLoginPage.login( browser.options.username, browser.options.password );

		// change
		PreferencesPage.changeSignature( signature );

		// check
		assert.equal( PreferencesPage.existingSignature.getText(), signature );

	} );

} );
