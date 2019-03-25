( function () {

	var byteLength = require( 'mediawiki.String' ).byteLength,
		codePointLength = require( 'mediawiki.String' ).codePointLength;

	/**
	 * @class mw.widgets
	 */

	/**
	 * Add a visible byte limit label to a TextInputWidget.
	 *
	 * Uses jQuery#byteLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {number} [limit] Byte limit, defaults to $input's maxlength
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length.
	 */
	mw.widgets.visibleByteLimit = function ( textInputWidget, limit, filterFunction ) {
		limit = limit || +textInputWidget.$input.attr( 'maxlength' );
		if ( !filterFunction || typeof filterFunction !== 'function' ) {
			filterFunction = undefined;
		}

		function updateCount() {
			var value = textInputWidget.getValue(),
				remaining;
			if ( filterFunction ) {
				value = filterFunction( value );
			}
			remaining = limit - byteLength( value );
			if ( remaining > 99 ) {
				remaining = '';
			} else {
				remaining = mw.language.convertNumber( remaining );
			}
			textInputWidget.setLabel( remaining );
		}
		textInputWidget.on( 'change', updateCount );
		// Initialise value
		updateCount();

		// Actually enforce limit
		textInputWidget.$input.byteLimit( limit, filterFunction );
	};

	/**
	 * Add a visible codepoint (character) limit label to a TextInputWidget.
	 *
	 * Uses jQuery#codePointLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {number} [limit] Code point limit, defaults to $input's maxlength
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length.
	 */
	mw.widgets.visibleCodePointLimit = function ( textInputWidget, limit, filterFunction ) {
		limit = limit || +textInputWidget.$input.attr( 'maxlength' );
		if ( !filterFunction || typeof filterFunction !== 'function' ) {
			filterFunction = undefined;
		}

		function updateCount() {
			var value = textInputWidget.getValue(),
				remaining;
			if ( filterFunction ) {
				value = filterFunction( value );
			}
			remaining = limit - codePointLength( value );
			if ( remaining > 99 ) {
				remaining = '';
			} else {
				remaining = mw.language.convertNumber( remaining );
			}
			textInputWidget.setLabel( remaining );
		}
		textInputWidget.on( 'change', updateCount );
		// Initialise value
		updateCount();

		// Actually enforce limit
		textInputWidget.$input.codePointLimit( limit, filterFunction );
	};

}() );
