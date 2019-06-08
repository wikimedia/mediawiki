/*
 * HTMLForm enhancements:
 * Add a dynamic max length to the reason field of SelectAndOther.
 */
( function () {

	// cache the separator to avoid object creation on each keypress
	var colonSeparator = mw.message( 'colon-separator' ).text();

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		// This checks the length together with the value from the select field
		// When the reason list is changed and the bytelimit is longer than the allowed,
		// nothing is done
		$root
			.find( '.mw-htmlform-select-and-other-field' )
			.each( function () {
				var $reasonList, currentValReasonList, maxlengthUnit, lengthLimiter, widget,
					$this = $( this ),
					$widget = $this.closest( '.oo-ui-widget[data-ooui]' );

				if ( $widget ) {
					mw.loader.using( 'mediawiki.widgets.SelectWithInputWidget', function () {
						widget = OO.ui.Widget.static.infuse( $widget );
						maxlengthUnit = widget.getData().maxlengthUnit;
						lengthLimiter = maxlengthUnit === 'codepoints' ? 'codePointLimit' : 'byteLimit';
						widget.textinput.$input[ lengthLimiter ]( function ( input ) {
							// Should be built the same as in HTMLSelectAndOtherField::loadDataFromRequest
							var comment = widget.dropdowninput.getValue();
							if ( comment === 'other' ) {
								comment = input;
							} else if ( input !== '' ) {
								// Entry from drop down menu + additional comment
								comment += colonSeparator + input;
							}
							return comment;
						} );
					} );
				} else {
					// find the reason list
					$reasonList = $root.find( '#' + $this.data( 'id-select' ) );
					// cache the current selection to avoid expensive lookup
					currentValReasonList = $reasonList.val();

					$reasonList.on( 'change', function () {
						currentValReasonList = $reasonList.val();
					} );

					// Select the function for the length limit
					maxlengthUnit = $this.data( 'mw-maxlength-unit' );
					lengthLimiter = maxlengthUnit === 'codepoints' ? 'codePointLimit' : 'byteLimit';
					$this[ lengthLimiter ]( function ( input ) {
						// Should be built the same as in HTMLSelectAndOtherField::loadDataFromRequest
						var comment = currentValReasonList;
						if ( comment === 'other' ) {
							comment = input;
						} else if ( input !== '' ) {
							// Entry from drop down menu + additional comment
							comment += colonSeparator + input;
						}
						return comment;
					} );
				}
			} );
	} );

}() );
