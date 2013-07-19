( function ( mw, $ ) {
	$( function () {
		/*
		 * Adds appropriate onchange event handlers to a checkbox field
		 * that is styled by hiding it and styling a label wrapper.
		 *
		 * Example HTML:
		 *     <label class="mw-ui-styled-checkbox-label">
		 *       <input type="checkbox" />&nbsp;
		 *     </label>
		 *
		 * The mediawiki.ui module's styles, and the mediawiki.ui.js module's
		 * scripts, will do this in a sane way automatically.
		 */
		function handleCheckboxChangeEvent( e ) {
			var $label,
				$target = $( e.target );

			if ( !$target.is( 'input[type=checkbox]' ) || !$target.parent().is( selector ) ) {
				return;
			}

			$label = $target.closest( selector );
			$label.toggleClass( checkedClass, $target.prop( 'checked' ) );
		}

		function handleKeypressEvent( e ) {
			var $check,
				$target = $( e.target );

			if ( !$target.is( selector ) ) {
				return;
			}

			if ( e.charCode === 32 ) {
				$check = $target.find( 'input[type=checkbox]' );
				$check.prop( 'checked', true );
				$check.change();
			}
		}

		var selector = '.mw-ui-styled-checkbox-label',
			checkedClass = 'mw-ui-checked';

		$( document )
			.on( 'keypress', handleKeypressEvent )
			.on( 'change', handleCheckboxChangeEvent );
	} );
}( mediaWiki, jQuery ) );
