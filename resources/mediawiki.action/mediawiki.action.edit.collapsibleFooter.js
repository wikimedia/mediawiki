( function ( mw, $ ) {
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
		}
	];

	for ( i = 0; i < collapsibleLists.length; i++ ) {
		/*jshint loopfunc:true */
		// Use a closure for iteration-local variables
		( function ( $list, $toggler, cookieName ) {
			var isCollapsed = $.cookie( cookieName ) !== 'expanded';

			// Style the toggler with an arrow icon and add a tabindex for accessibility
			$toggler.addClass( 'mw-editfooter-toggler' ).attr( 'tabindex', 200 + i );
			$list.addClass( 'mw-editfooter-list' );

			$list.makeCollapsible( {
				$customTogglers: $toggler,
				linksPassthru: false,
				plainMode: true,
				collapsed: isCollapsed
			} );

			$toggler.addClass( isCollapsed ? 'mw-icon-arrow-collapsed' : 'mw-icon-arrow-expanded' );

			$list.on( 'beforeExpand.mw-collapsible', function () {
				$toggler.removeClass( 'mw-icon-arrow-collapsed' ).addClass( 'mw-icon-arrow-expanded' );
				$.cookie( cookieName, 'expanded' );
			} );

			$list.on( 'beforeCollapse.mw-collapsible', function () {
				$toggler.removeClass( 'mw-icon-arrow-expanded' ).addClass( 'mw-icon-arrow-collapsed' );
				$.cookie( cookieName, 'collapsed' );
			} );
		} )( collapsibleLists[i].$list, collapsibleLists[i].$toggler, collapsibleLists[i].cookieName );
	}
}( mediaWiki, jQuery ) );
