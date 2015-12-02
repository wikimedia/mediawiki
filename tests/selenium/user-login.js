var assert = require( 'selenium-webdriver/testing/assert' ),
	baseURL = 'http://127.0.0.1:8080/wiki/',
	createAccountPage,
	crypto = require( 'crypto' ),
	webdriver = require( 'selenium-webdriver' ),
	chrome = require( 'selenium-webdriver/chrome' ),
	page = require( './pages/special-user-login.js' ),
	path = require( 'chromedriver' ).path,
	po = require( './lib/page-object.js' ),
	service = new chrome.ServiceBuilder( path ).build(),
	test = require( 'selenium-webdriver/testing' ),
	timeout = 10000;

chrome.setDefaultService( service );

function random( prefix, length ) {
	return prefix + crypto.randomBytes( length || 10 ).toString( 'hex' );
}

test.describe( 'Special:UserLogin', function () {
	var driver;

	this.timeout( timeout );

	beforeEach( function () {
		driver = new webdriver.Builder().withCapabilities( webdriver.Capabilities.chrome() ).build();
		driver.get( page.qualify( baseURL ) );
	} );

	afterEach( function () {
		driver.quit();
	} );

	test.it( 'Displays a login form', function () {
		assert( driver.isElementPresent( page.loginForm ) ).isTrue();
	} );
} );
