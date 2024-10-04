( function () {
	// Collapsible lists of categories and templates
	// If changing or removing a storeKey, ensure there is a strategy for old keys.
	// E.g. detect existence via requestIdleCallback and remove. (T121646)
	const collapsibleLists = [
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

	const handleOne = function ( $list, $toggler, storeKey ) {
		const collapsedVal = '0',
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

		if ( isCollapsed ) {
			$toggler.addClass( 'mw-editfooter-toggler-collapsed' );
		}

		$list.on( 'beforeExpand.mw-collapsible', () => {
			$toggler.removeClass( 'mw-editfooter-toggler-collapsed' );
			mw.storage.set( storeKey, expandedVal );
		} );

		$list.on( 'beforeCollapse.mw-collapsible', () => {
			$toggler.addClass( 'mw-editfooter-toggler-collapsed' );
			mw.storage.set( storeKey, collapsedVal );
		} );
	};

	mw.hook( 'wikipage.editform' ).add( ( $editForm ) => {
		for ( let i = 0; i < collapsibleLists.length; i++ ) {
			// Pass to a function for iteration-local variables
			handleOne(
				$editForm.find( collapsibleLists[ i ].listSel ),
				$editForm.find( collapsibleLists[ i ].togglerSel ),
				collapsibleLists[ i ].storeKey
			);
		}
	} );
}() );
