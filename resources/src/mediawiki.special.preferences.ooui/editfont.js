/*!
 * JavaScript for Special:Preferences: editfont field enhancements.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var widget, lastValue,
			$target = $root.find( '#mw-input-wpeditfont' );

		if (
			// This preference could theoretically be disabled ($wgHiddenPrefs)
			!$target.length ||
			$target.closest( '.mw-htmlform-autoinfuse-lazy' ).length
		) {
			return;
		}

		widget = OO.ui.infuse( $target );

		// Style options
		widget.dropdownWidget.menu.items.forEach( function ( item ) {
			// The following classes are used here:
			// * mw-editfont-monospace
			// * mw-editfont-sans-serif
			// * mw-editfont-serif
			item.$label.addClass( 'mw-editfont-' + item.getData() );
		} );

		function updateLabel( value ) {
			// Style selected item label
			// The following classes are used here:
			// * mw-editfont-monospace
			// * mw-editfont-sans-serif
			// * mw-editfont-serif
			widget.dropdownWidget.$label
				.removeClass( 'mw-editfont-' + lastValue )
				.addClass( 'mw-editfont-' + value );
			lastValue = value;
		}

		widget.on( 'change', updateLabel );
		updateLabel( widget.getValue() );

	} );
}() );
