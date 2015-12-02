var assert = require( 'selenium-webdriver/testing/assert' ),
	env = require( './environments.js' ).current,
	page = require( './pages/special-user-login.js' ),
	po = require( './lib/page-object.js' );

describe( 'Special:UserLogin', function () {
	var driver;

	beforeEach( function () {
		driver = env.startBrowser();
		driver.get( env.pageURL( page ) );
	} );

	afterEach( function () {
		driver.quit();
	} );

	it( 'Displays a login form', function () {
		return assert( driver.isElementPresent( page.loginForm ) ).isTrue();
	} );
} );
