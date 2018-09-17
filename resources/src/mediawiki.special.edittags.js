/*!
 * JavaScript for Special:EditTags
 */
( function () {
	$( function () {
		var summaryCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
			summaryByteLimit = mw.config.get( 'wgCommentByteLimit' ),
			$wpReason = $( '#wpReason' ),
			$tagList = $( '#mw-edittags-tag-list' );

		if ( $tagList.length ) {
			$tagList.chosen( {
				/* eslint-disable camelcase */
				placeholder_text_multiple: mw.msg( 'tags-edit-chosen-placeholder' ),
				no_results_text: mw.msg( 'tags-edit-chosen-no-results' )
				/* eslint-enable camelcase */
			} );
		}

		$( '#mw-edittags-remove-all' ).on( 'change', function ( e ) {
			$( '.mw-edittags-remove-checkbox' ).prop( 'checked', e.target.checked );
		} );
		$( '.mw-edittags-remove-checkbox' ).on( 'change', function ( e ) {
			if ( !e.target.checked ) {
				$( '#mw-edittags-remove-all' ).prop( 'checked', false );
			}
		} );

		// Limit to bytes or UTF-8 codepoints, depending on MediaWiki's configuration
		// use maxLength because it's leaving room for log entry text.
		if ( summaryCodePointLimit ) {
			$wpReason.codePointLimit();
		} else if ( summaryByteLimit ) {
			$wpReason.byteLimit();
		}
	} );

}() );
