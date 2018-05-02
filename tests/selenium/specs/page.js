const assert = require( 'assert' ),
	Api = require( 'wdio-mediawiki/Api' ),
	DeletePage = require( '../pageobjects/delete.page' ),
	RestorePage = require( '../pageobjects/restore.page' ),
	EditPage = require( '../pageobjects/edit.page' ),
	HistoryPage = require( '../pageobjects/history.page' ),
	UserLoginPage = require( '../pageobjects/userlogin.page' );

describe( 'Page', function () {
	var content,
		name;

	function getTestString() {
		return Math.random().toString() + '-öäü-♠♣♥♦';
	}

	before( function () {
		// disable VisualEditor welcome dialog
		UserLoginPage.open();
		browser.localStorage( 'POST', { key: 've-beta-welcome-dialog', value: '1' } );
	} );

	beforeEach( function () {
		browser.deleteCookie();
		content = getTestString();
		name = getTestString();
	} );

	it( 'should be creatable', function () {
		// create
		EditPage.edit( name, content );

		// check
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content );
	} );

	it( 'should be re-creatable', function () {
		let initialContent = getTestString();

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
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content );
	} );

	it( 'should be editable', function () {
		// create
		browser.call( function () {
			return Api.edit( name, content );
		} );

		// edit
		EditPage.edit( name, content );

		// check
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content );
	} );

	it( 'should have history', function () {
		// create
		browser.call( function () {
			return Api.edit( name, content );
		} );

		// check
		HistoryPage.open( name );
		assert.equal( HistoryPage.comment.getText(), `(Created page with "${content}")` );
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
		assert.equal(
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
		assert.equal( RestorePage.displayedContent.getText(), name + ' has been restored\nConsult the deletion log for a record of recent deletions and restorations.' );
	} );
} );
