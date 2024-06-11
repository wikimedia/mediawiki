/*!
 * JavaScript for Special:Undelete when results exceed REVISION_HISTORY_LIMIT.
 */
( function () {
	'use strict';
	var $linkSpan = $( '#mw-load-more-revisions' );
	var $link = $( '<a>' );

	$link = $linkSpan.wrapAll( $link ).on( 'click', () => {
		// Get the URL of the last link in the list
		var urlString = $( '.mw-undelete-revlist li:last-child a' ).prop( 'href' );
		// Extract the timestamp
		var timestamp = mw.util.getParamValue( 'timestamp', urlString );
		var $oldList = $( '.mw-undelete-revlist' );
		var $spinner = $.createSpinner( { size: 'large', type: 'block' } );
		var path = mw.util.wikiScript() + '?' + $.param( {
			title: mw.config.get( 'wgPageName' ),
			target: mw.config.get( 'wgRelevantPageName' )
		} );

		$oldList.after( $spinner );

		$.ajax( {
			type: 'GET',
			url: path,
			dataType: 'html',
			data: {
				historyoffset: timestamp,
				action: 'render'
			},
			success: function ( data, status, jqXHR ) {
				$spinner.remove();
				var $newDoc = $.parseHTML( data );
				if ( !$newDoc.length ) {
					return;
				}
				var $newList = $( $newDoc[ 0 ] );
				$oldList.append( $newList.children() );
				if ( jqXHR.status !== 206 ) {
					$link.hide();
				}
			},
			error: function ( data, textStatus, errorMessage ) {
				$spinner.remove();
				$link.show();
				mw.log.error( errorMessage );
			}
		} );
	} );
}() );
