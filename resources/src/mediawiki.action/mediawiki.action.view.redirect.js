/*!
 * In general, MediaWiki does not ask browsers to resolve wiki page
 * redirects client-side over HTTP. Instead, wiki page redirects are
 * resolved server-side and rendered directly in response to a
 * navigation.
 *
 * This script is responsible for:
 *
 * - Update the address bar to reflect the rendered destination.
 *
 *   Given [[Foo]] redirecting to [[Bar]], when viewing [[Foo]]
 *   the server renders Bar content, with "Bar" as doc title
 *   and with "Bar" in the address bar.
 *
 * - For internal redirect destination that specify a fragment, if
 *   the navigation does not set its own fragment, scroll to the
 *   specified section.
 *
 *   Given [[Foo]] redirecting to [[Bar#Foo]], the browser should
 *   scroll to "Foo", and render address bar Bar#Foo (not Foo, Bar,
 *   or Foo#Foo).
 *
 *   Given [[Foo]] redirecting to [[Bar#Foo]], when navigating to
 *   [[Foo#Quux]], the address bar should reflect Bar#Quux, and
 *   let the native scroll happen, don't override scroll to #Foo.
 */
( function () {
	var canonical = mw.config.get( 'wgInternalRedirectTargetUrl' );
	if ( !canonical ) {
		return;
	}

	var fragment = null;
	if ( location.hash ) {
		// Ignore redirect's own fragment and preserve fragment override in address
		canonical = canonical.replace( /#.*$/, '' ) + location.hash;
	} else {
		var index = canonical.indexOf( '#' );
		fragment = ( index !== -1 ) ? canonical.slice( index ) : null;
	}

	// Update address bar, including browser history.
	// Preserve correct "Back"-button behaviour by using replaceState instead of
	// pushState (or location.hash assignment)
	history.replaceState( history.state, '', canonical );

	if ( fragment ) {
		// Specification for history.replaceState() doesn't require browser to scroll,
		// so scroll to be sure (see also T110501).
		var node = document.getElementById( fragment.slice( 1 ) );
		if ( node ) {
			node.scrollIntoView();
		}
	}

}() );
