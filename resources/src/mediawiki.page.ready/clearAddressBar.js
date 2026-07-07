/**
 * Clears the address bar without triggering a route change
 *
 * @memberof module:mediawiki.page.ready
 * @param {module:mediawiki.router} router
 * @param {RegExp} [route] only clear if this route is active
 */
function clearAddressBar( router, route ) {
	if ( !route || route.test( router.getPath() ) ) {
		router.navigateTo( document.title, {
			path: '#',
			useReplaceState: true
		} );
	}
}

module.exports = clearAddressBar;
