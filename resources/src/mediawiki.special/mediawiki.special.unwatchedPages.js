/**
 * JavaScript for Special:UnwatchedPages
 */
( function ( mw, $ ) {
	/**
	 * @return {jQuery.Promise}
	 */
	function toggleWatchLink( $link, title ) {
		var api = new mw.Api(),
			$subjectLink = $link.parents( 'li' ).children( 'a' ).eq( 0 );

		// Use the class to determine whether to watch or unwatch
		if ( !$subjectLink.hasClass( 'mw-watched-item' ) ) {
			$link.text( mw.msg( 'unwatching' ) );
			return api.watch( title ).done( function () {
				$subjectLink.addClass( 'mw-watched-item' );
				$link.text( mw.msg( 'unwatch' ) );
				mw.notify( mw.msg( 'addedwatchtext-short', title ) );
			} );

		}

		$link.text( mw.msg( 'watching' ) );
		return api.unwatch( title ).done( function () {
			$subjectLink.removeClass( 'mw-watched-item' );
			$link.text( mw.msg( 'watch' ) );
			mw.notify( mw.msg( 'removedwatchtext-short', title ) );
		} );
	}

	$( function () {
		$( 'a.mw-watch-link' ).click( function ( e ) {
			var $link = $( this ),
				title = this.title;

			// Disable link whilst we're busy to avoid double handling
			if ( $link.data( 'mwDisabled' ) ) {
				// mw-watch-link-disabled disables pointer-events which prevents the click event
				// from happening in the first place. In older browsers we kill the event here.
				return false;
			}
			$link.data( 'mwDisabled', true ).addClass( 'mw-watch-link-disabled' );

			toggleWatchLink( $link, title ).always( function () {
				$link.data( 'mwDisabled', false ).removeClass( 'mw-watch-link-disabled' );
			} );

			e.preventDefault();
		} );
	} );
}( mediaWiki, jQuery ) );
