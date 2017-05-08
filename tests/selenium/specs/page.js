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
		EditPage.apiEdit( name, content );

		// wait
		browser.waitUntil( function () {
			EditPage.open( name );
			return !/There is currently no text in this page/.test( EditPage.displayedContent.getText() );
		}, 10000 );

		// edit
		EditPage.edit( name, content2 );

		// check
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content2 );

	} );

	it( 'should have history', function () {

		// create
		EditPage.apiEdit( name, content );

		// wait
		browser.waitUntil( function () {
			HistoryPage.open( name );
			return HistoryPage.comment.isExisting();
		}, 10000 );

		// check
		assert.equal( HistoryPage.comment.getText(), `(Created page with "${content}")` );

	} );

} );
