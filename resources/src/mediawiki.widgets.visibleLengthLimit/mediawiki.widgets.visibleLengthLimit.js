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
	 */
	mw.widgets.visibleByteLimit = function ( textInputWidget, limit ) {
		limit = limit || +textInputWidget.$input.attr( 'maxlength' );

		function updateCount() {
			var remaining = limit - byteLength( textInputWidget.getValue() );
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
		textInputWidget.$input.byteLimit( limit );
	};

	/**
	 * Add a visible codepoint (character) limit label to a TextInputWidget.
	 *
	 * Uses jQuery#codePointLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {number} [limit] Byte limit, defaults to $input's maxlength
	 */
	mw.widgets.visibleCodePointLimit = function ( textInputWidget, limit ) {
		limit = limit || +textInputWidget.$input.attr( 'maxlength' );

		function updateCount() {
			var remaining = limit - codePointLength( textInputWidget.getValue() );
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
		textInputWidget.$input.codePointLimit( limit );
	};

}() );
