// Example code for Selenium/Explanation/Stack
// https://www.mediawiki.org/wiki/Selenium/Explanation/Stack

import MainPage from '../pageobjects/main.page.js';

describe( 'Main Page', () => {
	it( 'should have edit link when using page object', async () => {
		await MainPage.open();
		await expect( MainPage.edit ).toExist();
	} );
} );
