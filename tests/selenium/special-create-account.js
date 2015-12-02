/*jshint node: true */

var assert = require( 'assert' ),
	baseURL = 'http://en.wikipedia.beta.wmflabs.org/wiki/',
	createAccountPage,
	webdriver = require( 'selenium-webdriver' ),
	chrome = require( 'selenium-webdriver/chrome' ),
	path = require( 'chromedriver' ).path,
	service = new chrome.ServiceBuilder(path).build();
	seTest = require( 'selenium-webdriver/testing' ),
	timeout = 10000;

chrome.setDefaultService(service);

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

seTest.describe( 'Special:CreateAccount', function () {
	this.timeout( timeout );
	seTest.it( 'Contains a sign-up form', function () {
		var driver = new webdriver.Builder().withCapabilities( webdriver.Capabilities.chrome() ).build();
		driver.get( createAccountPage.url );
		driver.isElementPresent( createAccountPage.elements.signUpForm ).then( function ( present ) {
			assert.equal( present, true );
		} );
		driver.quit();
	} );
} );
