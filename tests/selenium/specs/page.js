'use strict';

const BlankPage = require( 'wdio-mediawiki/BlankPage' );
const Api = require( 'wdio-mediawiki/Api' );
const DeletePage = require( '../pageobjects/delete.page' );
const RestorePage = require( '../pageobjects/restore.page' );
const EditPage = require( '../pageobjects/edit.page' );
const HistoryPage = require( '../pageobjects/history.page' );
const UndoPage = require( '../pageobjects/undo.page' );
const ProtectPage = require( '../pageobjects/protect.page' );
const LoginPage = require( 'wdio-mediawiki/LoginPage' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'Page', () => {
	let content, name, bot;

	before( async () => {
		bot = await Api.bot();
	} );

	beforeEach( async function () {
		await browser.deleteAllCookies();
		content = Util.getTestString( 'beforeEach-content-' );
		name = Util.getTestString( 'BeforeEach-name-' );

		// First try to load a blank page, so the next command works.
		await BlankPage.open();
		// Don't try to run wikitext-specific tests if the test namespace isn't wikitext by default.
		if ( await Util.isTargetNotWikitext( name ) ) {
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

		// T269566: Popup with text
		// 'Leave site? Changes that you made may not be saved. Cancel/Leave'
		// appears after the browser tries to leave the page with the preview.
		await browser.reloadSession();
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
		const initialContent = Util.getTestString( 'initialContent-' );

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
		const editContent = Util.getTestString( 'editContent-' );
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
		const response = await bot.edit( name, Util.getTestString( 'editContent-' ) );
		const previousRev = response.edit.oldrevid;
		const undoRev = response.edit.newrevid;

		await UndoPage.undo( name, previousRev, undoRev );

		await expect( EditPage.displayedContent ).toHaveText( expect.stringContaining( content ) );
	} );

} );
