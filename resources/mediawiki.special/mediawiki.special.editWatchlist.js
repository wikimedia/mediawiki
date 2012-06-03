/*
 * JavaScript for Special:EditWatchlist
 */

/**
 * Replace the submit button action to operate with ajax.
 */
( function ( mw, $ ) {
	$( '#watchlistedit-submit' ).parents( 'form:first' ).on( 'submit.ajax', function ( e ) {
		var titlesToRemove, params, api;
		titlesToRemove = $.map( $( '.mw-htmlform-flatlist-item input:checked' ), function ( el ) {
			return $( el ).val();
		} ).join( '|' );
		params = {
			action: 'watch',
			titles: titlesToRemove,
			token: mw.user.tokens.get( 'watchToken' ),
			unwatch: '1'
		};
		api = new mw.Api();
		api.ajax( params, { type: 'POST' } ).done( function ( data ) {
				$.each( data.watch, function ( e ) {
					var removedItem = this.title;
					var item = $( '.watchlist-item a' ).filter( function ( ) {
								return this.title === removedItem;
							} ).parents( '.mw-htmlform-flatlist-item' ).fadeOut();
				} );
				$( '#watchlistedit-submit' ).prop( {
					disabled: false,
					value: mw.msg( 'watchlistedit-normal-submit' )
				} );
			} ).fail( function () {
				//some error occurred.
				//re-enable the submit and try to send normal submit
				$( '#watchlistedit-submit' ).prop( {
					disabled: false,
					value: mw.msg( 'watchlistedit-normal-submit' )
				} ).parents( 'form:first' )
				   .off( 'submit.ajax' ).submit();
			} );
		$( '#watchlistedit-submit' ).prop( { disabled: true, value:  mw.msg( 'watchlistedit-normal-submitting' )  } );
		e.preventDefault();
	} );
} )( mediaWiki, jQuery );
