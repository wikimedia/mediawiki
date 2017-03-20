'use strict';
const assert = require( 'assert' ),
	HistoryPage = require( '../pageobjects/history.page' ),
	EditPage = require( '../pageobjects/edit.page' );

describe( 'Page', function () {

	var content,
		name;

	beforeEach( function () {
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
		EditPage.edit( name, content );

		// edit
		EditPage.edit( name, content2 );

		// check content
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content2 );

	} );

	it( 'should have history', function () {

		// create
		EditPage.edit( name, content );

		// check
		HistoryPage.open( name );
		assert.equal( HistoryPage.comment.getText(), `(Created page with "${content}")` );

	} );

} );
