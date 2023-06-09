/** @module PopupNotification */
// Store active notifications to only show one at a time, for use inside clearHints and showHint
const /** @type {Record<string,OoUiPopupWidget>} */ activeNotification = {};

/**
 * Adds and show a popup to the user to point them to the new location of the element
 *
 * @param {HTMLElement} container
 * @param {string} message
 * @param {string} id
 * @param {string[]} [classes]
 * @param {number|false} [timeout]
 * @param {Function} [onDismiss]
 * @return {JQuery.Promise<OoUiPopupWidget|undefined>}
 */
function add( container, message, id, classes = [], timeout = 4000, onDismiss = () => {} ) {
	/**
	 * @type {OoUiPopupWidget}
	 */
	let popupWidget;
	// load oojs-ui if it's not already loaded
	// FIXME: This should be replaced with Codex.
	return mw.loader.using( 'oojs-ui-core' ).then( () => {
		// use existing hint.
		if ( id && activeNotification[ id ] ) {
			return activeNotification[ id ];
		}
		const content = document.createElement( 'p' );
		content.textContent = message;
		popupWidget = new OO.ui.PopupWidget( {
			$content: $( content ),
			padded: true,
			autoClose: timeout !== false,
			head: timeout === false,
			anchor: true,
			align: 'center',
			position: 'below',
			classes: [ 'vector-popup-notification' ].concat( classes ),
			container
		} );
		popupWidget.$element.appendTo( container );
		popupWidget.on( 'closing', () => {
			onDismiss();
		} );
		if ( popupWidget && id ) {
			activeNotification[ id ] = popupWidget;
		}
		return popupWidget;
	} );
}
/**
 * Hides the popup widget
 *
 * @param {OoUiPopupWidget} popupWidget popupWidget from oojs-ui
 * cannot use type because it's not loaded yet
 */
function hide( popupWidget ) {
	popupWidget.toggle( false );
}
/**
 * Shows the popup widget
 *
 * @param {OoUiPopupWidget} popupWidget popupWidget from oojs-ui
 * cannot use type because it's not loaded yet
 * @param {number|false} [timeout] use false if user must dismiss it themselves.
 */
function show( popupWidget, timeout = 4000 ) {
	popupWidget.toggle( true );
	// @ts-ignore https://github.com/wikimedia/typescript-types/pull/40
	popupWidget.toggleClipping( true );
	// hide the popup after timeout ms
	if ( timeout === false ) {
		return;
	}
	setTimeout( () => {
		hide( popupWidget );
	}, timeout );
}

/**
 * Hides all popups
 *
 */
function hideAll() {
	for ( const key in activeNotification ) {
		const popupWidget = activeNotification[ key ];
		hide( popupWidget );
	}
}

module.exports = {
	add,
	hide,
	hideAll,
	show
};
