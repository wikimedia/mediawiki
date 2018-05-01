/*!
 * JavaScript for Special:Preferences: editfont field enhancements.
 */
( function ( mw, $ ) {
	$( function () {
		var widget, lastValue;

		try {
			widget = OO.ui.infuse( $( '#mw-input-wpeditfont' ) );
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
}( mediaWiki, jQuery ) );
