/*!
 * JavaScript to update page URL when a redirect is viewed, ensuring that the
 * page is scrolled to the id when it's a redirect with fragment.
 */
( function () {
	var canonical = mw.config.get( 'wgInternalRedirectTargetUrl' ),
		fragment = null,
		node, shouldChangeFragment, index;

	if ( canonical ) {
		index = canonical.indexOf( '#' );
		if ( index !== -1 ) {
			fragment = canonical.slice( index );
		}

		// Never override the fragment if the user intended to look at a different section
		shouldChangeFragment = fragment && !location.hash;

		if ( !shouldChangeFragment ) {
			// If the current page view has a fragment already, don't override it
			canonical = canonical.replace( /#.*$/, '' );
			canonical += location.hash;
		}

		// Note that this will update the hash in a modern browser, retaining back behaviour
		history.replaceState( /* data= */ history.state, /* title= */ document.title, /* url= */ canonical );
		if ( shouldChangeFragment ) {
			// Specification for history.replaceState() doesn't require browser to scroll,
			// so scroll to be sure (see also T110501).
			node = document.getElementById( fragment.slice( 1 ) );
			if ( node ) {
				node.scrollIntoView();
			}
		}
	}

}() );
