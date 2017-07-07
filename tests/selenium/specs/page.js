'use strict';
const assert = require( 'assert' ),
	EditPage = require( '../pageobjects/edit.page' ),
	HistoryPage = require( '../pageobjects/history.page' );

describe( 'Page', function () {

	var content,
		name;

	beforeEach( function () {
		browser.deleteCookie();
		content = Math.random().toString();
		name = Math.random().toString();
	} );

	it( 'should be creatable', function () {

		// create
		EditPage.edit( name, content );

		// check
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content );

	} );

	it( 'should be editable', function () {

		var content2 = Math.random().toString();

		// create
		browser.call( function () {
			return EditPage.apiEdit( name, content );
		} );

		// edit
		EditPage.edit( name, content2 );

		// check
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content2 );

	} );

	it( 'should have history', function () {

		// create
		browser.call( function () {
			return EditPage.apiEdit( name, content );
		} );

		// check
		HistoryPage.open( name );
		assert.equal( HistoryPage.comment.getText(), `(Created page with "${content}")` );

	} );

} );
