import { createApiClient } from 'wdio-mediawiki/Api.js';
import BlankPage from 'wdio-mediawiki/BlankPage.js';
import Page from 'wdio-mediawiki/Page.js';
import CategoryPage from '../pageobjects/category.page.js';
import { getTestString, isTargetNotWikitext } from 'wdio-mediawiki/Util.js';

describe( 'Category page', () => {
	let category, apiClient;

	before( async () => {
		apiClient = await createApiClient();
	} );

	beforeEach( async function () {
		await browser.deleteAllCookies();
		category = getTestString( 'Category-' );

		// First try to load a blank page, so the next command works.
		await BlankPage.open();
		// Don't try to run wikitext-specific tests if the test namespace isn't wikitext by default.
		if ( await isTargetNotWikitext( getTestString() ) ) {
			this.skip();
		}
	} );

	it( 'should show that an empty category has no members', async () => {
		await apiClient.edit( `Category:${ category }`, 'An empty category' );

		await CategoryPage.open( category );

		await expect( CategoryPage.heading ).toHaveText( `Category:${ category }` );
		await expect( CategoryPage.generatedList )
			.toHaveText( expect.stringContaining( 'This category currently contains no pages or media.' ) );
		await expect( CategoryPage.pages ).not.toExist();
		await expect( CategoryPage.subcategories ).not.toExist();
	} );

	it( 'should list member pages and subcategories', async () => {
		const member = getTestString( 'Member-' );
		const subcategory = getTestString( 'Subcategory-' );

		await apiClient.edit( `Category:${ category }`, 'A category with members' );
		await apiClient.edit( member, `[[Category:${ category }]]` );
		await apiClient.edit( `Category:${ subcategory }`, `[[Category:${ category }]]` );

		await CategoryPage.open( category );

		await expect( CategoryPage.page( member ) ).toExist();
		await expect( CategoryPage.subcategory( subcategory ) ).toExist();
		// A subcategory is not listed as a page
		await expect( CategoryPage.page( `Category:${ subcategory }` ) ).not.toExist();
	} );

	it( 'should be reachable from a member page', async () => {
		const member = getTestString( 'Member-' );

		await apiClient.edit( `Category:${ category }`, 'A category with members' );
		await apiClient.edit( member, `[[Category:${ category }]]` );

		await new Page().openTitle( member );
		await CategoryPage.categoryLink( category ).click();

		await expect( CategoryPage.heading ).toHaveText( `Category:${ category }` );
		await expect( CategoryPage.page( member ) ).toExist();
	} );
} );
