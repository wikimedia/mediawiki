/**
 * @param {HTMLElement} watchIcon
 * @param {boolean} isWatched
 * @param {string} expiry
 */
const updateWatchIcon = ( watchIcon, isWatched, expiry ) => {
	watchIcon.classList.remove(
		// Vector attaches two icon classes to the element.
		// Remove the mw-ui-icon one rather than managing both.
		'mw-ui-icon-star',
		'mw-ui-icon-unStar',
		'mw-ui-icon-wikimedia-unStar',
		'mw-ui-icon-wikimedia-star',
		'mw-ui-icon-wikimedia-halfStar'
	);

	if ( isWatched ) {
		if ( expiry === 'infinity' ) {
			watchIcon.classList.add( 'mw-ui-icon-wikimedia-unStar' );
		} else {
			watchIcon.classList.add( 'mw-ui-icon-wikimedia-halfStar' );
		}
	} else {
		watchIcon.classList.add( 'mw-ui-icon-wikimedia-star' );
	}
};

const init = () => {
	mw.hook( 'wikipage.watchlistChange' ).add(
		function ( /** @type {boolean} */ isWatched, /** @type {string} */ expiry ) {
			const watchIcons = document.querySelectorAll( '.mw-watchlink .mw-ui-icon' );
			if ( !watchIcons ) {
				return;
			}
			Array.from( watchIcons ).forEach( ( watchIcon ) => {
				updateWatchIcon( /** @type {HTMLElement} */ ( watchIcon ), isWatched, expiry );
			} );
		}
	);
};

module.exports = {
	updateWatchIcon,
	init
};
