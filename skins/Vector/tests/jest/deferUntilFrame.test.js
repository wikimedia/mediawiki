const deferUntilFrame = require( '../../resources/skins.vector.js/deferUntilFrame.js' );

describe( 'deferUntilFrame.js', () => {
	let /** @type {jest.SpyInstance} */ spy;

	beforeEach( () => {
		spy = jest.spyOn( window, 'requestAnimationFrame' ).mockImplementation( ( cb ) => {
			setTimeout( () => {
				cb( 1 );
			} );

			return 1;
		} );
	} );

	afterEach( () => {
		spy.mockRestore();
	} );

	it( 'does not fire rAF if `0` is passed', ( done ) => {
		deferUntilFrame( () => {
			expect( spy ).toHaveBeenCalledTimes( 0 );
			done();
		}, 0 );
	} );

	it( 'fires rAF the specified number of times', ( done ) => {
		deferUntilFrame( () => {
			expect( spy ).toHaveBeenCalledTimes( 3 );
			done();
		}, 3 );
	} );
} );
