var assert = require( 'selenium-webdriver/testing/assert' ),
	crypto = require( 'crypto' ),
	env = require( '../lib/environment.js' ),
	page = require( '../pages/special-create-account.js' ),
	po = require( '../lib/page-object.js' ),
	mw;

function random( prefix, length ) {
	return prefix + crypto.randomBytes( length || 10 ).toString( 'hex' );
}

describe( 'Special:CreateAccount', function () {
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
