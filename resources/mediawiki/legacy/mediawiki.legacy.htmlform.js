/*
 * Legacy emulation for the now depricated skins/common/htmlform.js
 */

( function( $, mw ) {

/* Extension */

$.extend( true, mw.legacy, {
	
	/* Global Variables */
	
	'htmlforms': {
		'selectOrOtherSelectChanged': function( e ) {
			var select;
			if ( !e ) {
				e = window.event;
			}
			if ( e.target ) {
				select = e.target;
			} else if ( e.srcElement ) {
				select = e.srcElement;
			}
			// Defeat Safari bug
			if ( select.nodeType == 3 ) {
				select = select.parentNode;
			}
			var id = select.id;
			var textbox = document.getElementById( id + '-other' );
			if ( select.value == 'other' ) {
				textbox.disabled = false;
			} else {
				textbox.disabled = true;
			}
		}
	}
} );

/* Initialization */

$( document ).ready( function() {
	// Find select-or-other fields
	$( 'select .mw-htmlform-select-or-other' ).each( function() {
		$(this).change( function() { mw.legacy.htmlforms.selectOrOtherSelectChanged(); } );
		// Use a fake event to update it.
		mw.legacy.htmlforms.selectOrOtherSelectChanged( { 'target': $(this).get( 0 ) } );
	} );
} );

} )( jQuery, mediaWiki );