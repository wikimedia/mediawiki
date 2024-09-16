'use strict';

const BlankPage = require( './../BlankPage' );

describe( 'BlankPage', () => {
	it( 'should have its title @daily', async () => {
		await BlankPage.open();

		// check
		await expect( await BlankPage.heading ).toHaveText( 'Blank page' );
	} );
} );
