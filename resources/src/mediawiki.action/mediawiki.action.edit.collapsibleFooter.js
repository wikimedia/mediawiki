( function () {
	var collapsibleLists, handleOne;

	// Collapsible lists of categories and templates
	// If changing or removing a storeKey, ensure there is a strategy for old keys.
	// E.g. detect existence via requestIdleCallback and remove. (T121646)
	collapsibleLists = [
		{
			listSel: '.templatesUsed ul',
			togglerSel: '.mw-templatesUsedExplanation',
			storeKey: 'mwedit-state-templatesUsed'
		},
		{
			listSel: '.hiddencats ul',
			togglerSel: '.mw-hiddenCategoriesExplanation',
			storeKey: 'mwedit-state-hiddenCategories'
		},
		{
			listSel: '.preview-limit-report-wrapper',
			togglerSel: '.mw-limitReportExplanation',
			storeKey: 'mwedit-state-limitReport'
		}
	];

	handleOne = function ( $list, $toggler, storeKey ) {
		var collapsedVal = '0',
			expandedVal = '1',
			// Default to collapsed if not set
			isCollapsed = mw.storage.get( storeKey ) !== expandedVal;

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
			mw.storage.set( storeKey, expandedVal );
		} );

		$list.on( 'beforeCollapse.mw-collapsible', function () {
			$toggler.removeClass( 'mw-icon-arrow-expanded' ).addClass( 'mw-icon-arrow-collapsed' );
			mw.storage.set( storeKey, collapsedVal );
		} );
	};

	mw.hook( 'wikipage.editform' ).add( function ( $editForm ) {
		var i;
		for ( i = 0; i < collapsibleLists.length; i++ ) {
			// Pass to a function for iteration-local variables
			handleOne(
				$editForm.find( collapsibleLists[ i ].listSel ),
				$editForm.find( collapsibleLists[ i ].togglerSel ),
				collapsibleLists[ i ].storeKey
			);
		}
	} );
}() );
