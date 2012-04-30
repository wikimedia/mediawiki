/*
 * JavaScript for Special:UnwatchedPages
 *
 */
 //set the watch links to use Ajax
 //see bug #17367
( function ( $, mw ) {
	 $( 'a.mw-watch-link' ).click( function () {
		var api, $watchedLi, $link, $subjectLink, $confirmMsg, articleTitle;
		api = new mw.Api();
		$watchedLi = $( this ).parents( 'li' );
		$subjectLink = $watchedLi.children( 'a:first' );
		$link = $( this );
		$confirmMsg = $watchedLi.children( '.confirmMsg' );
		if ( $confirmMsg.length === 0 ) {
			$confirmMsg = $( '<span>', { 'class': 'confirmMsg' } ).appendTo( $watchedLi );
		}
		articleTitle = this.title;
		//use the text as indication wether to add to watch or unwatch
		if ( $link.text() === mw.msg( 'watch' ) ) {
			api.watch( articleTitle, function () {
				$subjectLink.toggleClass( 'mw-watched-item' );
				$link.text( mw.msg( 'unwatch' ) );
				$confirmMsg.text( mw.msg( 'addedwatchtext-short', articleTitle ) );
			} );
		} else {
			api.unwatch( articleTitle, function () {
				$subjectLink.toggleClass( 'mw-watched-item' );
				$link.text( mw.msg( 'watch' ) );
				$confirmMsg.text( mw.msg( 'removedwatchtext-short', articleTitle ) );
			} );
		}
		return false;
	} );
}( jQuery, mediaWiki ) );
