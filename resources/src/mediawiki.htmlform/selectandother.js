/*
 * HTMLForm enhancements:
 * Add a dynamic max length to the reason field of SelectAndOther.
 */

// cache the separator to avoid require on each keypress
const colonSeparator = require( './contentMessages.json' ).colonSeparator;

mw.hook( 'htmlform.enhance' ).add( ( $root ) => {
	// This checks the length together with the value from the select field
	// When the reason list is changed and the bytelimit is longer than the allowed,
	// nothing is done
	$root
		.find( '.mw-htmlform-select-and-other-field' )
		.each( function () {
			const $this = $( this ),
				$widget = $this.closest( '.oo-ui-widget[data-ooui]' );
			// find the reason list
			const $reasonList = $root.find( '#' + $this.data( 'id-select' ) );

			if ( $widget.length ) {
				mw.loader.using( 'mediawiki.widgets.SelectWithInputWidget', () => {
					const widget = OO.ui.Widget.static.infuse( $widget );
					const maxlengthUnit = widget.getData().maxlengthUnit;
					const lengthLimiter = maxlengthUnit === 'codepoints' ?
						'visibleCodePointLimitWithDropdown' : 'visibleByteLimitWithDropdown';
					mw.widgets[ lengthLimiter ]( widget.textinput, widget.dropdowninput );
				} );
			} else {
				// cache the current selection to avoid expensive lookup
				let currentValReasonList = $reasonList.val();

				$reasonList.on( 'change', () => {
					currentValReasonList = $reasonList.val();
				} );

				// Select the function for the length limit
				const maxlengthUnit = $this.data( 'mw-maxlength-unit' );
				const lengthLimiter = maxlengthUnit === 'codepoints' ? 'codePointLimit' : 'byteLimit';
				$this[ lengthLimiter ]( ( input ) => {
					// Should be built the same as in HTMLSelectAndOtherField::loadDataFromRequest
					let comment = currentValReasonList;
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
