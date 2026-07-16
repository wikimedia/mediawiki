'use strict';

const mockGet = global.mw.Api.prototype.get;
describe( 'Signup', () => {
	beforeEach( () => {
		mockGet.mockReset();
	} );
	it( 'adjusts the username to not trigger the respective warning', () => {
		mockGet.mockResolvedValue( { query: { users: [] } } );
		let callback;
		mw.hook = ( hookname ) => {
			if ( hookname !== 'htmlform.enhance' ) {
				throw new Error( 'Unexpected hook called: ' + hookname );
			}

			return {
				add( callable ) {
					callback = callable;
				}
			};
		};
		const $form = $( '<form>' );
		const $usernameField = $( '<div>' ).addClass( 'cdx-field' ).appendTo( $form );
		const $input = $( '<input>' ).attr( 'id', 'wpName2' ).appendTo( $usernameField );
		const $pwField = $( '<div>' ).addClass( 'cdx-field' ).appendTo( $form );
		$( '<input>' ).attr( 'id', 'wpPassword2' ).appendTo( $pwField );

		require( '../../../resources/src/mediawiki.special.createaccount/signup.js' );

		callback( $form );

		$input.val( ' _ab__cD ' );
		$input[ 0 ].setSelectionRange( 5, 5 );
		$input.trigger( 'input' );

		expect( $input.val() ).toBe( 'Ab  cD ' );
		expect( $input[ 0 ].selectionStart ).toBe( 3 );
	} );
} );
