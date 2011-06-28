/* JavaScript for Special:RecentChanges */
( function( $ ) {

	var checkboxes = [ 'nsassociated', 'nsinvert' ];

	/**
	 * @var select {jQuery}
	 */
	var $select = null;

	var rc = mw.special.recentchanges = {
	
		/**
		 * Handler to disable/enable the namespace selector checkboxes when the
		 * special 'all' namespace is selected/unselected respectively.
		 */
	 	updateCheckboxes: function() {
			// The 'all' namespace is the FIRST in the list.
			var isAllNS = $select.find( 'option' ).first().is( ':selected' );

			// Iterates over checkboxes and propagate the selected option
			$.each( checkboxes, function( i, id ) {
				$( '#' + id ).attr( 'disabled', isAllNS );
			});
		},

		init: function() {
			// Populate & bind
	 		$select = $( '#namespace' ).change( rc.updateCheckboxes );

			// Trigger once set the initial statuses of the checkboxes.
			$select.change();
		}
	};

	// Run when document is ready
	$( rc.init );

})( jQuery );
