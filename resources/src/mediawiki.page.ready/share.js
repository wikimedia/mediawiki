/**
 * Share data using the Web Share API if available, and copy any URL to the clipboard.
 *
 * @param {Object} shareData
 * @param {string} [shareData.url] Required unless files are given
 * @param {string} [shareData.title]
 * @param {string} [shareData.text]
 * @param {File[]} [shareData.files]
 * @return {Promise<boolean>} Whether the reader completed the share. Resolves false when the
 *  browser can't share the data or the reader cancels; rejects on other errors.
 * @namespace share
 * @memberof module:mediawiki.page.ready
 */
function share( shareData ) {
	let result = Promise.resolve( false );
	if ( navigator.canShare && navigator.canShare( shareData ) ) {
		result = navigator.share( shareData ).then( () => true, ( err ) => {
			if ( err.name === 'AbortError' ) {
				return false;
			}
			throw err;
		} );
	}

	if ( shareData.url ) {
		navigator.clipboard.writeText( shareData.url );
		mw.notify( mw.msg( 'sharesection-clipboard-message' ) );
	}
	return result;
}

module.exports = share;
