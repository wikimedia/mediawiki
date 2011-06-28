/* JavaScript for Special:RecentChanges */
( function( $ ) {

	var checkboxes = [ 'nsassociated', 'nsinvert' ];

	mw.special.recentchanges = {

		/**
		 * @var select {jQuery}
		 */
		$select: null,

		init: function() {
			var rc = this;

			rc.$select = 
				$( 'select#namespace' )
					.change( rc.updateCheckboxes )
					// Trigger once set the initial statuses of the checkboxes.
					.change();
		},
	
		/**
		 * Handler to disable/enable the namespace selector checkboxes when the
		 * special 'all' namespace is selected/unselected respectively.
		 */
	 	updateCheckboxes: function() {
			// The 'all' namespace is the FIRST in the list.
			var isAllNS = mw.special.recentchanges.$select.find( 'option' ).first().is( ':selected' );

			// Iterates over checkboxes and propagate the selected option
			$.map( checkboxes, function( id ) {
				$( '#'+id ).attr( 'disabled', isAllNS );
			});
		},
	};

	mw.special.recentchanges.init();

})( jQuery );
