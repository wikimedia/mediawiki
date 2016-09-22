/*
 * HTMLForm enhancements:
 * Add minimal help for date and time fields
 */
( function ( mw ) {

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var supported = {};

		$root
			.find( 'input.mw-htmlform-datetime-field' )
			.each( function () {
				var input,
					type = this.getAttribute( 'type' );

				if ( type !== 'date' && type !== 'time' && type !== 'datetime' ) {
					// WTF?
					return;
				}

				if ( supported[ type ] === undefined ) {
					// Assume that if the browser implements validation (so it
					// rejects "bogus" as a value) then it supports a proper UI too.
					input = document.createElement( 'input' );
					input.setAttribute( 'type', type );
					input.value = 'bogus';
					supported[ type ] = ( input.value !== 'bogus' );
				}

				if ( supported[ type ] ) {
					if ( !this.getAttribute( 'min' ) ) {
						this.setAttribute( 'min', this.getAttribute( 'data-min' ) );
					}
					if ( !this.getAttribute( 'max' ) ) {
						this.setAttribute( 'max', this.getAttribute( 'data-max' ) );
					}
					if ( !this.getAttribute( 'step' ) ) {
						this.setAttribute( 'step', this.getAttribute( 'data-step' ) );
					}
				}
			} );
	} );

}( mediaWiki ) );
