/*!
 * JavaScript for Special:UserRights
 */
( function ( mw, $ ) {
	var convertmessagebox = require( 'mediawiki.notification.convertmessagebox' ),
		summaryCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
		summaryByteLimit = mw.config.get( 'wgCommentByteLimit' ),
		$wpReason = $( '#wpReason' );

	// Replace successbox with notifications
	convertmessagebox();

	// Dynamically show/hide the "other time" input under each dropdown
	$( '.mw-userrights-nested select' ).on( 'change', function ( e ) {
		$( e.target.parentNode ).find( 'input' ).toggle( $( e.target ).val() === 'other' );
	} );

	// Limit to bytes or UTF-8 codepoints, depending on MediaWiki's configuration
	if ( summaryCodePointLimit ) {
		$wpReason.codePointLimit( summaryCodePointLimit );
	} else if ( summaryByteLimit ) {
		$wpReason.byteLimit( summaryByteLimit );
	}

}( mediaWiki, jQuery ) );
