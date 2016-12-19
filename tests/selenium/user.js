/* eslint-env mocha, node */
/* global browser */
var assert = require( 'assert' );

describe( 'User', function () {

	var password,
		username;

	beforeEach( function () {
		password = Math.random().toString();
		username = Math.random().toString();
	} );

	it( 'should be able to create account', function () {

		// create
		browser.url( '/index.php?title=' + 'Special:CreateAccount' );
		browser.setValue( '#wpName2', username );
		browser.setValue( '#wpPassword2', password );
		browser.setValue( '#wpRetype', password );
		browser.click( '#wpCreateaccount' );

		// check
		assert.equal( browser.getText( '#firstHeading' ), 'Welcome, ' + username + '!' );

	} );

	it( 'should be able to log in', function () {

		// create
		browser.url( '/index.php?title=' + 'Special:CreateAccount' );
		browser.setValue( '#wpName2', username );
		browser.setValue( '#wpPassword2', password );
		browser.setValue( '#wpRetype', password );
		browser.click( '#wpCreateaccount' );

		// logout
		browser.url( '/index.php?title=' + 'Special:UserLogout' );

		// log in
		browser.url( '/index.php?title=' + 'Special:UserLogin' );
		browser.setValue( '#wpName1', username );
		browser.setValue( '#wpPassword1', password );
		browser.setValue( '#wpPassword1', password );
		browser.click( '#wpLoginAttempt' );

		// check
		assert.equal( browser.getText( '#pt-userpage' ), username );

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
