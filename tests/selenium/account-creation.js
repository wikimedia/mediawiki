var assert = require( 'selenium-webdriver/testing/assert' ),
	baseURL = 'http://127.0.0.1:8080/wiki/',
	createAccountPage,
	crypto = require( 'crypto' ),
	env = require( './environments.js' ).current,
	webdriver = require( 'selenium-webdriver' ),
	chrome = require( 'selenium-webdriver/chrome' ),
	page = require( './pages/special-create-account.js' ),
	path = require( 'chromedriver' ).path,
	po = require( './lib/page-object.js' ),
	service = new chrome.ServiceBuilder( path ).build(),
	timeout = 10000;

chrome.setDefaultService( service );

function random( prefix, length ) {
	return prefix + crypto.randomBytes( length || 10 ).toString( 'hex' );
}

describe( 'Special:CreateAccount', function () {
	var driver;

	this.timeout( timeout );

	beforeEach( function () {
		driver = new webdriver.Builder().withCapabilities( webdriver.Capabilities.chrome() ).build();
		driver.get( env.pageURL( page ) );
	} );

	afterEach( function () {
		driver.quit();
	} );

	it( 'Contains a sign-up form', function () {
		return assert( driver.isElementPresent( page.signUpForm ) ).isTrue();
	} );

	it( 'Rejects a blank form', function () {
		driver.findElement( page.createAccountButton ).click();
		return assert( driver.isElementPresent( page.accountCreationError ) ).isTrue();
	} );

	it( 'Accepts a valid username and password', function () {
		var username = random( 'user' ),
				password = random( 'password' );

		driver.findElement( page.username ).sendKeys( username );
		driver.findElement( page.password ).sendKeys( password );
		driver.findElement( page.passwordConfirmation ).sendKeys( password );
		driver.findElement( page.createAccountButton ).click();

		return assert( driver.isElementPresent( page.userPageLink ) ).isTrue();
	} );
} );
