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
		return EditPage.apiEdit( name, content )
			// edit
			.then( () => EditPage.edit( name, content2 ) )
			// check
			.then( () => EditPage.open( name ) )
			.then( () => EditPage.heading.getText() )
			.then( text => assert.equal( text, name ) )
			.then( () => EditPage.displayedContent.getText() )
			.then( text => assert.equal( text, content2 ) );

	} );

	it( 'should have history', function () {

		// create
		return EditPage.apiEdit( name, content )
		// check
			.then( () => HistoryPage.open( name ) )
			.then( () => HistoryPage.comment.getText() )
			.then( text => assert.equal( text, `(Created page with "${content}")` ) );

	} );

} );
