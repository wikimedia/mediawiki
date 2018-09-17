/*!
 * JavaScript for Special:Preferences: editfont field enhancements.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var widget, lastValue,
			$target = $root.find( '#mw-input-wpeditfont' );

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

		// Style options
		widget.dropdownWidget.menu.items.forEach( function ( item ) {
			item.$label.addClass( 'mw-editfont-' + item.getData() );
		} );

		function updateLabel( value ) {
			// Style selected item label
			widget.dropdownWidget.$label
				.removeClass( 'mw-editfont-' + lastValue )
				.addClass( 'mw-editfont-' + value );
			lastValue = value;
		}

		widget.on( 'change', updateLabel );
		updateLabel( widget.getValue() );

	} );
}() );
