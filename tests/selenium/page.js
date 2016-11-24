/* eslint-env es6, mocha, node */
var assert = require( 'assert' ),
	config = require( 'config' ),
	baseUrl = config.get( 'baseUrl' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

test.describe( 'Page', function () {

	var driver,
		helper = require( './helper' ),
		name = Math.random().toString(),
		content = Math.random().toString();

	test.beforeEach( function () {
		driver = helper.browser();
	} );

	test.afterEach( function () {
		helper.screenshot( driver, this.currentTest.state, this.currentTest.fullTitle() );
		driver.quit();
	} );

	test.it( 'should be creatable', function () {
		// create page
		driver.get( baseUrl + name + '&action=edit' );
		driver.findElement( By.css( '#wpTextbox1' ) ).sendKeys( content );
		driver.findElement( By.css( '#wpSave' ) ).click();

		// check name
		driver.findElement( By.css( '#firstHeading' ) ).getText().then( function ( text ) {
			assert.equal( text, name );
		} );

		// check content
		driver.findElement( By.css( '#mw-content-text' ) ).getText().then( function ( text ) {
			assert.equal( text, content );
		} );

	} );

	test.it( 'should be editable', function () {
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
