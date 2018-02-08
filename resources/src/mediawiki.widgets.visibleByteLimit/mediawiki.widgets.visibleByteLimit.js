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
mediaWiki.widgets.visibleByteLimit = function ( textInputWidget, limit ) {
	limit = limit || +textInputWidget.$input.attr( 'maxlength' );

	function updateCount() {
		textInputWidget.setLabel( ( limit - $.byteLength( textInputWidget.getValue() ) ).toString() );
	}
	textInputWidget.on( 'change', updateCount );
	// Initialise value
	updateCount();

	// Actually enforce limit
	textInputWidget.$input.byteLimit( limit );
};

/**
 * Add a visible character limit label to a TextInputWidget.
 *
 * Uses jQuery#charLimit to enforce the limit.
 *
 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
 * @param {number} [limit] Character limit, defaults to $input's maxlength
 */
mediaWiki.widgets.visibleCharLimit = function ( textInputWidget, limit ) {
	limit = limit || +textInputWidget.$input.attr( 'maxlength' );

	function updateCount() {
		textInputWidget.setLabel( ( limit - $.charLength( textInputWidget.getValue() ) ).toString() );
	}
	textInputWidget.on( 'change', updateCount );
	// Initialise value
	updateCount();

	// Actually enforce limit
	textInputWidget.$input.charLimit( limit );
};
