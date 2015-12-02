/*jshint node: true */
/*jshint esversion: 6 */

var assert = require( 'assert' ),
baseURL = 'http://en.wikipedia.beta.wmflabs.org/wiki/',
createAccountPage,
selenium = require( 'selenium-webdriver' ),
test = require( 'selenium-webdriver/testing' );

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

const mochaTimeOut = 30000; // ms

test.describe( 'Special:CreateAccount', function () {
	this.timeout( mochaTimeOut );
	test.it( 'Contains a sign-up form', function () {
		var driver = new selenium.Builder().withCapabilities( selenium.Capabilities.chrome() ).build();
		driver.get( createAccountPage.url );
		driver.isElementPresent( createAccountPage.elements.signUpForm ).then( function ( present ) {
			assert.equal( present, true );
		} );
		driver.quit();
	} );
} );
