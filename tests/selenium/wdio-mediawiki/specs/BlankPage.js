const assert = require( 'assert' ),
	BlankPage = require( './../BlankPage' );

describe( 'BlankPage', function () {
	it( 'should have its title @daily', function () {
		BlankPage.open();

		// check
		assert.strictEqual( BlankPage.heading.getText(), 'Blank page' );
	} );
} );
