/*
 * JavaScript for Special:EditWatchlist
 */

/**
 * Replace the submit button action to operate with ajax.
 */
jQuery( function ( $ ) {
	$( '#watchlistedit-submit' ).click( function( e ) {
		var titlesToRemove, params, api;
		titlesToRemove = $.map( $( '.mw-htmlform-flatlist-item input:checked' ).parent()
							.find( '.watchlist-item' ), function( val ){ 
								return $(val).text() 
							} ).join( '|' );
		params = {
			action: 'watch',
			titles: titlesToRemove,
			token: mw.user.tokens.get( 'watchToken' ),
			unwatch: true
		};
		api = new mw.Api();
		var self = this;
		api.post( params,
				function( data ) {
					$(data.watch).each( function( e ){
						var removedItem = this.title;
						var item=$('.watchlist-item a').filter( function( ) {
									return this.title == removedItem; } 
								).css( 'text-decoration', 'line-through' )
								.parents( '.mw-htmlform-flatlist-item' ).children( 'input' )
								.prop( { disabled: true, checked: false } );
					} );
					$(self).prop( {
						'disabled': false,
						'value': mw.msg( 'watchlistedit-normal-submit')
					} );
				},
				function( ) {
					//some error occurred.
					//just re-enable the submit and don't mark the articles as deleted from watchlist
					$(self).prop( {
						'disabled': false,
						'value': mw.msg( 'watchlistedit-normal-submit' )
					} );
				} );
		$(this).prop( {'disabled': true, 'value':  mw.msg( 'watchlistedit-normal-submiting')  } );
		e.preventDefault();
	} );
} );

