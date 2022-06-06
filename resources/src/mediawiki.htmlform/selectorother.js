/*
 * HTMLForm enhancements:
 * Animate the SelectOrOther fields, to only show the text field when 'other' is selected.
 */
( function () {

	/**
	 * @class jQuery.plugin.htmlform
	 */

	/**
	 * jQuery plugin to fade or snap to visible state.
	 *
	 * @param {boolean} [instantToggle=false]
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.goIn = function ( instantToggle ) {
		if ( instantToggle === true ) {
			return this.show();
		}
		return this.stop( true, true ).fadeIn();
	};

	/**
	 * jQuery plugin to fade or snap to hiding state.
	 *
	 * @param {boolean} [instantToggle=false]
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.goOut = function ( instantToggle ) {
		if ( instantToggle === true ) {
			return this.hide();
		}
		return this.stop( true, true ).fadeOut();
	};

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		/**
		 * @ignore
		 * @param {boolean|jQuery.Event} instant
		 */
		function handleSelectOrOther( instant ) {
			var $select = $( this ).find( 'select' ),
				$other = $( this ).find( 'input' );
			$other = $other.add( $other.siblings( 'br' ) );
			if ( $select.val() === 'other' ) {
				$other.goIn( instant );
			} else {
				$other.goOut( instant );
			}
		}

		// Exclude OOUI widgets, since they're infused and SelectWithInputWidget
		// is responsible for this logic.
		$root
			.on( 'change', '.mw-htmlform-select-or-other:not(.oo-ui-widget)', handleSelectOrOther )
			.find( '.mw-htmlform-select-or-other:not(.oo-ui-widget)' )
			.each( function () {
				handleSelectOrOther.call( this, true );
			} );
	} );

}() );
