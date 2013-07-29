( function ( mw, $ ) {
	$( function () {
		/**
		 * @class jQuery.plugin.styledCheckbox
		 */

		/**
		 * Adds appropriate onchange event handler to a checkbox field
		 * that is styled by hiding it and styling a wrapper around it.
		 *
		 * Example HTML:
		 *     <span class="pretty-checkbox">
		 *       <input type="checkbox" />&nbsp;
		 *     </span>
		 * CSS:
		 *     .pretty-checkbox {
		 *         padding: 2px 8px;
		 *         border: 1px solid grey;
		 *     }
		 *
		 *     .pretty-checkbox.mw-ui-checked {
		 *         background-color: blue;
		 *     }
		 *
		 *     .pretty-checkbox input[type=checkbox] {
		 *         display: none;
		 *     }
		 * JS:
		 *     $( '.pretty-checkbox input[type=checkbox]' ).styledCheckbox( '.pretty-checkbox' );
		 *
		 * @method
		 * @param {string} [selector='.mw-ui-styled-checkbox-label'] How to select the wrapper element
		 * @param {string} [checkedClass='mw-ui-checked'] Class to toggle on the wrapper on change events
		 * @chainable
		 * @return {jQuery}
		 */
		$.fn.styledCheckbox = function ( selector, checkedClass ) {
			selector = selector || '.mw-ui-styled-checkbox-label';
			checkedClass = checkedClass || 'mw-ui-checked';

			function handleCheckboxChangeEvent() {
				var $box = $( this );

				$( this ).toggleClass( checkedClass );
			}

			this.change( handleCheckboxChangeEvent );
			return this;
		};

		$( '.mw-ui-styled-checkbox-label' ).styledCheckbox();
	} );
}( mediaWiki, jQuery ) );
