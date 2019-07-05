/*!
 * Exif metadata display for MediaWiki file uploads
 *
 * Add an expand/collapse link and collapse by default if set to
 * (with JS disabled, user will see all items)
 *
 * See also ImagePage.php#makeMetadataTable (creates the HTML)
 */
( function () {
	$( function () {
		var $tables = $( '.mw_metadata' );
		if ( !$tables.find( '.mw-metadata-collapsible, .collapsable' ).length ) {
			// No collapsible rows present on this page
			return;
		}
		$tables.each( function () {
			var $link,
				expandText = mw.msg( 'metadata-expand' ),
				collapseText = mw.msg( 'metadata-collapse' ),
				$table = $( this );

			$link = $( '<a>' )
				.text( expandText )
				.attr( {
					role: 'button',
					tabindex: 0
				} )
				.on( 'click keypress', function ( e ) {
					if (
						e.type === 'click' ||
						e.type === 'keypress' && e.which === 13
					) {
						// eslint-disable-next-line no-jquery/no-class-state
						if ( $table.hasClass( 'collapsed' ) ) {
							// From collapsed to expanded. Button will now collapse.
							$( this ).text( collapseText );
						} else {
							// From expanded to collapsed. Button will now expand.
							$( this ).text( expandText );
						}
						// eslint-disable-next-line no-jquery/no-class-state
						$table.toggleClass( 'collapsed' );
					}
				} );

			$table.find( 'tbody' ).append(
				$( '<tr>' ).addClass( 'mw-metadata-show-hide-extended' ).append(
					$( '<td>' ).prop( 'colspan', 2 ).append( $link )
				)
			);
		} );
	} );
}() );
