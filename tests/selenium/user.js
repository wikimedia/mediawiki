var assert = require( 'assert' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

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
		driver.get( 'http://127.0.0.1:8080/wiki/Special:CreateAccount' );
		driver.findElement( By.id( 'wpCreateaccount' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

	test.it( 'should be able to log in', function () {
		driver.get( 'http://127.0.0.1:8080/wiki/Special:UserLogin' );
		driver.findElement( By.id( 'wpLoginAttempt' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );

	test.it( 'should be able to change preferences', function () {
		driver.get( 'http://127.0.0.1:8080/wiki/Special:UserLogin' );
		driver.findElement( By.id( 'wpName1' ) ).sendKeys( 'Admin' );
		driver.findElement( By.id( 'wpPassword1' ) ).sendKeys( 'vagrant' );
		driver.findElement( By.id( 'wpLoginAttempt' ) ).click();

		driver.get( 'http://127.0.0.1:8080/wiki/Special:Preferences' );
		driver.findElement( By.id( 'prefcontrol' ) ).isDisplayed().then( function ( displayed ) {
			assert( displayed );
		} );
	} );
} );
