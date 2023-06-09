/**
 * Collapsible tabs for Vector
 */
function init() {
	const cactionsId = 'p-cactions',
		$cactions = $( '#' + cactionsId ),
		// eslint-disable-next-line no-jquery/no-global-selector
		$tabContainer = $( '#p-views ul' );
	let initialCactionsWidth = function () {
		// HACK: This depends on a discouraged feature of jQuery width().
		// The #p-cactions element is generally hidden by default, but
		// the consumers of this function need to know the width that the
		// "More" menu would consume if it were visible. This means it
		// must not return 0 if hidden, but rather virtually render it
		// and compute its width, then hide it again. jQuery width() does
		// all that for us.
		const width = $cactions.width() || 0;
		initialCactionsWidth = function () {
			return width;
		};
		return width;
	};

	// Bind callback functions to animate our drop down menu in and out
	// and then call the collapsibleTabs function on the menu
	$tabContainer
		.on( 'beforeTabCollapse', function () {
			let expandedWidth;
			// If the dropdown was hidden, show it
			if ( !mw.util.isPortletVisible( cactionsId ) ) {
				mw.util.showPortlet( cactionsId );
				// Now that it is visible, force-render it virtually
				// to get its expanded width, then shrink it 1px before we
				// yield from JS (which means the expansion won't be visible).
				// Then animate from the 1px to the expanded width.
				expandedWidth = $cactions.width();
				// eslint-disable-next-line no-jquery/no-animate
				$cactions
					.css( 'width', '1px' )
					.animate( { width: expandedWidth }, 'normal' );
			}
		} )
		.on( 'beforeTabExpand', function () {
			// If we're removing the last child node right now, hide the dropdown
			if ( $cactions.find( 'li' ).length === 1 ) {
				// eslint-disable-next-line no-jquery/no-animate
				$cactions.animate( { width: '1px' }, 'normal', function () {
					$( this ).attr( 'style', '' );
					mw.util.hidePortlet( cactionsId );
				} );
			}
		} )
		.collapsibleTabs( {
			expandCondition: function ( eleWidth ) {
				// This looks a bit awkward because we're doing expensive queries as late
				// as possible.
				const distance = $.collapsibleTabs.calculateTabDistance();
				// If there are at least eleWidth + 1 pixels of free space, expand.
				// We add 1 because .width() will truncate fractional values but .offset() will not.
				if ( distance >= eleWidth + 1 ) {
					return true;
				} else {
					// Maybe we can still expand? Account for the width of the "Actions" dropdown
					// if the expansion would hide it.
					if ( $cactions.find( 'li' ).length === 1 ) {
						return distance >= eleWidth + 1 - initialCactionsWidth();
					} else {
						return false;
					}
				}
			},
			collapseCondition: function () {
				let collapsibleWidth = 0,
					doCollapse = false;

				// This looks a bit awkward because we're doing expensive queries as late
				// as possible.
				// TODO: The dropdown itself should probably "fold" to just the down-arrow
				// (hiding the text) if it can't fit on the line?

				// Never collapse if there is no overlap.
				if ( $.collapsibleTabs.calculateTabDistance() >= 0 ) {
					return false;
				}

				// Always collapse if the "More" button is already shown.
				if ( mw.util.isPortletVisible( cactionsId ) ) {
					return true;
				}

				// If we reach here, this means:
				// 1. #p-cactions is currently empty and invisible (e.g. when logged out),
				// 2. and, there is at least one li.collapsible link in #p-views, as asserted
				//    by handleResize() before calling here. Such link exists e.g. as
				//    "View history" on articles, but generally not on special pages.
				// 3. and, the left-navigation and right-navigation are overlapping
				//    each other, e.g. when making the window very narrow, or if a gadget
				//    added a lot of tabs.
				$tabContainer.children( 'li.collapsible' ).each( function ( _index, element ) {
					collapsibleWidth += $( element ).width() || 0;
					if ( collapsibleWidth > initialCactionsWidth() ) {
						// We've found one or more collapsible links that are wider
						// than the "More" menu would be if it were made visible,
						// which means it is worth doing a collapsing.
						doCollapse = true;
						// Stop this possibly expensive loop the moment the condition is met once.
						return false;
					}
					return;
				} );
				return doCollapse;
			}
		} );
}

module.exports = Object.freeze( { init: init } );
