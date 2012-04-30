/**
 * JavaScript for Special:UnwatchedPages
 *
 * set the watch links to use Ajax
 * see bug #17367
*/
( function ( mw, $ ) {
	 $( 'a.mw-watch-link' ).click( function ( e ) {
		var api, $watchedLi, $link, $subjectLink, articleTitle;
		api = new mw.Api();
		$link = $( this );
		$watchedLi = $link.parents( 'li' );
		$subjectLink = $watchedLi.children( 'a:first' );
		articleTitle = this.title;
		//use the text as indication wether to add to watch or unwatch
		if ( !$subjectLink.hasClass( 'mw-watched-item' ) ) {
			api.watch( articleTitle, function () {
				$subjectLink.toggleClass( 'mw-watched-item' );
				$link.text( mw.msg( 'unwatch' ) );
				mw.notify( mw.msg( 'addedwatchtext-short', articleTitle ) );
			} );
		} else {
			api.unwatch( articleTitle, function () {
				$subjectLink.toggleClass( 'mw-watched-item' );
				$link.text( mw.msg( 'watch' ) );
				mw.notify( mw.msg( 'removedwatchtext-short', articleTitle ) );
			} );
		}
		e.preventDefault();
	} );
}( mediaWiki, jQuery ) );
