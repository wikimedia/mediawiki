// Example code for Selenium/Explanation/Stack
// https://www.mediawiki.org/wiki/Selenium/Explanation/Stack

// baseUrl is required for our continuous integration.
// If you don't have MW_SERVER and MW_SCRIPT_PATH environment variables set
// you can probably hardcode it to something like this:
// const baseUrl = 'http://localhost:8080/wiki/';
const baseUrl = `${ process.env.MW_SERVER }${ process.env.MW_SCRIPT_PATH }/index.php?title=`;

describe( 'Main page', () => {
	it( 'should have "Log in" link when using mocha', async () => {
		await browser.url( `${ baseUrl }Main_Page` );
		const displayed = await $( 'li#pt-login-2 a' ).isDisplayed();
		if ( displayed === false ) {
			throw new Error( 'Log in link not visible' );
		} else {
			console.log( 'Log in link visible' );
		}
	} );
} );
