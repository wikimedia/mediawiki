const features = require( './features.js' );
const popupNotification = require( './popupNotification.js' );
const LIMITED_WIDTH_FEATURE_NAME = 'limited-width';
const TOGGLE_ID = 'toggleWidth';

/**
 * Sets data attribute for click tracking purposes.
 *
 * @param {HTMLElement} toggleBtn
 */
function setDataAttribute( toggleBtn ) {
	toggleBtn.dataset.eventName = features.isEnabled( LIMITED_WIDTH_FEATURE_NAME ) ?
		'limited-width-toggle-off' : 'limited-width-toggle-on';
}

/**
 * Gets appropriate popup text based off the limited width feature flag
 *
 * @return {string}
 */
function getPopupText() {
	const label = features.isEnabled( LIMITED_WIDTH_FEATURE_NAME ) ?
		'vector-limited-width-toggle-off-popup' : 'vector-limited-width-toggle-on-popup';
	// possible messages:
	// * vector-limited-width-toggle-off-popup
	// * vector-limited-width-toggle-on-popup
	return mw.msg( label );
}

/**
 * adds a toggle button
 */
function init() {
	const settings = /** @type {HTMLElement|null} */ ( document.querySelector( '.vector-settings' ) );
	const toggle = /** @type {HTMLElement|null} */ ( document.querySelector( '.vector-limited-width-toggle' ) );
	const toggleIcon = /** @type {HTMLElement|null} */ ( document.querySelector( '.vector-limited-width-toggle .mw-ui-icon' ) );

	if ( !settings || !toggle || !toggleIcon ) {
		return;
	}

	setDataAttribute( toggle );

	/**
	 * @param {string} id this allows us to group notifications making sure only one is visible
	 *  at any given time. All existing popups associated with ID will be removed.
	 * @param {number|false} timeout
	 */
	const showPopup = ( id, timeout = 4000 ) => {
		popupNotification.add( settings, getPopupText(), id, [], timeout )
			.then( ( popupWidget ) => {
				if ( popupWidget ) {
					popupNotification.show( popupWidget, timeout );
				}
			} );
	};

	toggle.addEventListener( 'click', function () {
		const isLimitedWidth = features.isEnabled( LIMITED_WIDTH_FEATURE_NAME );
		const oldIcon = isLimitedWidth ? 'fullScreen' : 'exitFullscreen';
		const newIcon = isLimitedWidth ? 'exitFullscreen' : 'fullScreen';

		features.toggle( LIMITED_WIDTH_FEATURE_NAME );
		setDataAttribute( toggle );
		toggleIcon.classList.remove( `mw-ui-icon-wikimedia-${oldIcon}` );
		toggleIcon.classList.add( `mw-ui-icon-wikimedia-${newIcon}` );
		window.dispatchEvent( new Event( 'resize' ) );
		if ( isLimitedWidth ) {
			// Now is full width, show notification
			showPopup( TOGGLE_ID );
		}
	} );
}

module.exports = init;
