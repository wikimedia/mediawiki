/*jshint node: true */
/*jshint esversion: 6 */

var assert = require( 'assert' ),
	baseURL = 'http://en.wikipedia.beta.wmflabs.org/wiki/',
	createAccountPage,
	selenium = require( 'selenium-webdriver' ),
	seTest = require( 'selenium-webdriver/testing' ),
	timeout = 10000;

function Page( path ) {
	this.url = baseURL + path;
	this.elements = {};
}

Page.prototype.defineElements = function ( elements ) {
	for ( var name in elements ) {
		this.elements[ name ] = selenium.By.css( elements[ name ] );
	}
};

createAccountPage = new Page( 'Special:CreateAccount' );
createAccountPage.defineElements( {
	signUpForm: 'form[name=userlogin2]'
} );

seTest.describe( 'Special:CreateAccount', function () {
	this.timeout( timeout );
	seTest.it( 'Contains a sign-up form', function () {
		var driver = new selenium.Builder().withCapabilities( selenium.Capabilities.chrome() ).build();
		driver.get( createAccountPage.url );
		driver.isElementPresent( createAccountPage.elements.signUpForm ).then( function ( present ) {
			assert.equal( present, true );
		} );
		driver.quit();
	} );
} );
