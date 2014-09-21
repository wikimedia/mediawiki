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
			// Disable link whilst we're busy to avoid double handling
			if ( $link.data( 'mwDisabled' ) ) {
				// mw-watch-link-disabled disables pointer-events which prevents the click event
				// from happening in the first place. In older browsers we kill the event here.
				return false;
			}
			$link.data( 'mwDisabled', true ).addClass( 'mw-watch-link-disabled' );

			// Use the class to determine whether to watch or unwatch
			if ( !$subjectLink.hasClass( 'mw-watched-item' ) ) {
				$link.text( mw.msg( 'watching' ) );
				promise = api.watch( title ).done( function () {
					$subjectLink.addClass( 'mw-watched-item' );
					$link.text( mw.msg( 'unwatch' ) );
					mw.notify( mw.msg( 'addedwatchtext-short', title ) );
				} ).fail( function () {
					$link.text( mw.msg( 'watch' ) );
					mw.notify( mw.msg( 'watcherrortext', title ) );
				} );
			} else {
				$link.text( mw.msg( 'unwatching' ) );
				promise = api.unwatch( title ).done( function () {
					$subjectLink.removeClass( 'mw-watched-item' );
					$link.text( mw.msg( 'watch' ) );
					mw.notify( mw.msg( 'removedwatchtext-short', title ) );
				} ).fail( function () {
					$link.text( mw.msg( 'unwatch' ) );
					mw.notify( mw.msg( 'watcherrortext', title ) );
				} );
			}

			promise.always( function () {
				$link.data( 'mwDisabled', false ).removeClass( 'mw-watch-link-disabled' );
			} );

			e.preventDefault();
		} );
	} );
}( mediaWiki, jQuery ) );
