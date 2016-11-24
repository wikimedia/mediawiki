/* eslint-env es6, mocha, node */
var assert = require( 'assert' ),
	config = require( 'config' ),
	baseUrl = config.get( 'baseUrl' ),
	password = config.get( 'password' ),
	username = config.get( 'username' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

test.describe( 'User', function () {

	var driver,
		helper = require( './helper' );

	test.beforeEach( function () {
		driver = helper.browser();
	} );

	test.afterEach( function () {
		helper.screenshot( driver, this.currentTest.state, this.currentTest.fullTitle() );
		driver.quit();
	} );

	test.it( 'should be able to create account', function () {
		let username = Math.random().toString(),
			password = Math.random().toString();

		// create
		driver.get( baseUrl + 'Special:CreateAccount' );
		driver.findElement( By.css( '#wpName2' ) ).sendKeys( username );
		driver.findElement( By.css( '#wpPassword2' ) ).sendKeys( password );
		driver.findElement( By.css( '#wpRetype' ) ).sendKeys( password );
		driver.findElement( By.css( '#wpCreateaccount' ) ).click();

		// check
		driver.findElement( By.css( '#firstHeading' ) ).getText().then( function ( text ) {
			assert.equal( text, 'Welcome, ' + username + '!' );
		} );

	} );

	test.it( 'should be able to log in', function () {
		let username = Math.random().toString(),
			password = Math.random().toString();

		// create
		driver.get( baseUrl + 'Special:CreateAccount' );
		driver.findElement( By.css( '#wpName2' ) ).sendKeys( username );
		driver.findElement( By.css( '#wpPassword2' ) ).sendKeys( password );
		driver.findElement( By.css( '#wpRetype' ) ).sendKeys( password );
		driver.findElement( By.css( '#wpCreateaccount' ) ).click();

		// logout
		driver.get( baseUrl + 'Special:UserLogout' );

		// log in
		driver.get( baseUrl + 'Special:UserLogin' );
		driver.findElement( By.css( '#wpName1' ) ).clear();
		driver.findElement( By.css( '#wpName1' ) ).sendKeys( username );
		driver.findElement( By.css( '#wpPassword1' ) ).sendKeys( password );
		driver.findElement( By.css( '#wpLoginAttempt' ) ).click();

		// check
		driver.findElement( By.css( '#pt-userpage' ) ).getText().then( function ( text ) {
			assert.equal( text, username );
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
