/*!
 * JavaScript for Special:EditTags
 */
( function () {
	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'EditTags' ) {
		return;
	}
	$( () => {
		var $wpReason = $( '#wpReason' );
		var $tagList = $( '#mw-edittags-tag-list' );

		if ( $tagList.length ) {
			$tagList.chosen( {
				/* eslint-disable camelcase */
				placeholder_text_multiple: mw.msg( 'tags-edit-chosen-placeholder' ),
				no_results_text: mw.msg( 'tags-edit-chosen-no-results' )
				/* eslint-enable camelcase */
			} );
		}

		$( '#mw-edittags-remove-all' ).on( 'change', ( e ) => {
			$( '.mw-edittags-remove-checkbox' ).prop( 'checked', e.target.checked );
		} );
		$( '.mw-edittags-remove-checkbox' ).on( 'change', ( e ) => {
			if ( !e.target.checked ) {
				$( '#mw-edittags-remove-all' ).prop( 'checked', false );
			}
		} );

		$wpReason.codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ) );
	} );

}() );
