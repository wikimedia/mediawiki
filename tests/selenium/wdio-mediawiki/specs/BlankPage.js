'use strict';

const assert = require( 'assert' );
const BlankPage = require( './../BlankPage' );

describe( 'BlankPage', function () {
	it( 'should have its title @daily', async function () {
		await BlankPage.open();

		// check
		assert.strictEqual( await BlankPage.heading.getText(), 'Blank page' );
	} );
} );
