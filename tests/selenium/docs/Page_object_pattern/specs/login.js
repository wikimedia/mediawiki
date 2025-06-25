// Example code for Selenium/Explanation/Page object pattern
// https://www.mediawiki.org/wiki/Selenium/Explanation/Page_object_pattern

import { mwbot, createAccount } from 'wdio-mediawiki/Api.js';
import { getTestString } from 'wdio-mediawiki/Util.js';

// baseUrl is required for our continuous integration.
// If you don't have MW_SERVER and MW_SCRIPT_PATH environment variables set
// you can probably hardcode it to something like this:
// const baseUrl = 'http://localhost:8080/wiki/';
const baseUrl = `${ process.env.MW_SERVER }${ process.env.MW_SCRIPT_PATH }/index.php?title=`;

describe( 'User', () => {
	let password, username, bot;

	before( async () => {
		bot = await mwbot();
	} );

	beforeEach( async () => {
		username = getTestString( 'User-' );
		password = getTestString();
	} );

	it( 'should be able to log in without page object', async () => {
		// create
		await createAccount( bot, username, password );

		// log in
		await browser.url( `${ baseUrl }Special:UserLogin` );
		await $( '#wpName1' ).setValue( username );
		await $( '#wpPassword1' ).setValue( password );
		await $( '#wpLoginAttempt' ).click();

		// check
		const actualUsername = await browser.execute( () => mw.config.get( 'wgUserName' ) );
		expect( actualUsername ).toBe( username );
	} );
} );
