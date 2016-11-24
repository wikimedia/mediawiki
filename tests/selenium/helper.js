/* eslint-env es6, node */
var config = require( 'config' ),
	webdriver = require( 'selenium-webdriver' );

function browser() {
	return new webdriver.Builder()
	.forBrowser( config.get( 'browser' ) )
	.build();
}

function screenshot( driver, state, title ) {
	if ( state === 'failed' ) {
		driver.takeScreenshot().then( ( image ) => {
			let fileName = config.get( 'logPath' ) + title + '.png';
			require( 'fs' ).writeFile( fileName, image, 'base64', ( err ) => {
				if ( err ) { throw err; }
			} );
		} );
	}
}

module.exports = { browser, screenshot };
