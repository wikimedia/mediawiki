jQuery( document ).ready( function ( $ ) {
	var collapsibleLists, i, handleOne;

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

	handleOne = function ( $list, $toggler, cookieName ) {
		var isCollapsed = $.cookie( cookieName ) !== 'expanded';

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
			$.cookie( cookieName, 'expanded' );
		} );

		$list.on( 'beforeCollapse.mw-collapsible', function () {
			$toggler.removeClass( 'mw-icon-arrow-expanded' ).addClass( 'mw-icon-arrow-collapsed' );
			$.cookie( cookieName, 'collapsed' );
		} );
	};

	for ( i = 0; i < collapsibleLists.length; i++ ) {
		// Pass to a function for iteration-local variables
		handleOne( collapsibleLists[i].$list, collapsibleLists[i].$toggler, collapsibleLists[i].cookieName );
	}
} );
