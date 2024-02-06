/**
 * @private
 * @class mw.plugin.page.ready
 */

const ID = 'mw-teleport-target';

const target = document.createElement( 'div' );
target.id = ID;

/**
 * Manages a dedicated container element for modals and dialogs.
 *
 * This creates an empty div and attaches it to the end of the body element.
 * This div can be used by Codex Dialogs and similar components that
 * may need to be displayed on the page.
 *
 * Skins should apply body content styles to this element so that
 * dialogs will use the same styles (font sizes, etc).
 *
 * @ignore
 * @return {Object}
 * @return {HTMLDivElement} return.target The div element
 * @return {Function} return.attach Call this function to attach the div to the <body>
 */
module.exports = {
	target,
	attach() {
		document.body.appendChild( target );
	}
};
