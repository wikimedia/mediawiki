/*!
 * JavaScript for Special:RevisionDelete
 */
( function ( mw, $ ) {
	// Add a dynamic byte limit to the reason field
	// When the reason list is changed an the bytelimit
	// is longer than the allowed, nothing is done
	var $reasonList = $( '#wpRevDeleteReasonList' ),
		// cache the current selection to avoid expensive lookup
		currentValReasonList = $reasonList.val(),
		// cache the separator to avoid object creation on each keypress
		colonSeparator = mw.message( 'colon-separator' ).text();

	$reasonList.change( function () {
		currentValReasonList = reasonList.val();
	});

	$( '#wpReason' ).byteLimit( 255, function ( input ) {
		// Should be build the same as in SpecialRevisionDelete::submit
		var comment = currentValReasonList;
		if ( comment !== 'other' && input !== '' ) {
			// Entry from drop down menu + additional comment
			comment += colonSeparator + input;
		} elseif ( comment === 'other' ) {
			comment = input;
		}
		return comment;
	});

}( mediaWiki, jQuery ) );
