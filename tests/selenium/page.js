/* eslint-env es6, mocha, node */
var assert = require( 'assert' ),
	config = require( 'config' ),
	baseUrl = config.get( 'baseUrl' ),
	browser = config.get( 'browser' ),
	logPath = config.get( 'logPath' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

test.describe( 'Page', function () {

	var driver;

	test.beforeEach( function () {
		driver = new webdriver.Builder()
		.forBrowser( browser )
		.build();
	} );

	test.afterEach( function () {
		if ( this.currentTest.state === 'failed' ) {
			driver.takeScreenshot().then( ( image ) => {
				require( 'fs' ).writeFile( logPath + this.currentTest.fullTitle() + '.png', image, 'base64', ( err ) => {
					if ( err ) { throw err; }
				} );
			} );
		}
		driver.quit();
	} );

	test.it( 'should be created', function () {
		driver.get( baseUrl + 'Does_not_exist' );
		driver.findElement( By.css( 'li#ca-edit a' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

	test.it( 'should be edited', function () {
		driver.get( baseUrl + 'Main_Page' );
		driver.findElement( By.css( 'li#ca-edit a' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

	test.it( 'should have history', function () {
		driver.get( baseUrl + 'Main_Page' );
		driver.findElement( By.css( 'li#ca-history a' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

} );
