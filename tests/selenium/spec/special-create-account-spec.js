/*jshint node: true */

jasmine.DEFAULT_TIMEOUT_INTERVAL = 10000;

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

describe( 'Account creation page', function () {
	beforeEach( function ( done ) {
		this.driver = new selenium.Builder(). withCapabilities( selenium.Capabilities.chrome() ). build();

		this.driver.get( createAccountPage.url ).then( done );
	} );

	afterEach( function ( done ) {
		this.driver.quit().then( done );
	} );

	it( 'Contains a sign-up form', function ( done ) {
		this.driver.findElement( createAccountPage.elements.signUpForm ).then( done );
	} );
} );
