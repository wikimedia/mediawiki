/**
 * @private
 * @class mw.plugin.page.ready
 */

const ID = 'mw-teleport-target';

/**
 * Create a dedicated container element for modals and dialogs.
 *
 * This method appends an new empty div to the end of the body element
 * that can be used by Codex Dialogs and similar components that
 * may need to be displayed on the page.
 *
 * Skins should apply body content styles to this element so that
 * dialogs will use the same styles (font sizes, etc).
 *
 * @return {HTMLDivElement}
 */
module.exports = function () {

	// Create an empty div with appropriate ID
	const target = document.createElement( 'div' );
	target.id = ID;

	// Append the node to the body
	document.body.appendChild( target );

	// Return the node in case other code needs to reference it
	return target;
};
