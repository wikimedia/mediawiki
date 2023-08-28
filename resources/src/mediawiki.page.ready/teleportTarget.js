/**
 * @private
 * @class mw.plugin.page.ready
 */

const ID = 'mw-teleport-target';

/**
 * Setup a dedicated container element for any modal or dialog
 * elements which may need to be displayed on the page. This
 * method appends an empty div to the end of the body tag
 * that can be used by Codex Dialogs and similar components.
 *
 * Skins should apply body content styles to this element so that
 * dialogs will use the same styles (font sizes, etc).
 *
 * @return {HTMLDivElement}
 */
module.exports = function () {

	// Create an empty div with appropriate ID
	const target = document.createElement( 'div' );
	target.setAttribute( 'id', ID );

	// Append the node to the body
	document.body.appendChild( target );

	// Return the node in case other code needs to reference it
	return target;
};
