// Example code for Selenium/Getting Started/Create a simple test
// https://www.mediawiki.org/wiki/Selenium/Getting_Started/Create_a_simple_test

import SpecialPages from '../pageobjects/specialpages.page.js';

describe( 'Special:SpecialPages', () => {
	it( 'should not have Edit link', async () => {
		await SpecialPages.open();
		await expect( SpecialPages.edit ).not.toExist();
	} );
} );
