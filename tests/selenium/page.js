/* eslint-env es6, mocha, node */
var assert = require( 'assert' ),
	config = require( 'config' ),
	baseUrl = config.get( 'baseUrl' ),
	webdriver = require( 'selenium-webdriver' ),
	By = webdriver.By,
	test = require( 'selenium-webdriver/testing' );

test.describe( 'Page', function () {

	let content,
		driver,
		helper = require( './helper' ),
		name;

	test.beforeEach( function () {
		content = Math.random().toString();
		driver = helper.browser();
		name = Math.random().toString();
	} );

	test.afterEach( function () {
		helper.screenshot( driver, this.currentTest.state, this.currentTest.fullTitle() );
		driver.quit();
	} );

	test.it( 'should be creatable', function () {

		// create
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

		let content2 = Math.random().toString();

		// create
		driver.get( baseUrl + name + '&action=edit' );
		driver.findElement( By.css( '#wpTextbox1' ) ).sendKeys( content );
		driver.findElement( By.css( '#wpSave' ) ).click();

		// edit
		driver.get( baseUrl + name + '&action=edit' );
		driver.findElement( By.css( '#wpTextbox1' ) ).clear();
		driver.findElement( By.css( '#wpTextbox1' ) ).sendKeys( content2 );
		driver.findElement( By.css( '#wpSave' ) ).click();

		// check content
		driver.findElement( By.css( '#mw-content-text' ) ).getText().then( function ( text ) {
			assert.equal( text, content2 );
		} );

	} );

	test.it( 'should have history', function () {

		// create
		driver.get( baseUrl + name + '&action=edit' );
		driver.findElement( By.css( '#wpTextbox1' ) ).sendKeys( content );
		driver.findElement( By.css( '#wpSave' ) ).click();

		// check history
		driver.get( baseUrl + name + '&action=history' );
		driver.findElement( By.css( '#pagehistory .comment' ) ).getText().then( function ( text ) {
			assert.equal( text, '(Created page with "' + content + '")' );
		} );

	} );

} );
