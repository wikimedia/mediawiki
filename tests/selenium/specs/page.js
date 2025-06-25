import BlankPage from 'wdio-mediawiki/BlankPage.js';
import { mwbot } from 'wdio-mediawiki/Api.js';
import DeletePage from '../pageobjects/delete.page.js';
import RestorePage from '../pageobjects/restore.page.js';
import EditPage from '../pageobjects/edit.page.js';
import HistoryPage from '../pageobjects/history.page.js';
import UndoPage from '../pageobjects/undo.page.js';
import ProtectPage from '../pageobjects/protect.page.js';
import LoginPage from 'wdio-mediawiki/LoginPage.js';
import { getTestString, isTargetNotWikitext } from 'wdio-mediawiki/Util.js';

describe( 'Page', () => {
	let content, name, bot;

	before( async () => {
		bot = await mwbot();
	} );

	beforeEach( async function () {
		await browser.deleteAllCookies();
		content = getTestString( 'beforeEach-content-' );
		name = getTestString( 'BeforeEach-name-' );

		// First try to load a blank page, so the next command works.
		await BlankPage.open();
		// Don't try to run wikitext-specific tests if the test namespace isn't wikitext by default.
		if ( await isTargetNotWikitext( name ) ) {
			this.skip();
		}
	} );

	it( 'should be previewable @daily', async () => {
		await LoginPage.loginAdmin();
		await EditPage.preview( name, content );

		await expect( EditPage.heading ).toHaveText( `Creating ${ name }` );
		await expect( EditPage.displayedContent ).toHaveText( content );
		await expect( EditPage.content ).toBeDisplayed( { message: 'editor is still present' } );
		await expect( EditPage.conflictingContent ).not.toBeDisplayed( { message: 'no edit conflict happened' } );

	} );

	it( 'should be creatable', async () => {
		// create
		await LoginPage.loginAdmin();
		await EditPage.edit( name, content );

		// check
		await expect( EditPage.heading ).toHaveText( name );
		await expect( EditPage.displayedContent ).toHaveText( content );
	} );

	it( 'should be re-creatable', async () => {
		const initialContent = getTestString( 'initialContent-' );

		// create and delete
		await bot.edit( name, initialContent, 'create for delete' );
		await bot.delete( name, 'delete prior to recreate' );

		// re-create
		await LoginPage.loginAdmin();
		await EditPage.edit( name, content );

		// check
		await expect( EditPage.heading ).toHaveText( name );
		await expect( EditPage.displayedContent ).toHaveText( content );
	} );

	it( 'should be editable @daily', async () => {
		// create
		await bot.edit( name, content, 'create for edit' );

		// edit
		const editContent = getTestString( 'editContent-' );
		await EditPage.edit( name, editContent );

		// check
		await expect( EditPage.heading ).toHaveText( name );
		await expect( EditPage.displayedContent ).toHaveText( expect.stringContaining( editContent ) );
	} );

	it( 'should have history @daily', async () => {
		// create
		await bot.edit( name, content, `created with "${ content }"` );

		// check
		await HistoryPage.open( name );
		await expect( HistoryPage.comment ).toHaveText( `created with "${ content }"` );
	} );

	it( 'should be deletable', async () => {
		// create
		await bot.edit( name, content, 'create for delete' );

		// login
		await LoginPage.loginAdmin();
		// delete
		await DeletePage.delete( name, 'delete reason' );

		// check
		await expect( DeletePage.displayedContent ).toHaveText( expect.stringContaining( `"${ name }" has been deleted.` ) );
	} );

	it( 'should be restorable', async () => {
		// create and delete
		await bot.edit( name, content, 'create for delete' );
		await bot.delete( name, 'delete for restore' );

		// login
		await LoginPage.loginAdmin();

		// restore
		await RestorePage.restore( name, 'restore reason' );

		// check
		await expect( RestorePage.displayedContent ).toHaveText( expect.stringContaining( `${ name } has been undeleted` ) );
	} );

	it( 'should be protectable', async () => {

		await bot.edit( name, content, 'create for protect' );

		// login
		await LoginPage.loginAdmin();

		await ProtectPage.protect(
			name,
			'protect reason',
			'Allow only administrators'
		);

		// Logout
		await browser.deleteAllCookies();

		// Check that we can't edit the page anymore
		await EditPage.openForEditing( name );
		await expect( EditPage.save ).not.toExist();
		await expect( EditPage.heading ).toHaveText( `View source for ${ name }` );
	} );

	it( 'should be undoable @daily', async () => {

		// create
		await bot.edit( name, content, 'create to edit and undo' );

		// edit
		const response = await bot.edit( name, getTestString( 'editContent-' ) );
		const previousRev = response.edit.oldrevid;
		const undoRev = response.edit.newrevid;

		await UndoPage.undo( name, previousRev, undoRev );

		await expect( EditPage.displayedContent ).toHaveText( expect.stringContaining( content ) );
	} );

} );
