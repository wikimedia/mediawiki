/*
 * JavaScript for Special:EditWatchlist
 */

/**
 * Replace the submit button action to operate with ajax.
 */
jQuery( function ( $ ) {
	$( '#watchlistedit-submit' ).parents( 'form:first' ).on( 'submit.ajax', function( e ) {
		var titlesToRemove, params, api;
		titlesToRemove = $.map( $( '.mw-htmlform-flatlist-item input:checked' ).parent()
							.find( '.watchlist-item' ), function( val ) {
								return $( val ).text();
							} ).join( '|' );
		params = {
			action: 'watch',
			titles: titlesToRemove,
			token: mw.user.tokens.get( 'watchToken' ),
			unwatch: true
		};
		api = new mw.Api();
		api.post( params,
				{ ok: function( data ) {
					$( data.watch ).each( function( e ) {
						var removedItem = this.title;
						var item=$( '.watchlist-item a' ).filter( function( ) {
									return this.title == removedItem;
								} ).css( 'text-decoration', 'line-through' )
								.parents( '.mw-htmlform-flatlist-item' ).children( 'input' )
								.prop( { disabled: true, checked: false } );
					} );
					$( '#watchlistedit-submit' ).prop( {
						'disabled': false,
						'value': mw.msg( 'watchlistedit-normal-submit' )
					} );
				},
				err: function( ) {
					//some error occurred.
					//re-enable the submit and try to send normal submit
					$( '#watchlistedit-submit' ).prop( {
						'disabled': false,
						'value': mw.msg( 'watchlistedit-normal-submit' )
					} );
					$( '#watchlistedit-submit' ).parents( 'form:first' ).off( 'submit.ajax' ).submit();
				} } );
		$( '#watchlistedit-submit' ).prop( { 'disabled': true, 'value':  mw.msg( 'watchlistedit-normal-submiting')  } );
		e.preventDefault();
	} );
} );