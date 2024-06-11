/*!
 * Add portlet link to toggle all collapsibles created by
 * the jquery.makeCollapsible module.
 */
let toggleAll;

mw.hook( 'wikipage.content' ).add( () => {
	// return early if the link was already added
	if ( toggleAll ) {
		return;
	}
	// return early if there are no collapsibles within the parsed page content
	if ( !document.querySelector( '#mw-content-text .mw-parser-output .mw-collapsible' ) ) {
		return;
	}

	// create portlet link for expand/collapse all
	const portletLink = mw.util.addPortletLink(
		'p-tb',
		'#',
		mw.msg( 'collapsible-expand-all-text' ),
		't-collapsible-toggle-all',
		mw.msg( 'collapsible-expand-all-tooltip' )
	);
	// return early if no link was added (e.g. no toolbox)
	if ( !portletLink ) {
		return;
	}

	// set up the toggle link
	toggleAll = portletLink.querySelector( 'a' );
	toggleAll.setAttribute( 'role', 'button' );

	// initially treat as collapsed
	toggleAll.setAttribute( 'aria-expanded', 'false' );
	let allExpanded = false;

	// on click, expand/collapse all collapsibles, then prepare to do the opposite on the next click
	toggleAll.addEventListener( 'click', ( e ) => {
		// Prevent scrolling
		e.preventDefault();
		// expand
		if ( !allExpanded ) {
			const collapsed = document.querySelectorAll( '#mw-content-text .mw-parser-output .mw-made-collapsible.mw-collapsed' );
			Array.prototype.forEach.call( collapsed, ( collapsible ) => {
				$( collapsible ).data( 'mw-collapsible' ).expand();
			} );
			toggleAll.textContent = mw.msg( 'collapsible-collapse-all-text' );
			toggleAll.title = mw.msg( 'collapsible-collapse-all-tooltip' );
			toggleAll.setAttribute( 'aria-expanded', 'true' );
			allExpanded = true;
		// collapse
		} else {
			const expanded = document.querySelectorAll( '#mw-content-text .mw-parser-output .mw-made-collapsible:not( .mw-collapsed )' );
			Array.prototype.forEach.call( expanded, ( collapsible ) => {
				$( collapsible ).data( 'mw-collapsible' ).collapse();
			} );
			toggleAll.textContent = mw.msg( 'collapsible-expand-all-text' );
			toggleAll.title = mw.msg( 'collapsible-expand-all-tooltip' );
			toggleAll.setAttribute( 'aria-expanded', 'false' );
			allExpanded = false;
		}
	} );
} );
