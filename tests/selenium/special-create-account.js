/*jshint node: true */

var selenium = require( 'selenium-webdriver' ),
	baseURL = 'http://en.wikipedia.beta.wmflabs.org/wiki/',
	createAccountPage;

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

QUnit.module( 'Special:CreateAccount', {
} );

QUnit.test( 'Contains a sign-up form', function ( assert ) {
	var driver = new selenium.Builder(). withCapabilities( selenium.Capabilities.chrome() ). build();
	driver.get( createAccountPage.url );
	driver.isElementPresent( createAccountPage.elements.signUpForm ).then( function ( present ) {
		assert.equal( present, true );
	} );
	driver.quit();
} );
