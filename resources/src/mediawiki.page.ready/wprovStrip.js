/**
 * Strip the 'wprov' parameter from the URL and return its value.
 *
 * The wprov query parameter is used to track the source of traffic to a page.
 * We remove the wprov parameter from the URL to prevent it from being shared.
 * We return the value so other scripts can still access it if needed.
 *
 * For more details see https://wikitech.wikimedia.org/wiki/Provenance
 *
 * @param {string} [loc] The document location (optional, exposed for testing)
 * @return {string|null} The wprov string
 * @ignore
 */
function stripWprov( loc = window.location.href ) {
	const uri = new URL( loc );
	const wprov = uri.searchParams.get( 'wprov' );
	if ( window.history.replaceState && wprov ) {
		uri.searchParams.delete( 'wprov' );
		// When 'title' is the only remaining parameter, use the canonical URL
		if (
			uri.searchParams.has( 'title' ) &&
			uri.searchParams.size === 1 &&
			uri.pathname === mw.config.get( 'wgScript' )
		) {
			const canonUrl = mw.util.getUrl( uri.searchParams.get( 'title' ) );
			// Make sure that the wiki uses pretty URLs
			if ( !canonUrl.includes( '?' ) ) {
				uri.pathname = canonUrl;
				uri.searchParams.delete( 'title' );
			}
		}
		window.history.replaceState( {}, '', uri.toString() );
	}
	return wprov;
}

module.exports = {
	stripWprov
};
