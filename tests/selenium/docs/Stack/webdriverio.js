// Example code for Selenium/Explanation/Stack
// https://www.mediawiki.org/wiki/Selenium/Explanation/Stack

// baseUrl is required for our continuous integration.
// If you don't have MW_SERVER and MW_SCRIPT_PATH environment variables set
// you can probably hardcode it to something like this:
// const baseUrl = 'http://localhost:8080/wiki/';
const baseUrl = `${ process.env.MW_SERVER }${ process.env.MW_SCRIPT_PATH }/index.php?title=`;

import { remote } from 'webdriverio';

const browser = await remote( {
	capabilities: {
		browserName: 'chrome',
		'goog:chromeOptions': {
			args: [ '--headless', '--no-sandbox' ],
			...( process.env.CI && {
				binary: '/usr/bin/chromium'
			} )
		},
		...( process.env.CI && {
			'wdio:chromedriverOptions': {
				binary: '/usr/bin/chromedriver'
			}
		} ),
		'wdio:enforceWebDriverClassic': true
	}
} );

await browser.url( `${ baseUrl }Main_Page` );

const displayed = await browser.$( 'li#pt-login-2 a' ).isDisplayed();
if ( displayed === false ) {
	throw new Error( 'Log in link not visible' );
} else {
	console.log( 'Log in link visible' );
}
await browser.deleteSession();
