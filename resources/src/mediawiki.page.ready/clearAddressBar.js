/**
 * Clears the address bar without triggering a route change
 *
 * @param {module:mediawiki.router} router
 * @param {RegExp} [route] only clear if this route is active
 * @ignore
 */
function clearAddressBar( router, route ) {
	if ( !route || route.test( router.getPath() ) ) {
		router.navigateTo( document.title, {
			path: '#',
			useReplaceState: true
		} );
	}
}

/**
 * @memberof module:mediawiki.page.ready
 */
module.exports = clearAddressBar;
