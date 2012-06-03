/*
 * JavaScript for Special:EditWatchlist
 */

/**
 * Replace the submit button action to operate with ajax.
 */
jQuery( function ( $ ) {
	$( '#watchlistedit-submit' ).click(function(){
		var titlesToRemove, params, api;
		titlesToRemove = $( '.mw-htmlform-flatlist-item input:checked' ).map(function ( ) { 
			return $( this ).parent().find( '.watchlist-item' ).text();
		}).toArray().join( '|' );
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
					$(data.watch).each(function(e){
						var removedItem = this.title;
						var item=$('.watchlist-item a').filter(function(){return this.title == removedItem})
						item.css( 'text-decoration', 'line-through' );
						item.parents( '.mw-htmlform-flatlist-item' ).children( 'input' )
								.attr( { disabled: 'disabled', checked: false } );
					});
					$(self).attr( 'disabled', null );
					$(self).attr( 'value', mw.msg( 'watchlistedit-normal-submit' ) );	
				},
				function( ) {
					//some error occurred. just re-enable the submit and don't mark the articles as deleted from watchlist
					$(self).attr( 'disabled', null );
					$(self).attr( 'value', mw.msg( 'watchlistedit-normal-submit' ) );					
					}  );
		$(this).attr( 'disabled', 'disabled' );
		$(this).attr( 'value', mw.msg( 'watchlistedit-normal-submiting' ) );
		return false;
	});
} );

