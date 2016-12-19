/* global browser */
var assert = require( 'assert' );

describe( 'Page', function () {

	it( 'should be created', function () {
		browser.url( '/index.php?title=Does_not_exist' );
		browser.saveScreenshot('../log/should be created.png');
		assert( browser.isVisible( 'li#ca-edit a' ) );
	} );

	it( 'should be edited', function () {
		browser.url( '/index.php?title=Main_Page' );
		browser.saveScreenshot('../log/should be edited.png');
		assert( browser.isVisible( 'li#ca-edit a' ) );
	} );

	it( 'should have history', function () {
		browser.url( '/index.php?title=Main_Page' );
		browser.saveScreenshot('../log/should have history.png');
		assert( browser.isVisible( 'li#ca-history a' ) );
	} );

} );
