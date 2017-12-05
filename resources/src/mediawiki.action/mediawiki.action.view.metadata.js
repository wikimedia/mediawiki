/*!
 * Exif metadata display for MediaWiki file uploads
 *
 * Add an expand/collapse link and collapse by default if set to
 * (with JS disabled, user will see all items)
 *
 * See also ImagePage.php#makeMetadataTable (creates the HTML)
 */
( function ( mw, $ ) {
	$( function () {
		var $tables = $( '.mw_metadata' );
		if ( !$tables.find( '.mw-metadata-collapsible, .collapsable' ).length ) {
			// No collapsible rows present on this page
			return;
		}
		$tables.each( function () {
			var $table = $( this ),
				$link = $table.find( '.mw-metadata-show-hide-extended a' ),
				expandText = mw.msg( 'metadata-expand' ),
				collapseText = mw.msg( 'metadata-collapse' );

			if ( !$link.length ) {
				$link = $( '<a>' )
					.text( expandText )
					.attr( {
						role: 'button',
						tabindex: 0
					} );
				$table.find( 'tbody' ).append(
					$( '<tr class="mw-metadata-show-hide-extended"></tr>' ).append(
						$( '<td colspan="2"></td>' ).append( $link )
					)
				);
			}
			$link.on( 'click keypress', function ( e ) {
				if (
					e.type === 'click' ||
					e.type === 'keypress' && e.which === 13
				) {
					if ( $table.hasClass( 'collapsed' ) ) {
						// From collapsed to expanded. Button will now collapse.
						$( this ).text( collapseText );
					} else {
						// From expanded to collapsed. Button will now expand.
						$( this ).text( expandText );
					}
					$table.toggleClass( 'collapsed' );
				}
			} );
		} );

		// Initial collapsed state
		// (For back-compat with cached HTML from before ImagePage.php
		// did this by default)
		$tables.addClass( 'collapsed' );
	} );

}( mediaWiki, jQuery ) );
