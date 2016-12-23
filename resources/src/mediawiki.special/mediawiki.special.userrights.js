/*!
 * JavaScript for Special:UserRights
 */
( function ( $ ) {
	var convertmessagebox = require( 'mediawiki.notification.convertmessagebox' );
	// Replace successbox with notifications
	convertmessagebox();

	// Dynamically show/hide the expiry selection underneath each checkbox
	$( '#mw-userrights-form2 input[type=checkbox]' ).on( 'change', function ( e ) {
		$( '#mw-userrights-nested-' + e.target.id ).toggle( e.target.checked );
	} ).trigger( 'change' );

	// Also dynamically show/hide the "other time" input under each dropdown
	$( '.mw-userrights-nested select' ).on( 'change', function ( e ) {
		$( e.target.parentNode ).find( 'input' ).toggle( $( e.target ).val() === 'other' );
	} ).trigger( 'change' );
}( jQuery ) );
