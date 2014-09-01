/*!
 * JavaScript to update page URL when a redirect is viewed, ensuring that the
 * page is scrolled to the id when it's a redirect with fragment.
 *
 * This is loaded in the top queue, so avoid unnecessary dependencies
 * like mediawiki.Title or mediawiki.Uri.
 */
( function ( mw, $ ) {
	var profile = $.client.profile(),
		canonical = mw.config.get( 'wgInternalRedirectTargetUrl' ),
		fragment = null,
		shouldChangeFragment, index;

	// Clear internal mw.config entries, so that no one tries to depend on them
	mw.config.set( 'wgInternalRedirectTargetUrl', null );

	// Deployment hack for compatibility with cached HTML, remove before 1.24 release
	if ( !canonical ) {
		canonical = mw.config.get( 'wgRedirectToFragment' );
	}

	index = canonical.indexOf( '#' );
	if ( index !== -1 ) {
		fragment = canonical.slice( index );
	}

	// Never override the fragment if the user intended to look at a different section
	shouldChangeFragment = fragment && !location.hash;

	// Replace the whole URL if possible, otherwise just change the fragment
	if ( canonical && history.replaceState ) {
		if ( !shouldChangeFragment ) {
			// If the current page view has a fragment already, don't override it
			canonical = canonical.replace( /#.*$/, '' );
			canonical += location.hash;
		}

		// This will also cause the browser to scroll to given fragment
		history.replaceState( /*data=*/ history.state, /*title=*/ document.title, /*url=*/ canonical );

		// …except for IE 10 and 11. Prod it with a location.hash change.
		if ( shouldChangeFragment && profile.name === 'msie' && profile.versionNumber >= 10 ) {
			location.hash = fragment;
		}

	} else if ( shouldChangeFragment ) {
		if ( profile.layout === 'webkit' && profile.layoutVersion < 420 ) {
			// Released Safari w/ WebKit 418.9.1 messes up horribly
			// Nightlies of 420+ are ok
			return;
		}

		location.hash = fragment;
	}

	if ( shouldChangeFragment && profile.layout === 'gecko' && profile.versionNumber < 34 ) {
		// Firefox < 34 needs to wait until after load, otherwise the window doesn't scroll
		// (https://bugzilla.mozilla.org/show_bug.cgi?id=516293). There's no way to detect this
		// programmatically, so we use version-testing. If the ugly version check hits other
		// Gecko-based browsers, they'll jump twice, but better twice than not at all.
		$( function () {
			if ( location.hash === fragment ) {
				location.hash = fragment;
			}
		} );
	}

}( mediaWiki, jQuery ) );
