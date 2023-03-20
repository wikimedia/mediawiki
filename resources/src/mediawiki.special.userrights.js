/*!
 * JavaScript for Special:UserRights
 */
$( function () {
	// Replace successbox with notifications
	require( 'mediawiki.notification.convertmessagebox' )();

	// Dynamically show/hide the "other time" input under each dropdown
	$( '.mw-userrights-nested select' ).on( 'change', function ( e ) {
		$( e.target.parentNode ).find( 'input' ).toggle( $( e.target ).val() === 'other' );
	} );

	$( '#wpReason' ).codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ) );

	// Disable the watch field for cross-wiki userright changes
	var userrightsInterwikiDelimiter = require( './config.json' ).UserrightsInterwikiDelimiter;
	$( '#username' ).on( 'change', function ( e ) {
		$( '#wpWatch' ).prop( 'disabled', e.target.value.indexOf( userrightsInterwikiDelimiter ) !== -1 );
	} ).trigger( 'change' );
} );
