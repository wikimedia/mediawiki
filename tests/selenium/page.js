var assert = require( 'assert' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

test.describe( 'Page', function () {
	var baseUrl, driver;

	test.before( function () {
		baseUrl = process.env.MW_SERVER + process.env.MW_SCRIPT_PATH + '/index.php?title=';
		console.log( 'MW_SERVER: ' + process.env.MW_SERVER );
		console.log( 'MW_SCRIPT_PATH: ' + process.env.MW_SCRIPT_PATH);
		console.log( 'baseUrl: ' + baseUrl );
	} );

	test.beforeEach( function () {
		driver = new webdriver.Builder()
		.forBrowser( 'firefox' )
		.build();
	} );

	test.afterEach( function () {
		driver.quit();
	} );

	test.it( 'should be created', function () {
		console.log( 'baseUrl + Does_not_exist: ' + baseUrl + 'Does_not_exist');
		driver.get( baseUrl + 'Does_not_exist' );
		driver.takeScreenshot().then(( image ) => {
			require( 'fs' ).writeFile( 'log/screenshot.png', image, 'base64', ( err ) => {
				if ( err ) throw err;
				console.log( 'screenshot saved' );
			});
		});
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
