const
	SCROLL_TITLE_HOOK = 'vector.page_title_scroll',
	SCROLL_TITLE_CONTEXT_ABOVE = 'scrolled-above-page-title',
	SCROLL_TITLE_CONTEXT_BELOW = 'scrolled-below-page-title',
	SCROLL_TITLE_ACTION = 'scroll-to-top';

/**
 * Fire a hook to be captured by WikimediaEvents for scroll event logging.
 *
 * @param {string} direction the scroll direction
 */
function firePageTitleScrollHook( direction ) {
	if ( direction === 'down' ) {
		mw.hook( SCROLL_TITLE_HOOK ).fire( {
			context: SCROLL_TITLE_CONTEXT_BELOW
		} );
	} else {
		mw.hook( SCROLL_TITLE_HOOK ).fire( {
			context: SCROLL_TITLE_CONTEXT_ABOVE,
			action: SCROLL_TITLE_ACTION
		} );
	}
}

/**
 * Create an observer for showing/hiding feature and for firing scroll event hooks.
 *
 * @param {Function} show functionality for when feature is visible
 * @param {Function} hide functionality for when feature is hidden
 * @return {IntersectionObserver}
 */
function initScrollObserver( show, hide ) {
	return new IntersectionObserver( function ( entries ) {
		if ( !entries[ 0 ].isIntersecting && entries[ 0 ].boundingClientRect.top < 0 ) {
			// Viewport has crossed the bottom edge of the target element.
			show();
		} else {
			// Viewport is above the bottom edge of the target element.
			hide();
		}
	} );
}

module.exports = {
	initScrollObserver,
	firePageTitleScrollHook
};
