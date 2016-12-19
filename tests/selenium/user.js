/* global browser */
var assert = require( 'assert' );

describe( 'User', function () {

	it( 'should be able to create account', function () {
		browser.url( '/index.php?title=Special:CreateAccount' );
		assert( browser.isVisible( '#wpCreateaccount' ) );
	} );

	it( 'should be able to log in', function () {
		browser.url( '/index.php?title=Special:UserLogin' );
		assert( browser.isVisible( '#wpLoginAttempt' ) );
	} );

	it( 'should be able to change preferences', function () {
		browser.url( '/index.php?title=Special:UserLogin' );
		browser.setValue( '#wpName1', browser.username );
		browser.setValue( '#wpPassword1', browser.password );
		browser.click( '#wpLoginAttempt' );

		browser.url( '/index.php?title=Special:Preferences' );
		assert( browser.isVisible( '#prefcontrol' ) );
	} );

} );
