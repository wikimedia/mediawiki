'use strict';

const mockGet = global.mw.Api.prototype.get;
const mockPost = global.mw.Api.prototype.post;
global.mw.Api.AbortController = class {
	abort() {}
};
function flushPromises() {
	return new Promise( ( resolve ) => {
		setTimeout( resolve );
	} );
}
describe( 'Signup smoke test', () => {
	let htmlformEnhanceFireCallback;
	beforeEach( () => {
		mockGet.mockReset();
		mockPost.mockReset();
		global.mw.config.get.mockReset();

		mw.hook = ( hookname ) => {
			if ( hookname !== 'htmlform.enhance' ) {
				throw new Error( 'Unexpected hook called: ' + hookname );
			}

			return {
				add( callable ) {
					htmlformEnhanceFireCallback = callable;
				}
			};
		};
		mw.track = () => {};
		mw.message = ( messageKey ) => ( {
			parseDom: () => $( `<div><span>${ messageKey }</span></div>` )
		} );
	} );

	it( 'validates user input for username, password, and confirm password', async () => {
		mockGet.mockResolvedValue( { query: { users: [] } } );
		global.mw.config.get.mockImplementation( ( key ) => ( { wgUserLanguage: 'qqx' }[ key ] ) );

		const $form = $( '<form>' );
		const $usernameField = $( '<div>' ).addClass( 'cdx-field' ).appendTo( $form );
		const $usernameInput = $( '<input>' ).attr( 'id', 'wpName2' ).appendTo( $usernameField );
		const $pwField = $( '<div>' ).addClass( 'cdx-field' ).appendTo( $form );
		const $passwordInput = $( '<input>' ).attr( 'id', 'wpPassword2' ).appendTo( $pwField );
		const $confirmPwField = $( '<div>' ).addClass( 'cdx-field' ).appendTo( $form );
		const $confirmPwInput = $( '<input>' ).attr( 'id', 'wpRetype' ).appendTo( $confirmPwField );
		const $emailField = $( '<div>' ).addClass( 'cdx-field' ).appendTo( $form );
		$( '<input>' ).attr( 'id', 'wpEmail' ).appendTo( $emailField );

		require( '../../../resources/src/mediawiki.special.createaccount/signup.js' );

		htmlformEnhanceFireCallback( $form );

		$usernameInput.val( ' _ab__cD ' );
		$usernameInput[ 0 ].setSelectionRange( 5, 5 );
		$usernameInput.trigger( 'input' );

		expect( $usernameInput.val() ).toBe( 'Ab  cD ' );
		expect( $usernameInput[ 0 ].selectionStart ).toBe( 3 );
		expect( mockGet ).toHaveBeenCalledWith( {
			action: 'query',
			list: 'users',
			ususers: 'Ab cD',
			usprop: 'cancreate',
			formatversion: 2,
			errorformat: 'html',
			errorsuselocal: true,
			uselang: 'qqx'
		}, { signal: undefined } );

		mockPost.mockResolvedValue( { validatepassword: { validity: 'Good' } } );
		$passwordInput.val( 'correct horse battery staple' );
		$passwordInput.trigger( 'blur' );
		expect( mockPost ).toHaveBeenCalledWith( {
			action: 'validatepassword',
			user: $usernameInput.val(),
			password: $passwordInput.val(),
			email: '',
			realname: '',
			formatversion: 2,
			errorformat: 'html',
			errorsuselocal: true,
			uselang: 'qqx'
		}, { signal: undefined } );

		$confirmPwInput.val( 'not the same password' );
		$confirmPwInput.trigger( 'blur' );
		await flushPromises();
		expect( $confirmPwField.html() ).toContain( 'badretype' );

		$confirmPwInput.val( 'correct horse battery staple' );
		$confirmPwInput.trigger( 'blur' );
		await flushPromises();
		expect( $confirmPwField.html() ).not.toContain( 'badretype' );
	} );
} );
