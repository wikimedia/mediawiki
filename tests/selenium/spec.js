/*jshint node: true */

var assert = require( 'assert' );
var webdriver = require( 'selenium-webdriver' );

describe( 'Ralph Says', function () {
	it( 'shows a quote container', function () {
		var driver = new webdriver.Builder().withCapabilities( webdriver.Capabilities.chrome() ).build();
		driver.get( 'http://ralphsays.github.io' );
		var present = driver.isElementPresent( webdriver.By.id( 'quote' ) );
		assert.equal( present, true, 'Quote container not displayed' );
		driver.quit();
	} );
} );
