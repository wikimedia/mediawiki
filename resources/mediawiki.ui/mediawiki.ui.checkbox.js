( function ( mw, $ ) {
	$( function () {
		/**
		 * @class jQuery.plugin.styledCheckbox
		 */

		/**
		 * Adds appropriate onchange event handlers to a checkbox field
		 * that is styled by hiding it and styling a label wrapper.
		 *
		 * Example HTML:
		 *     <label class="pretty-checkbox">
		 *       <input type="checkbox" />&nbsp;
		 *     </label>
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
		 * @param {string} [selector='.mw-ui-styled-checkbox-label'] How to select the label (will be passed to $.closest)
		 * @param {string} [checkedClass='mw-ui-checked'] Class to toggle on the label element on change events
		 * @chainable
		 * @return {jQuery}
		 */
		$.fn.styledCheckbox = function ( selector, checkedClass ) {
			function handleCheckboxChangeEvent() {
				var $check = $( this ),
					enabled = $check.prop( 'checked' ),
					$label = $check.closest( selector );

				$label.toggleClass( checkedClass, enabled );
			}

			selector = selector || '.mw-ui-styled-checkbox-label';
			checkedClass = checkedClass || 'mw-ui-checked';

			this.change( handleCheckboxChangeEvent );
			return this;
		};

		$( '.mw-ui-checkbox' ).styledCheckbox();
	} );
}( mediaWiki, jQuery ) );
