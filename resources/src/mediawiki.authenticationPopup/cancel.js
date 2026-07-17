const { CANCEL_PAGE_MESSAGE } = require( './constants.js' );

const link = document.querySelector( '.mw-authentication-popup-cancel' );
if ( link ) {
	link.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		if ( window.opener ) {
			window.opener.postMessage( CANCEL_PAGE_MESSAGE, '*' );
		}
	} );
}
