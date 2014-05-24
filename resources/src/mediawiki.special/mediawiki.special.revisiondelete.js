/*!
 * JavaScript for Special:RevisionDelete
 */
( function ( mw, $ ) {
	// Add a dynamic byte limit to the reason field
	// When the reason list is changed an the bytelimit
	// is longer than the allowed, nothing is done
	var reasonList = $( '#wpRevDeleteReasonList' ),
		// cache the current selection to avoid expensive lookup
		currentValReasonList = reasonList.val(),
		// cache the separator to avoid object creation on each keypress
		colonSeparator = mw.message( 'colon-separator' ).plain();

	reasonList.change( function () {
		currentValReasonList = reasonList.val();
	});

	$( '#wpReason' ).byteLimit( 255, function ( input ) {
		if ( currentValReasonList === 'other' || !currentValReasonList ) {
			return input;
		} else if ( input ) {
			return currentValReasonList + colonSeparator + input;
		} else {
			return currentValReasonList;
		}
	});

}( mediaWiki, jQuery ) );
