/*jshint node: true */

var assert = require( 'selenium-webdriver/testing/assert' ),
	baseURL = 'http://127.0.0.1:8080/wiki/',
	createAccountPage,
	crypto = require('crypto'),
	webdriver = require( 'selenium-webdriver' ),
	chrome = require( 'selenium-webdriver/chrome' ),
	path = require( 'chromedriver' ).path,
	service = new chrome.ServiceBuilder( path ).build(),
	test = require( 'selenium-webdriver/testing' ),
	timeout = 10000;

chrome.setDefaultService( service );

function Page( path ) {
	this.url = baseURL + path;
}

Page.prototype.defineElements = function ( elements ) {
	for ( var name in elements ) {
		this[ name ] = webdriver.By.css( elements[ name ] );
	}
};

createAccountPage = new Page( 'Special:CreateAccount' );
createAccountPage.defineElements( {
	signUpForm: 'form[name=userlogin2]',
	username: '#wpName2',
	password: '#wpPassword2',
	passwordConfirmation: '#wpRetype',
	createAccountButton: '#wpCreateaccount',
	accountCreationError: '#mw-createacct-status-area.errorbox',
	userPageLink: '#pt-userpage'
} );

function random(prefix, length) {
	return prefix + crypto.randomBytes(length || 10).toString('hex');
}

test.describe( 'Special:CreateAccount', function () {

	var driver,
			page = createAccountPage;

	this.timeout( timeout );

	beforeEach( function () {
		driver = new webdriver.Builder().withCapabilities( webdriver.Capabilities.chrome() ).build();
		driver.get( page.url );
	} );

	afterEach( function () {
		driver.quit();
	} );

	test.it( 'Contains a sign-up form', function () {
		assert( driver.isElementPresent( page.signUpForm ) ).isTrue();
	} );

	test.it( 'Rejects a blank form', function () {
		driver.findElement( page.createAccountButton ).click();
		assert( driver.isElementPresent( page.accountCreationError ) ).isTrue();
	} );

	test.it( 'Accepts a valid username and password', function () {
		var username = random( 'user' ),
				password = random( 'password' );

		driver.findElement( page.username ).sendKeys( username );
		driver.findElement( page.password ).sendKeys( password );
		driver.findElement( page.passwordConfirmation ).sendKeys( password );
		driver.findElement( page.createAccountButton ).click();

		assert( driver.isElementPresent( page.userPageLink ) ).isTrue();
	} );
} );
