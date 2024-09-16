// Example code for Selenium/Explanation/Stack
// https://www.mediawiki.org/wiki/Selenium/Explanation/Stack

'use strict';

const MainPage = require( '../pageobjects/main.page' );

describe( 'Main Page', () => {
	it( 'should have "Log in" link when using page object', async () => {
		await MainPage.open();
		await expect( MainPage.login ).toExist();
	} );
} );
