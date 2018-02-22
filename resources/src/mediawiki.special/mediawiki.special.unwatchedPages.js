/*!
 * JavaScript for Special:UnwatchedPages
 */
( function ( mw, $ ) {
	$( function () {
		$( 'a.mw-watch-link' ).click( function ( e ) {
			var promise,
				api = new mw.Api(),
				$link = $( this ),
				$subjectLink = $link.closest( 'li' ).children( 'a' ).eq( 0 ),
				title = mw.util.getParamValue( 'title', $link.attr( 'href' ) );
			// nice format
			title = mw.Title.newFromText( title ).toText();
			$link.addClass( 'mw-watch-link-disabled' );

			// Preload the notification module for mw.notify
			mw.loader.load( 'mediawiki.notification' );

			// Use the class to determine whether to watch or unwatch
			if ( !$subjectLink.hasClass( 'mw-watched-item' ) ) {
				$link.text( mw.msg( 'watching' ) );
				promise = api.watch( title ).done( function () {
					$subjectLink.addClass( 'mw-watched-item' );
					$link.text( mw.msg( 'unwatch' ) );
					mw.notify( mw.msg( 'addedwatchtext-short', title ) );
				} ).fail( function () {
					$link.text( mw.msg( 'watch' ) );
					mw.notify( mw.msg( 'watcherrortext', title ), { type: 'error' } );
				} );
			} else {
				$link.text( mw.msg( 'unwatching' ) );
				promise = api.unwatch( title ).done( function () {
					$subjectLink.removeClass( 'mw-watched-item' );
					$link.text( mw.msg( 'watch' ) );
					mw.notify( mw.msg( 'removedwatchtext-short', title ) );
				} ).fail( function () {
					$link.text( mw.msg( 'unwatch' ) );
					mw.notify( mw.msg( 'watcherrortext', title ), { type: 'error' } );
				} );
			}

			promise.always( function () {
				$link.removeClass( 'mw-watch-link-disabled' );
			} );

			e.preventDefault();
		} );
	} );
}( mediaWiki, jQuery ) );
