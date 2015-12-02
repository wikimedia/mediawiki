var assert = require( 'selenium-webdriver/testing/assert' ),
		random = require( 'malu' ).random,
		page = require( '../pages/special-create-account.js' );

require( '../suite.js' );

describe( 'Special:CreateAccount', function () {
	var driver;

	beforeEach( function () {
		driver = this.runtime.startBrowser();
		return driver.get( this.wiki.pageURL( page ) );
	} );

	it( 'Contains a sign-up form', function () {
		return assert( driver.isElementPresent( page.signUpForm ) ).isTrue();
	} );

	it( 'Rejects a blank form', function () {
		driver.findElement( page.createAccountButton ).click();
		return assert( driver.isElementPresent( page.accountCreationError ) ).isTrue();
	} );

	it( 'Accepts a valid username and password', function () {
		var username = random.string( 'user' ),
				password = random.string( 'password' );

		driver.findElement( page.username ).sendKeys( username );
		driver.findElement( page.password ).sendKeys( password );
		driver.findElement( page.passwordConfirmation ).sendKeys( password );
		driver.findElement( page.createAccountButton ).click();

		return assert( driver.isElementPresent( page.userPageLink ) ).isTrue();
	} );
} );
