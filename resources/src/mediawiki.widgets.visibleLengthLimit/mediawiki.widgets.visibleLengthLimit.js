( function () {
	'use strict';

	const { byteLength, codePointLength } = require( 'mediawiki.String' );
	const { colonSeparator } = require( './contentMessages.json' );

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

		const lengthFunction = lengthLimiter === 'byteLimit' ? byteLength : codePointLength;
		function updateCount() {
			let value = textInputWidget.getValue(),
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
	 * @param {OO.ui.DropdownInputWidget} dropdownInputWidget
	 * @param {number} [limit]
	 */
	function internalVisibleLimitWithDropdown( lengthLimiter, textInputWidget, dropdownInputWidget, limit ) {
		const filterFunction = function ( input ) {
			let comment = dropdownInputWidget.getValue();
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
		dropdownInputWidget.on( 'change', () => {
			textInputWidget.emit( 'change' );
		} );
	}

	/**
	 * Add a visible byte limit label to a TextInputWidget.
	 *
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 *
	 * Uses {@link module:jquery.lengthLimit.$.fn.byteLimit byteLimit} to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget
	 * @param {number} [limit] Byte limit, defaults to input's `maxlength` attribute
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length
	 */
	mw.widgets.visibleByteLimit = function ( textInputWidget, limit, filterFunction ) {
		internalVisibleLimit( 'byteLimit', textInputWidget, limit, filterFunction );
	};

	/**
	 * Add a visible codepoint (character) limit label to a TextInputWidget.
	 *
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 *
	 * Uses {@link module:jquery.lengthLimit.$.fn.codePointLimit codePointLimit} to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget
	 * @param {number} [limit] Code point limit, defaults to input's `maxlength` attribute
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length
	 */
	mw.widgets.visibleCodePointLimit = function ( textInputWidget, limit, filterFunction ) {
		internalVisibleLimit( 'codePointLimit', textInputWidget, limit, filterFunction );
	};

	/**
	 * Add a visible byte limit label to a TextInputWidget, assuming that the value of the
	 * DropdownInputWidget will be added as a prefix. MediaWiki formats the comment fields for many
	 * actions that way, e.g. for page deletion.
	 *
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 *
	 * Uses {@link module:jquery.lengthLimit.$.fn.byteLimit byteLimit} to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget
	 * @param {OO.ui.DropdownInputWidget} dropdownInputWidget
	 * @param {number} [limit] Code point limit, defaults to input's `maxlength` attribute
	 */
	mw.widgets.visibleByteLimitWithDropdown = function ( textInputWidget, dropdownInputWidget, limit ) {
		internalVisibleLimitWithDropdown( 'byteLimit', textInputWidget, dropdownInputWidget, limit );
	};

	/**
	 * Add a visible codepoint (character) limit label to a TextInputWidget, assuming that the value
	 * of the DropdownInputWidget will be added as a prefix. MediaWiki formats the comment fields for
	 * many actions that way, e.g. for page deletion.
	 *
	 * Loaded from `mediawiki.widgets.visibleLengthLimit` module.
	 *
	 * Uses {@link module:jquery.lengthLimit.$.fn.codePointLimit codePointLimit} to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget
	 * @param {OO.ui.DropdownInputWidget} dropdownInputWidget
	 * @param {number} [limit] Code point limit, defaults to input's `maxlength` attribute
	 */
	mw.widgets.visibleCodePointLimitWithDropdown = function ( textInputWidget, dropdownInputWidget, limit ) {
		internalVisibleLimitWithDropdown( 'codePointLimit', textInputWidget, dropdownInputWidget, limit );
	};

}() );
