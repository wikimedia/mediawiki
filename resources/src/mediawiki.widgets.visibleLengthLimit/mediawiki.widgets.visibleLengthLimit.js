( function () {

	var byteLength = require( 'mediawiki.String' ).byteLength,
		codePointLength = require( 'mediawiki.String' ).codePointLength,
		colonSeparator = require( './contentMessages.json' ).colonSeparator;

	/**
	 * @internal
	 * @param {string} lengthLimiter
	 * @param {OO.ui.TextInputWidget} textInputWidget
	 * @param {number} [limit]
	 * @param {Function} [filterFunction]
	 */
	function internalVisibleLimit( lengthLimiter, textInputWidget, limit, filterFunction ) {
		limit = limit || +textInputWidget.$input.attr( 'maxlength' );
		if ( !filterFunction || typeof filterFunction !== 'function' ) {
			filterFunction = undefined;
		}

		var lengthFunction = lengthLimiter === 'byteLimit' ? byteLength : codePointLength;
		function updateCount() {
			var value = textInputWidget.getValue(),
				remaining;
			if ( filterFunction ) {
				value = filterFunction( value );
			}
			remaining = limit - lengthFunction( value );
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
		textInputWidget.$input[ lengthLimiter ]( limit, filterFunction );
	}

	/**
	 * @internal
	 * @param {string} lengthLimiter
	 * @param {OO.ui.TextInputWidget} textInputWidget
	 * @param {OO.ui.DropdownInputWidget} dropdownInputWidget Dropdown input widget
	 * @param {number} [limit]
	 */
	function internalVisibleLimitWithDropdown( lengthLimiter, textInputWidget, dropdownInputWidget, limit ) {
		var filterFunction = function ( input ) {
			var comment = dropdownInputWidget.getValue();
			if ( comment === 'other' ) {
				comment = input;
			} else if ( input !== '' ) {
				// Entry from drop down menu + additional comment
				comment += colonSeparator + input;
			}
			return comment;
		};

		internalVisibleLimit( lengthLimiter, textInputWidget, limit, filterFunction );

		// Keep the remaining counter in sync when reason list changed
		dropdownInputWidget.on( 'change', function () {
			textInputWidget.emit( 'change' );
		} );
	}

	/**
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 * Add a visible byte limit label to a TextInputWidget.
	 *
	 * Uses jQuery#byteLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {number} [limit] Byte limit, defaults to $input's maxlength
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length.
	 */
	mw.widgets.visibleByteLimit = function ( textInputWidget, limit, filterFunction ) {
		internalVisibleLimit( 'byteLimit', textInputWidget, limit, filterFunction );
	};

	/**
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 * Add a visible codepoint (character) limit label to a TextInputWidget.
	 *
	 * Uses jQuery#codePointLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {number} [limit] Code point limit, defaults to $input's maxlength
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length.
	 */
	mw.widgets.visibleCodePointLimit = function ( textInputWidget, limit, filterFunction ) {
		internalVisibleLimit( 'codePointLimit', textInputWidget, limit, filterFunction );
	};

	/**
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 * Add a visible byte limit label to a TextInputWidget/DropdownInputWidget
	 *
	 * Uses jQuery#byteLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {OO.ui.DropdownInputWidget} dropdownInputWidget Dropdown input widget
	 * @param {number} [limit] Code point limit, defaults to $input's maxlength
	 */
	mw.widgets.visibleByteLimitWithDropdown = function ( textInputWidget, dropdownInputWidget, limit ) {
		internalVisibleLimitWithDropdown( 'byteLimit', textInputWidget, dropdownInputWidget, limit );
	};

	/**
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 * Add a visible codepoint (character) limit label to a TextInputWidget/DropdownInputWidget
	 *
	 * Uses jQuery#codePointLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {OO.ui.DropdownInputWidget} dropdownInputWidget Dropdown input widget
	 * @param {number} [limit] Code point limit, defaults to $input's maxlength
	 */
	mw.widgets.visibleCodePointLimitWithDropdown = function ( textInputWidget, dropdownInputWidget, limit ) {
		internalVisibleLimitWithDropdown( 'codePointLimit', textInputWidget, dropdownInputWidget, limit );
	};

}() );
