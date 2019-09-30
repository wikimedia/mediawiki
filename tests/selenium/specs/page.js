const assert = require( 'assert' ),
	Api = require( 'wdio-mediawiki/Api' ),
	BlankPage = require( 'wdio-mediawiki/BlankPage' ),
	DeletePage = require( '../pageobjects/delete.page' ),
	RestorePage = require( '../pageobjects/restore.page' ),
	EditPage = require( '../pageobjects/edit.page' ),
	HistoryPage = require( '../pageobjects/history.page' ),
	UndoPage = require( '../pageobjects/undo.page' ),
	UserLoginPage = require( 'wdio-mediawiki/LoginPage' ),
	Util = require( 'wdio-mediawiki/Util' );

describe( 'Page', function () {
	var content,
		name;

	before( function () {
		// disable VisualEditor welcome dialog
		BlankPage.open();
		browser.setLocalStorage( 've-beta-welcome-dialog', '1' );
	} );

	beforeEach( function () {
		browser.deleteAllCookies();
		content = Util.getTestString( 'beforeEach-content-' );
		name = Util.getTestString( 'BeforeEach-name-' );
	} );

	it( 'should be previewable', function () {
		EditPage.preview( name, content );

		assert.strictEqual( EditPage.heading.getText(), 'Creating ' + name );
		assert.strictEqual( EditPage.displayedContent.getText(), content );
		assert( EditPage.content.isDisplayed(), 'editor is still present' );
		assert( !EditPage.conflictingContent.isDisplayed(), 'no edit conflict happened' );
	} );

	it( 'should be creatable', function () {
		// create
		EditPage.edit( name, content );

		// check
		assert.strictEqual( EditPage.heading.getText(), name );
		assert.strictEqual( EditPage.displayedContent.getText(), content );
	} );

	it( 'should be re-creatable', function () {
		const initialContent = Util.getTestString( 'initialContent-' );

		// create
		browser.call( function () {
			return Api.edit( name, initialContent );
		} );

		// delete
		browser.call( function () {
			return Api.delete( name, 'delete prior to recreate' );
		} );

		// create
		EditPage.edit( name, content );

		// check
		assert.strictEqual( EditPage.heading.getText(), name );
		assert.strictEqual( EditPage.displayedContent.getText(), content );
	} );

	it( 'should be editable @daily', function () {
		// create
		browser.call( function () {
			return Api.edit( name, content );
		} );

		// edit
		const editContent = Util.getTestString( 'editContent-' );
		EditPage.edit( name, editContent );

		// check
		assert.strictEqual( EditPage.heading.getText(), name );
		assert( EditPage.displayedContent.getText().includes( editContent ) );
	} );

	it( 'should have history @daily', function () {
		// create
		browser.call( function () {
			return Api.edit( name, content );
		} );

		// check
		HistoryPage.open( name );
		assert.strictEqual( HistoryPage.comment.getText(), `Created or updated page with "${content}"` );
	} );

	it( 'should be deletable', function () {
		// login
		UserLoginPage.loginAdmin();

		// create
		browser.call( function () {
			return Api.edit( name, content );
		} );

		// delete
		DeletePage.delete( name, content + '-deletereason' );

		// check
		assert.strictEqual(
			DeletePage.displayedContent.getText(),
			'"' + name + '" has been deleted. See deletion log for a record of recent deletions.\nReturn to Main Page.'
		);
	} );

	it( 'should be restorable', function () {
		// login
		UserLoginPage.loginAdmin();

		// create
		browser.call( function () {
			return Api.edit( name, content );
		} );

		// delete
		browser.call( function () {
			return Api.delete( name, content + '-deletereason' );
		} );

		// restore
		RestorePage.restore( name, content + '-restorereason' );

		// check
		assert.strictEqual( RestorePage.displayedContent.getText(), name + ' has been restored\nConsult the deletion log for a record of recent deletions and restorations.' );
	} );

	it( 'should be undoable', function () {
		// create
		browser.call( function () {
			return Api.edit( name, content );
		} );

		// edit
		let previousRev, undoRev;
		browser.call( function () {
			return Api.edit( name, Util.getTestString( 'editContent-' ) )
				.then( ( response ) => {
					previousRev = response.edit.oldrevid;
					undoRev = response.edit.newrevid;
				} );
		} );

		UndoPage.undo( name, previousRev, undoRev );

		assert.strictEqual( EditPage.displayedContent.getText(), content );
	} );

} );
