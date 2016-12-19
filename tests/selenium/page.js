/* global browser */
var assert = require( 'assert' );

describe( 'Page', function () {

	it( 'should be created', function () {
		browser.url( '/index.php?title=Does_not_exist' );
		assert( browser.isVisible( 'li#ca-edit a' ) );
	} );

	it( 'should be edited', function () {
		browser.url( '/index.php?title=Main_Page' );
		assert( browser.isVisible( 'li#ca-edit a' ) );
	} );

	it( 'should have history', function () {
		browser.url( '/index.php?title=Main_Page' );
		assert( browser.isVisible( 'li#ca-history a' ) );
	} );

} );
