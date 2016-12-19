/* eslint-env mocha, node */
var assert = require( 'assert' ),
	historyPage = require( './pages/historyPage' ),
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
		randomPage.edit( name, content );

		// check
		assert.equal( randomPage.heading.getText(), name );
		assert.equal( randomPage.displayedContent.getText(), content );

	} );

	it( 'should be editable', function () {

		var content2 = Math.random().toString();

		// create
		randomPage.edit( name, content );

		// edit
		randomPage.edit( name, content2 );

		// check content
		assert.equal( randomPage.heading.getText(), name );
		assert.equal( randomPage.displayedContent.getText(), content2 );

	} );

	it( 'should have history', function () {

		// create
		randomPage.edit( name, content );

		// check
		historyPage.open( name );
		assert.equal( historyPage.comment.getText(), '(Created page with "' + content + '")' );

	} );

} );
