/*!
 * JavaScript for Special:Preferences: signature field enhancements.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var widget,
			$target = $root.find( '#mw-input-wpnickname' );

		if (
			!$target.length ||
			$target.closest( '.mw-htmlform-autoinfuse-lazy' ).length
		) {
			return;
		}

		try {
			widget = OO.ui.infuse( $target );
		} catch ( err ) {
			// This preference could theoretically be disabled ($wgHiddenPrefs)
			return;
		}

		// Add a visible length limit
		mw.widgets.visibleCodePointLimit( widget );

	} );
}() );
