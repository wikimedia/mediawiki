/*jshint node: true */

var baseURL = 'http://en.wikipedia.beta.wmflabs.org/wiki/',
	createAccountPage,
	webdriver = require( 'selenium-webdriver' ),
	chrome = require( 'selenium-webdriver/chrome' ),
	path = require( 'chromedriver' ).path,
	service = new chrome.ServiceBuilder( path ).build();

chrome.setDefaultService( service );
jasmine.DEFAULT_TIMEOUT_INTERVAL = 10000;

function Page( path ) {
	this.url = baseURL + path;
	this.elements = {};
}

Page.prototype.defineElements = function ( elements ) {
	for ( var name in elements ) {
		this.elements[ name ] = webdriver.By.css( elements[ name ] );
	}
};

createAccountPage = new Page( 'Special:CreateAccount' );
createAccountPage.defineElements( {
	signUpForm: 'form[name=userlogin2]'
} );

describe( 'Special:CreateAccount', function () {
	beforeEach( function ( done ) {
		this.driver = new webdriver.Builder(). withCapabilities( webdriver.Capabilities.chrome() ). build();

		this.driver.get( createAccountPage.url ).then( done );
	} );

	afterEach( function ( done ) {
		this.driver.quit().then( done );
	} );

	it( 'Contains a sign-up form', function ( done ) {
		this.driver.findElement( createAccountPage.elements.signUpForm ).then( done );
	} );
} );
