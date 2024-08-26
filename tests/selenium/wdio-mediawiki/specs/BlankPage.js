import BlankPage from './../BlankPage.js';

describe( 'BlankPage', () => {
	it( 'should have its title @daily', async () => {
		await BlankPage.open();

		// check
		await expect( BlankPage.heading ).toHaveText( 'Blank page' );
	} );
} );
