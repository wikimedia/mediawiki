( function ( mw, $ ) {
	var collapsibleLists, handleOne;

	// Collapsible lists of categories and templates
	collapsibleLists = [
		{
			listSel: '.templatesUsed ul',
			togglerSel: '.mw-templatesUsedExplanation',
			cookieName: 'templates-used-list'
		},
		{
			listSel: '.hiddencats ul',
			togglerSel: '.mw-hiddenCategoriesExplanation',
			cookieName: 'hidden-categories-list'
		},
		{
			listSel: '.preview-limit-report-wrapper',
			togglerSel: '.mw-limitReportExplanation',
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

	// The lists are inside #mw-content-text, so we can use this hook for now.
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var i;
		for ( i = 0; i < collapsibleLists.length; i++ ) {
			// Pass to a function for iteration-local variables
			handleOne(
				// We need to do this in this funny way since $content might contain e.g. $( '.hiddencats' )
				// itself, making .find( '.hiddencats ul' ) return an empty collection.
				$content.find( $( collapsibleLists[i].listSel ) ),
				$content.find( $( collapsibleLists[i].togglerSel ) ),
				collapsibleLists[i].cookieName
			);
		}
	} );

}( mediaWiki, jQuery ) );
