const assert = require( 'assert' ),
	BlankPage = require( 'wdio-mediawiki/BlankPage' );

describe( 'BlankPage', function () {
	it( 'should have its title', function () {
		BlankPage.open();

		// check
		assert.strictEqual( BlankPage.heading.getText(), 'Blank page' );
	} );
} );
