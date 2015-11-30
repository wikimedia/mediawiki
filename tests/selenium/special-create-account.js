/*jshint node: true */

var baseURL = 'http://en.wikipedia.beta.wmflabs.org/wiki/',
	createAccountPage,
	webdriver = require( 'selenium-webdriver' ),
	chrome = require( 'selenium-webdriver/chrome' ),
	path = require( 'chromedriver' ).path,
	service = new chrome.ServiceBuilder( path ).build();

chrome.setDefaultService( service );

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

QUnit.module( 'Special:CreateAccount', {

	beforeEach: function () {
		this.driver = new webdriver.Builder().withCapabilities( webdriver.Capabilities.chrome() ).build();
		this.driver.get( createAccountPage.url );
	},

	afterEach: function () {
		this.driver.quit();
	}
} );

QUnit.test( 'Contains a sign-up form', function ( assert ) {
	this.driver.isElementPresent( createAccountPage.elements.signUpForm ).then( function ( present ) {
		assert.equal( present, true );
	} );
} );
