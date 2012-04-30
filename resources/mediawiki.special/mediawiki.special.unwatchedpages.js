/*
 * JavaScript for Special:UnwatchedPages
 *
 */
 //set the watch links to use Ajax
 //see bug #17367
( function ( $, mw ) {
	 $( 'a.mw-watchLink' ).click( function( ) {
		var api, watchedLi, link, toWatch;
		api=new mw.Api( );
		watchedLi=$( this ).parents( 'li' );
		link=$( this );
		toWatch=( link.text( ) === mw.msg( 'watch' ) );
		if( toWatch ) {
			api.watch( this.title, function( ) {
				watchedLi.toggleClass( 'mw-watched-item' );
				link.text( mw.msg( 'unwatch' ) );
			} );
		}
		else {
			api.unwatch( this.title, function( ) {
				watchedLi.toggleClass( 'mw-watched-item' );
				link.text( mw.msg( 'watch' ) );
			} );
		}
		return false;
	} );
}( jQuery, mediaWiki ) );
