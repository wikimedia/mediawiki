/* eslint-env mocha, node */
/* global browser */
var assert = require( 'assert' ),
	randomPage = require( './pages/randomPage' );

describe( 'Page', function () {

	var content,
		name;

	beforeEach( function () {
		content = Math.random().toString();
		name = Math.random().toString();
	} );

	it( 'should be creatable', function () {

		// create
		randomPage.create( name, content );

		// check
		assert.equal( randomPage.heading.getText(), name );
		assert.equal( randomPage.displayedContent.getText(), content );

	} );

	it( 'should be editable', function () {

		var content2 = Math.random().toString();

		// create
		browser.url( '/index.php?title=' + name + '&action=edit' );
		browser.setValue( '#wpTextbox1', content );
		browser.click( '#wpSave' );

		// edit
		browser.url( '/index.php?title=' + name + '&action=edit' );
		browser.setValue( '#wpTextbox1', content2 );
		browser.click( '#wpSave' );

		// check content
		assert.equal( browser.getText( '#mw-content-text' ), content2 );

	} );

	it( 'should have history', function () {

		// create
		browser.url( '/index.php?title=' + name + '&action=edit' );
		browser.setValue( '#wpTextbox1', content );
		browser.click( '#wpSave' );

		// check history
		browser.url( '/index.php?title=' + name + '&action=history' );
		assert.equal( browser.getText( '#pagehistory .comment' ), '(Created page with "' + content + '")' );

	} );

} );
