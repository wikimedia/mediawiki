'use strict';
const assert = require( 'assert' ),
	CreateAccountPage = require( '../pageobjects/createaccount.page' ),
	PreferencesPage = require( '../pageobjects/preferences.page' ),
	UserLoginPage = require( '../pageobjects/userlogin.page' ),
	EditPage = require( '../pageobjects/edit.page' );

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
		browser.call( function () {
			return CreateAccountPage.apiCreateAccount( username, password );
		} );

		// log in
		UserLoginPage.login( username, password );

		// check
		assert.equal( UserLoginPage.userPage.getText(), username );

	} );

	it( 'should be able to change preferences', function () {

		var realName = Math.random().toString();

		// create
		browser.call( function () {
			return CreateAccountPage.apiCreateAccount( username, password );
		} );

		// log in
		UserLoginPage.login( username, password );

		// change
		PreferencesPage.changeRealName( realName );

		// check
		assert.equal( PreferencesPage.realName.getValue(), realName );

	} );

	it( 'should be able to view new message banner', function () {

		// create user
		browser.call( function () {
			return CreateAccountPage.apiCreateAccount( username, password );
		} );

		// create talk page with content
		browser.call( function () {
			return EditPage.apiEdit( 'User_talk:' + username, Math.random().toString() );
		} );

		// log in
		UserLoginPage.login( username, password );

		// check
		assert.equal( UserLoginPage.usermessage.getText(), 'You have a new message (last change).' );

	} );

} );
