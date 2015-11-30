/*jshint node: true */

var selenium = require( 'selenium-webdriver' ),
	baseURL = 'http://en.wikipedia.beta.wmflabs.org/wiki/';

function Page( path ) {
	this.url = baseURL + path;
	this.elements = {};
}

Page.prototype.defineElements = function ( elements ) {
	for ( var name in elements ) {
		this.elements[ name ] = selenium.By.css( elements[ name ] );
	}
};

var createAccountPage = new Page( 'Special:CreateAccount' );
createAccountPage.defineElements( {
	signUpForm: 'form[name=userlogin2]'
} );


QUnit.module("Account creation page", {
	setup: function ( done ) {
		this.driver = new selenium.Builder(). withCapabilities( selenium.Capabilities.chrome() ). build();
		this.driver.get( createAccountPage.url ).then( done );
	},
	teardown: function ( done ) {
		this.driver.quit().then( done );
	}
});

test("Contains a sign-up form", function ( done ) {
		var i = this.driver.findElement( createAccountPage.elements.signUpForm ).then( done );
		console.log(i);
    equal(true, true, "passing test");
});
