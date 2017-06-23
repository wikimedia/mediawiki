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
		EditPage.apiEdit( name, content );
		browser.pause( 3000 ); // TODO: hardcoded pauses are evil http://webdriver.io/api/utility/pause.html

		// edit
		EditPage.edit( name, content2 );

		// check content
		EditPage.open( name );
		assert.equal( EditPage.heading.getText(), name );
		assert.equal( EditPage.displayedContent.getText(), content2 );

	} );

	it( 'should have history', function () {

		// create
		EditPage.apiEdit( name, content );
		browser.pause( 3000 ); // TODO: hardcoded pauses are evil http://webdriver.io/api/utility/pause.html

		// check
		HistoryPage.open( name );
		assert.equal( HistoryPage.comment.getText(), `(Created page with "${content}")` );

	} );

} );
