/* global browser */
var assert = require( 'assert' );

describe( 'User', function () {

	afterEach( function () {
		if ( this.currentTest.state === 'failed' ) {
			browser.saveScreenshot('../log/' + this.currentTest.fullTitle() + '.png');
		}
	} );

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
		browser.setValue( '#wpName1', 'Admin' );
		browser.setValue( '#wpPassword1', 'vagrant' );
		browser.saveScreenshot('../log/1.png');
		browser.click( '#wpLoginAttempt' );
		browser.saveScreenshot('../log/2.png');

		browser.url( '/index.php?title=Special:Preferences' );
		browser.saveScreenshot('../log/3.png');
		assert( browser.isVisible( '#prefcontrol' ) );
	} );

} );
