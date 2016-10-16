( function ( mw ) {
	var collapsibleLists, handleOne;

	// Collapsible lists of edit notices
	collapsibleLists = [
		{
			listSel: '#mw-titleprotectedwarning-log',
			togglerSel: '#mw-titleprotectedwarning-notice',
			collapsed: false
		},
		{
			listSel: '#mw-protectedpagewarning-log',
			togglerSel: '#mw-protectedpagewarning-notice',
			collapsed: false
		},
		{
			listSel: '#mw-semiprotectedpagewarning-log',
			togglerSel: '#mw-semiprotectedpagewarning-notice',
			collapsed: true
		},
		{
			listSel: '#mw-cascadeprotectedwarning-list',
			togglerSel: '#mw-cascadeprotectedwarning-warning',
			collapsed: false
		}
	];

	handleOne = function ( $list, $toggler, $collapsed ) {
		// Style the toggler with an arrow icon and add a tabIndex and a role for accessibility
		$toggler.addClass( 'mw-editheader-toggler' ).prop( 'tabIndex', 0 ).attr( 'role', 'button' );
		$list.addClass( 'mw-editheader-list' );

		$list.makeCollapsible( {
			$customTogglers: $toggler,
			linksPassthru: true,
			plainMode: true,
			collapsed: $collapsed
		} );

		$toggler.addClass( $collapsed ? 'mw-icon-arrow-collapsed' : 'mw-icon-arrow-expanded' );

		$list.on( 'beforeExpand.mw-collapsible', function () {
			$toggler.removeClass( 'mw-icon-arrow-collapsed' ).addClass( 'mw-icon-arrow-expanded' );
		} );

		$list.on( 'beforeCollapse.mw-collapsible', function () {
			$toggler.removeClass( 'mw-icon-arrow-expanded' ).addClass( 'mw-icon-arrow-collapsed' );
		} );
	};

	mw.hook( 'wikipage.editheader' ).add( function ( $editHeader ) {
		var i;
		// Allow extensions to append items
		mw.hook( 'collapsibleheader.collapsiblelists' ).fire( collapsibleLists );
		for ( i = 0; i < collapsibleLists.length; i++ ) {
			// Pass to a function for iteration-local variables
			handleOne(
				$editHeader.find( collapsibleLists[ i ].listSel ),
				$editHeader.find( collapsibleLists[ i ].togglerSel ),
				collapsibleLists[ i ].collapsed
			);
		}
	} );
}( mediaWiki ) );
