var assert = require( 'selenium-webdriver/testing/assert' ),
		page = require( '../pages/special-user-login.js' );

require( '../suite.js' );

describe( 'Special:UserLogin', function () {
	var driver;

	beforeEach( function () {
		driver = this.runtime.startBrowser();
		return driver.get( this.wiki.pageURL( page ) );
	} );

	it( 'Displays a login form', function () {
		return assert( driver.isElementPresent( page.loginForm ) ).isTrue();
	} );
} );
