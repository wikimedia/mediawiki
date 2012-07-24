/* JavaScript for Special:RecentChanges */
( function ( mw, $ ) {

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
		updateCheckboxes: function () {
			// The option element for the 'all' namespace has an empty value
			var isAllNS = $select.find('option:selected').val() === '';

			// Iterates over checkboxes and propagate the selected option
			$.each( checkboxes, function ( i, id ) {
				$( '#' + id ).prop( 'disabled', isAllNS );
			});
		},

		init: function () {
			// Populate
			$select = $( '#namespace' );

			// Bind to change event, and trigger once to set the initial state of the checkboxes.
			$select.change( rc.updateCheckboxes ).change();
		}
	};

	// Run when document is ready
	$( rc.init );

}( mediaWiki, jQuery ) );
