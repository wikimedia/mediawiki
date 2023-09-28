/**
 * mediawiki.toggleAllCollapsibles
 *
 * add portlet link to toggle all collapsibles created by
 * jQuery.plugin.makeCollapsible
 *
 */
( function () {
	// @param {jQuery} $collapsible an element that has been made collapsible
	const addCollapsibleAll = function ( $collapsible ) {

		// get toolbox and page content
		const toolboxId = 'p-tb',
			toolbox = document.getElementById( toolboxId );

		// return early if there isn't a toolbox
		if ( !toolbox ) {
			return;
		}
		// return early if there are no collapsibles within the parsed page content
		if ( $collapsible.closest( '#mw-content-text .mw-parser-output' ).length === 0 ) {
			return;
		}

		const portletId = 't-collapsible-toggle-all';

		// return early if the link was already added
		let portletLink = document.getElementById( portletId );
		if ( portletLink ) {
			return;
		}

		// create portlet link for expand/collapse all
		portletLink = mw.util.addPortletLink(
			toolboxId,
			'#',
			mw.msg( 'collapsible-expand-all-text' ),
			portletId,
			mw.msg( 'collapsible-expand-all-tooltip' )
		);

		// return early if no link was added
		if ( !portletLink ) {
			return;
		}

		// set up the toggle link
		const toggleAll = portletLink.querySelector( 'a' );
		toggleAll.setAttribute( 'role', 'button' );

		// initially treat as collapsed
		toggleAll.setAttribute( 'aria-expanded', 'false' );
		let allExpanded = false;

		// on click, expand/collapse all collapsibles, then prepare to do the opposite on the next click
		toggleAll.addEventListener( 'click', function ( e ) {
			// Prevent scrolling
			e.preventDefault();
			// expand
			if ( !allExpanded ) {
				const collapsed = document.querySelectorAll( '#mw-content-text .mw-parser-output .mw-made-collapsible.mw-collapsed' );
				Array.prototype.forEach.call( collapsed, function ( collapsible ) {
					$( collapsible ).data( 'mwCollapsible' ).expand();
				} );
				toggleAll.innerText = mw.msg( 'collapsible-collapse-all-text' );
				toggleAll.title = mw.msg( 'collapsible-collapse-all-tooltip' );
				toggleAll.setAttribute( 'aria-expanded', 'true' );
				allExpanded = true;
			// collapse
			} else {
				const expanded = document.querySelectorAll( '#mw-content-text .mw-parser-output .mw-made-collapsible:not( .mw-collapsed )' );
				Array.prototype.forEach.call( expanded, function ( collapsible ) {
					$( collapsible ).data( 'mwCollapsible' ).collapse();
				} );
				toggleAll.innerText = mw.msg( 'collapsible-expand-all-text' );
				toggleAll.title = mw.msg( 'collapsible-expand-all-tooltip' );
				toggleAll.setAttribute( 'aria-expanded', 'false' );
				allExpanded = false;
			}
		} );
		// only run once
		mw.hook( 'wikipage.collapsibleContent' ).remove( addCollapsibleAll );
	};

	// add listener to collapsible content
	mw.hook( 'wikipage.collapsibleContent' ).add( addCollapsibleAll );

}() );
