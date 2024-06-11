'use strict';

const assert = require( 'assert' );
const BlankPage = require( './../BlankPage' );

describe( 'BlankPage', () => {
	it( 'should have its title @daily', async () => {
		await BlankPage.open();

		// check
		assert.strictEqual( await BlankPage.heading.getText(), 'Blank page' );
	} );
} );
