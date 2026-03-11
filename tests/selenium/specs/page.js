import BlankPage from 'wdio-mediawiki/BlankPage.js';
import { createApiClient } from 'wdio-mediawiki/Api.js';
import EditPage from '../pageobjects/edit.page.js';
import HistoryPage from '../pageobjects/history.page.js';
import UndoPage from '../pageobjects/undo.page.js';
import LoginPage from 'wdio-mediawiki/LoginPage.js';
import { getTestString, isTargetNotWikitext } from 'wdio-mediawiki/Util.js';

describe( 'Page', () => {
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

	it( 'should be previewable', async () => {
		await EditPage.preview( name, content );

		await expect( EditPage.heading ).toHaveText( `Creating ${ name }` );
		await expect( EditPage.displayedContent ).toHaveText( content );
		await expect( EditPage.content ).toBeDisplayed( { message: 'editor is still present' } );
		await expect( EditPage.conflictingContent ).not.toBeDisplayed( { message: 'no edit conflict happened' } );

	} );

	it( 'should be creatable', async () => {
		// create
		await EditPage.edit( name, content );

		// check
		await expect( EditPage.heading ).toHaveText( name );
		await expect( EditPage.displayedContent ).toHaveText( content );
	} );

	it( 'should be re-creatable', async () => {
		const initialContent = getTestString( 'initialContent-' );

		// create and delete
		await apiClient.edit( name, initialContent, 'create for delete' );
		await apiClient.delete( name, 'delete prior to recreate' );

		// re-create
		await EditPage.edit( name, content );

		// check
		await expect( EditPage.heading ).toHaveText( name );
		await expect( EditPage.displayedContent ).toHaveText( content );
	} );

	it( 'should be editable', async () => {
		// create
		await apiClient.edit( name, content, 'create for edit' );

		// edit
		const editContent = getTestString( 'editContent-' );
		await EditPage.edit( name, editContent );

		// check
		await expect( EditPage.heading ).toHaveText( name );
		await expect( EditPage.displayedContent )
			.toHaveText( expect.stringContaining( editContent ) );
	} );

	it( 'should have history', async () => {
		// create
		await apiClient.edit( name, content, `created with "${ content }"` );

		// check
		await HistoryPage.open( name );
		await expect( HistoryPage.comment ).toHaveText( `created with "${ content }"` );
	} );

	it( 'should be undoable', async () => {
		// create
		await apiClient.edit( name, content, 'create to edit and undo' );

		// edit
		const response = await apiClient.edit( name, getTestString( 'editContent-' ) );
		const previousRev = response.edit.oldrevid;
		const undoRev = response.edit.newrevid;

		await UndoPage.undo( name, previousRev, undoRev );

		await expect( EditPage.displayedContent ).toHaveText( expect.stringContaining( content ) );
	} );

} );
