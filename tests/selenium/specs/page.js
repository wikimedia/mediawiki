'use strict';
const assert = require( 'assert' ),
	HistoryPage = require( '../pageobjects/history.page' ),
	RandomPage = require( '../pageobjects/random.page' );

describe( 'Page', function () {

	var content,
		name;

	beforeEach( function () {
		content = Math.random().toString();
		name = Math.random().toString();
	} );

	it( 'should be creatable', function () {

		// create
		RandomPage.edit( name, content );

		// check
		assert.equal( RandomPage.heading.getText(), name );
		assert.equal( RandomPage.displayedContent.getText(), content );

	} );

	it( 'should be editable', function () {

		var content2 = Math.random().toString();

		// create
		RandomPage.edit( name, content );

		// edit
		RandomPage.edit( name, content2 );

		// check content
		assert.equal( RandomPage.heading.getText(), name );
		assert.equal( RandomPage.displayedContent.getText(), content2 );

	} );

	it( 'should have history', function () {

		// create
		RandomPage.edit( name, content );

		// check
		HistoryPage.open( name );
		assert.equal( HistoryPage.comment.getText(), `(Created page with "${content}")` );

	} );

} );
