import BlankPage from 'wdio-mediawiki/BlankPage.js';
import { createApiClient } from 'wdio-mediawiki/Api.js';
import DeletePage from '../pageobjects/delete.page.js';
import RestorePage from '../pageobjects/restore.page.js';
import EditPage from '../pageobjects/edit.page.js';
import ProtectPage from '../pageobjects/protect.page.js';
import LoginPage from 'wdio-mediawiki/LoginPage.js';
import { getTestString, isTargetNotWikitext } from 'wdio-mediawiki/Util.js';

describe( 'Page admin actions', () => {
	let content, name, apiClient;

	before( async () => {
		apiClient = await createApiClient();

		await LoginPage.loginAdmin();
	} );

	beforeEach( async function () {
		content = getTestString( 'beforeEach-content-' );
		name = getTestString( 'BeforeEach-name-' );

		// First try to load a blank page, so the next command works.
		await BlankPage.open();
		// Don't try to run wikitext-specific tests if the test namespace isn't wikitext by default.
		if ( await isTargetNotWikitext( name ) ) {
			this.skip();
		}
	} );

	it( 'should be deletable', async () => {
		// create
		await apiClient.edit( name, content, 'create for delete' );

		// delete
		await DeletePage.delete( name, 'delete reason' );

		// check
		await expect( DeletePage.displayedContent ).toHaveText( expect.stringContaining( `"${ name }" has been deleted.` ) );
	} );

	it( 'should be restorable', async () => {
		// create and delete
		await apiClient.edit( name, content, 'create for delete' );
		await apiClient.delete( name, 'delete for restore' );

		// restore
		await RestorePage.restore( name, 'restore reason' );

		// check
		await expect( RestorePage.displayedContent ).toHaveText( expect.stringContaining( `${ name } has been undeleted` ) );
	} );

	it( 'should be protectable', async () => {
		await apiClient.edit( name, content, 'create for protect' );

		await ProtectPage.protect(
			name,
			'protect reason',
			'Allow only administrators'
		);

		// Log out the user
		await browser.deleteAllCookies();

		// Check that we can't edit the page anymore
		await EditPage.openForEditing( name );
		expect( await EditPage.save.isExisting() ).toBe( false );
		await expect( EditPage.heading ).toHaveText( `View source for ${ name }` );
	} );

} );
