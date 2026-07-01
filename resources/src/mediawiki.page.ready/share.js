/**
 * Share a URL using the Web Share API if available, and copy it to the clipboard.
 *
 * @param {Object} shareData
 * @param {string} shareData.url
 * @namespace share
 * @memberof module:mediawiki.page.ready
 */
function share( shareData ) {
	if ( navigator.canShare && navigator.canShare( shareData ) ) {
		navigator.share( shareData );
	}

	// eslint-disable-next-line compat/compat
	navigator.clipboard.writeText( shareData.url );
	mw.notify( mw.msg( 'sharesection-clipboard-message' ) );
}

module.exports = share;
