var assert = require( 'selenium-webdriver/testing/assert' ),
	env = require( '../lib/environment.js' ),
	page = require( '../pages/special-user-login.js' ),
	po = require( '../lib/page-object.js' ),
	mw;

describe( 'Special:UserLogin', function () {
	var driver;

	before(function (done) {
		mw = new env.Environment();
		mw.init(done);
	});

	beforeEach( function () {
		driver = mw.startBrowser();
		driver.get( mw.pageURL( page ) );
	} );

	afterEach( function () {
		driver.quit();
	} );

	it( 'Displays a login form', function () {
		return assert( driver.isElementPresent( page.loginForm ) ).isTrue();
	} );
} );
