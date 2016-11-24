/* eslint-env es6, mocha, node */
var assert = require( 'assert' ),
	config = require( 'config' ),
	baseUrl = config.get( 'baseUrl' ),
	browser = config.get( 'browser' ),
	username = config.get( 'username' ),
	password = config.get( 'password' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

test.describe( 'User', function () {

	var driver;

	test.beforeEach( function () {
		driver = new webdriver.Builder()
		.forBrowser( browser )
		.build();
	} );

	test.afterEach( function () {
		if ( this.currentTest.state === 'failed' ) {
			driver.takeScreenshot().then( ( image ) => {
				require( 'fs' ).writeFile( process.env.WORKSPACE + '/log/' + this.currentTest.fullTitle() + '.png', image, 'base64', ( err ) => {
					if ( err ) { throw err; }
				} );
			} );
		}
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
		driver.findElement( By.id( 'wpName1' ) ).sendKeys( username );
		driver.findElement( By.id( 'wpPassword1' ) ).sendKeys( password );
		driver.findElement( By.id( 'wpLoginAttempt' ) ).click();

		driver.get( baseUrl + 'Special:Preferences' );
		driver.findElement( By.id( 'prefcontrol' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

} );
