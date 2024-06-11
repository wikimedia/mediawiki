/*
 * HTMLForm enhancements:
 * Add a dynamic max length to the reason field of SelectAndOther.
 */

// cache the separator to avoid require on each keypress
var colonSeparator = require( './contentMessages.json' ).colonSeparator;

mw.hook( 'htmlform.enhance' ).add( ( $root ) => {
	// This checks the length together with the value from the select field
	// When the reason list is changed and the bytelimit is longer than the allowed,
	// nothing is done
	$root
		.find( '.mw-htmlform-select-and-other-field' )
		.each( function () {
			var $reasonList, currentValReasonList, maxlengthUnit, lengthLimiter, widget,
				$this = $( this ),
				$widget = $this.closest( '.oo-ui-widget[data-ooui]' );
			// find the reason list
			$reasonList = $root.find( '#' + $this.data( 'id-select' ) );

			if ( $widget ) {
				mw.loader.using( 'mediawiki.widgets.SelectWithInputWidget', () => {
					widget = OO.ui.Widget.static.infuse( $widget );
					maxlengthUnit = widget.getData().maxlengthUnit;
					lengthLimiter = maxlengthUnit === 'codepoints' ?
						'visibleCodePointLimitWithDropdown' : 'visibleByteLimitWithDropdown';
					mw.widgets[ lengthLimiter ]( widget.textinput, widget.dropdowninput );
				} );
			} else {
				// cache the current selection to avoid expensive lookup
				currentValReasonList = $reasonList.val();

				$reasonList.on( 'change', () => {
					currentValReasonList = $reasonList.val();
				} );

				// Select the function for the length limit
				maxlengthUnit = $this.data( 'mw-maxlength-unit' );
				lengthLimiter = maxlengthUnit === 'codepoints' ? 'codePointLimit' : 'byteLimit';
				$this[ lengthLimiter ]( ( input ) => {
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
