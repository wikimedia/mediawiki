/* eslint-env es6, mocha, node */
var assert = require( 'assert' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

if ( process.env.JENKINS_HOME ) {
	// Jenkins
	var baseUrl = process.env.MW_SERVER + process.env.MW_SCRIPT_PATH + '/index.php?title=';
} else {
	// MediaWiki-Vagrant
	var baseUrl = 'http://127.0.0.1:8080/wiki/';
}

test.describe( 'User', function () {

	var driver;

	test.beforeEach( function () {
		driver = new webdriver.Builder()
		.forBrowser( 'firefox' )
		.build();
	} );

	test.afterEach( function () {
		driver.quit();
	} );

	test.it( 'should be able to create account', function () {
		driver.get( baseUrl + 'Special:CreateAccount' );
		driver.findElement( By.id( 'wpCreateaccount' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

	test.it( 'should be able to log in', function () {
		driver.get( baseUrl + 'Special:UserLogin' );
		driver.findElement( By.id( 'wpLoginAttempt' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

	test.it( 'should be able to change preferences', function () {
		driver.get( baseUrl + 'Special:UserLogin' );
		driver.findElement( By.id( 'wpName1' ) ).sendKeys( 'WikiAdmin' );
		driver.findElement( By.id( 'wpPassword1' ) ).sendKeys( 'testpass' );
		driver.findElement( By.id( 'wpLoginAttempt' ) ).click();

		driver.get( baseUrl + 'Special:Preferences' );
		driver.findElement( By.id( 'prefcontrol' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

} );
