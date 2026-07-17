const { SUCCESS_PAGE_MESSAGE } = require( './constants.js' );

if ( window.opener ) {
	window.opener.postMessage( SUCCESS_PAGE_MESSAGE, window.origin );
}
