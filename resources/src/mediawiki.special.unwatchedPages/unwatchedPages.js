/*!
 * JavaScript for Special:UnwatchedPages
 */
( function () {
	$( () => {
		$( 'a.mw-watch-link' ).on( 'click', function ( e ) {
			const api = new mw.Api(),
				$link = $( this ),
				$subjectLink = $link.closest( 'li' ).children( 'a' ).eq( 0 ),
				titleParam = mw.util.getParamValue( 'title', $link.attr( 'href' ) ),
				// nice format
				title = mw.Title.newFromText( titleParam ).toText();
			$link.addClass( 'mw-watch-link-disabled' );

			// Preload the notification module for mw.notify
			mw.loader.load( 'mediawiki.notification' );

			let promise;
			// Use the class to determine whether to watch or unwatch
			// eslint-disable-next-line no-jquery/no-class-state
			if ( !$subjectLink.hasClass( 'mw-watched-item' ) ) {
				$link.text( mw.msg( 'watching' ) );
				promise = api.watch( title ).done( () => {
					$subjectLink.addClass( 'mw-watched-item' );
					$link.text( mw.msg( 'unwatch' ) );
					mw.notify( mw.msg( 'addedwatchtext-short', title ) );
				} ).fail( ( code, data ) => {
					$link.text( mw.msg( 'watch' ) );
					mw.notify( api.getErrorMessage( data ), { type: 'error' } );
				} );
			} else {
				$link.text( mw.msg( 'unwatching' ) );
				promise = api.unwatch( title ).done( () => {
					$subjectLink.removeClass( 'mw-watched-item' );
					$link.text( mw.msg( 'watch' ) );
					mw.notify( mw.msg( 'removedwatchtext-short', title ) );
				} ).fail( ( code, data ) => {
					$link.text( mw.msg( 'unwatch' ) );
					mw.notify( api.getErrorMessage( data ), { type: 'error' } );
				} );
			}

			promise.always( () => {
				$link.removeClass( 'mw-watch-link-disabled' );
			} );

			e.preventDefault();
		} );
	} );
}() );
