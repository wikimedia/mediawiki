/*
 * JavaScript for Specical:Search
 */

// Emulate HTML5 autofocus behavior in non HTML5 compliant browsers
if ( !( 'autofocus' in document.createElement( 'input' ) ) ) {
	$( 'input[autofocus]' ).focus();
}
