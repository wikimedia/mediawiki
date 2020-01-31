/*!
 * JavaScript for Special:Preferences: signature field enhancements.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var widget,
			$target = $root.find( '#mw-input-wpnickname' );

		if (
			// This preference could theoretically be disabled ($wgHiddenPrefs)
			!$target.length ||
			$target.closest( '.mw-htmlform-autoinfuse-lazy' ).length
		) {
			return;
		}

		widget = OO.ui.infuse( $target );

		// Add a visible length limit
		mw.widgets.visibleCodePointLimit( widget );

	} );
}() );
