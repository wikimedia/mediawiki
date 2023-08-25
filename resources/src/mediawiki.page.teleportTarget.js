/**
 * Setup a dedicated container element for any modal or dialog
 * elements which may need to be displayed on the page. This
 * script appends an empty div to the end of the body tag
 * that can be used by Codex Dialogs and similar components.
 *
 * Skins should apply body content styles to this element so that
 * dialogs will use the same styles (font sizes, etc).
 *
 * When this script runs, it immediately adds the div to the
 * end of the body tag. Additionally, it makes the created
 * DOM node available via module.exports, in case other code
 * needs to require it (to spare developers from remembering
 * the exact HTML ID for example).
 */
( function () {
	const ID = 'mw-teleport-target';

	// Create an empty div with appropriate ID
	const target = document.createElement( 'div' );
	target.setAttribute( 'id', ID );

	// Append the node to the body
	document.body.appendChild( target );

	// Export the node in case other code needs to reference it
	module.exports = target;
}() );
