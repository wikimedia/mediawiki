/**
 * JavaScript to scroll the page to an id, when a redirect with fragment is viewed.
 */
( function ( mw, $ ) {
	var profile = $.client.profile(),
		fragment = mw.config.get( 'wgRedirectToFragment' );

	if ( fragment === null ) {
		// nothing to do
		return;
	}

	if ( profile.layout === 'webkit' && profile.layoutVersion < 420 ) {
		// Released Safari w/ WebKit 418.9.1 messes up horribly
		// Nightlies of 420+ are ok
		return;
	}
	if ( !window.location.hash ) {
		window.location.hash = fragment;

		// Mozilla needs to wait until after load, otherwise the window doesn't
		// scroll.  See <https://bugzilla.mozilla.org/show_bug.cgi?id=516293>.
		// There's no obvious way to detect this programmatically, so we use
		// version-testing.  If Firefox fixes the bug, they'll jump twice, but
		// better twice than not at all, so make the fix hit future versions as
		// well.
		if ( profile.layout === 'gecko' ) {
			$( function () {
				if ( window.location.hash === fragment ) {
					window.location.hash = fragment;
				}
			} );
		}
	}
}( mediaWiki, jQuery ) );
