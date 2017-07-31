/*
 * HTMLForm enhancements:
 * Animate the SelectOrOther fields, to only show the text field when 'other' is selected.
 */
( function ( mw, $ ) {

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
			var $other = $root.find( '#' + $( this ).attr( 'id' ) + '-other' );
			$other = $other.add( $other.siblings( 'br' ) );
			if ( $( this ).val() === 'other' ) {
				$other.goIn( instant );
			} else {
				$other.goOut( instant );
			}
		}

		$root
			.on( 'change', '.mw-htmlform-select-or-other', handleSelectOrOther )
			.find( '.mw-htmlform-select-or-other' )
			.each( function () {
				handleSelectOrOther.call( this, true );
			} );
	} );

}( mediaWiki, jQuery ) );
