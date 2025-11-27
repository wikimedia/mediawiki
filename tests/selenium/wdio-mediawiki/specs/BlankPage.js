import BlankPage from './../BlankPage.js';

describe( 'BlankPage', () => {
	it( 'should have its title', async () => {
		await BlankPage.open();

		// check
		await expect( BlankPage.heading ).toHaveText( 'Blank page' );
	} );
} );
