/* global browser */
var assert = require( 'assert' );

describe( 'User', function () {

	it( 'should be able to create account', function () {
		browser.url( '/Special:CreateAccount' );
		assert( browser.isVisible( '#wpCreateaccount' ) );
	} );

	it( 'should be able to log in', function () {
		browser.url( '/Special:UserLogin' );
		assert( browser.isVisible( '#wpLoginAttempt' ) );
	} );

	it( 'should be able to change preferences', function () {
		browser.url( '/Special:UserLogin' );
		browser.setValue( '#wpName1', 'Admin' );
		browser.setValue( '#wpPassword1', 'vagrant' );
		browser.click( '#wpLoginAttempt' );

		browser.url( '/Special:Preferences' );
		assert( browser.isVisible( '#prefcontrol' ) );
	} );

} );
