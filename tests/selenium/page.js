/* eslint-env es6, mocha, node */
var assert = require( 'assert' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

test.describe( 'Page', function () {
	var baseUrl, driver;

	test.before( function () {
		baseUrl = process.env.MW_SERVER + process.env.MW_SCRIPT_PATH + '/index.php?title=';
	} );

	test.beforeEach( function () {
		driver = new webdriver.Builder()
		.forBrowser( 'firefox' )
		.build();
	} );

	test.afterEach( function () {
		driver.takeScreenshot().then( ( image ) => {
			require( 'fs' ).writeFile( process.env.WORKSPACE + '/log/' + this.currentTest.title + '.png', image, 'base64', ( err ) => {
				if ( err ) { throw err; }
			} );
		} );
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
