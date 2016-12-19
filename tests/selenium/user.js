/* global browser */
var assert = require( 'assert' );

describe( 'User', function () {

	it( 'should be able to create account', function () {
		browser.url( '/index.php?title=Special:CreateAccount' );
		browser.saveScreenshot('../log/should be able to create account.png');
		assert( browser.isVisible( '#wpCreateaccount' ) );
	} );

	it( 'should be able to log in', function () {
		browser.url( '/index.php?title=Special:UserLogin' );
		browser.saveScreenshot('../log/should be able to log in.png');
		assert( browser.isVisible( '#wpLoginAttempt' ) );
	} );

	it( 'should be able to change preferences', function () {
		browser.url( '/index.php?title=Special:UserLogin' );
		browser.setValue( '#wpName1', 'Admin' );
		browser.setValue( '#wpPassword1', 'vagrant' );
		browser.click( '#wpLoginAttempt' );

		browser.url( '/index.php?title=Special:Preferences' );
		browser.saveScreenshot('../log/should be able to change preferences.png');
		assert( browser.isVisible( '#prefcontrol' ) );
	} );

} );
