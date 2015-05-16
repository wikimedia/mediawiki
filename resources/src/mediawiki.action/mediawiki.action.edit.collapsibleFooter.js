( function ( mw, $ ) {
	/**
	 * Make a footer area collapsible.
	 *
	 * @param {jQuery} $list Footer area
	 * @param {jQuery} $toggler Expand/collapse button
	 * @param {string} cookieName Name of the cookie used to remember state
	 * @private
	 */
	function makeFooterCollapsible( $list, $toggler, cookieName ) {
		// Collapsed by default
		var isCollapsed = mw.cookie.get( cookieName ) !== 'expanded';

		// Style the toggler with an arrow icon and add a tabIndex and a role for accessibility
		$toggler.addClass( 'mw-editfooter-toggler' ).prop( 'tabIndex', 0 ).attr( 'role', 'button' );
		$list.addClass( 'mw-editfooter-list' );

		$list.makeCollapsible( {
			$customTogglers: $toggler,
			linksPassthru: true,
			plainMode: true,
			collapsed: isCollapsed
		} );

		$toggler.addClass( isCollapsed ? 'mw-icon-arrow-collapsed' : 'mw-icon-arrow-expanded' );

		$list.on( 'beforeExpand.mw-collapsible', function () {
			$toggler.removeClass( 'mw-icon-arrow-collapsed' ).addClass( 'mw-icon-arrow-expanded' );
			mw.cookie.set( cookieName, 'expanded' );
		} );

		$list.on( 'beforeCollapse.mw-collapsible', function () {
			$toggler.removeClass( 'mw-icon-arrow-expanded' ).addClass( 'mw-icon-arrow-collapsed' );
			mw.cookie.set( cookieName, 'collapsed' );
		} );
	}

	$( function () {
		var collapsibleLists, i;

		// Collapsible lists of categories and templates
		collapsibleLists = [
			{
				$list: $( '.templatesUsed ul' ),
				$toggler: $( '.mw-templatesUsedExplanation' ),
				cookieName: 'templates-used-list'
			},
			{
				$list: $( '.hiddencats ul' ),
				$toggler: $( '.mw-hiddenCategoriesExplanation' ),
				cookieName: 'hidden-categories-list'
			},
			{
				$list: $( '.preview-limit-report-wrapper' ),
				$toggler: $( '.mw-limitReportExplanation' ),
				cookieName: 'preview-limit-report'
			}
		];

		for ( i = 0; i < collapsibleLists.length; i++ ) {
			makeFooterCollapsible( collapsibleLists[i].$list, collapsibleLists[i].$toggler, collapsibleLists[i].cookieName );
		}
	} );
}( mediaWiki, jQuery ) );
