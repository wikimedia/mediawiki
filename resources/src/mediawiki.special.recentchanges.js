/*!
 * JavaScript for Special:RecentChanges
 */
( function () {
	var rc, $checkboxes, $select;

	/**
	 * @class mw.special.recentchanges
	 * @singleton
	 */
	rc = {
		/**
		 * Handler to hide/show the namespace selector checkboxes when the
		 * special 'all' namespace is selected/unselected respectively.
		 */
		updateCheckboxes: function () {
			// The option element for the 'all' namespace has an empty value
			var isAllNS = $select.val() === '';

			// Iterates over checkboxes and propagate the selected option
			$checkboxes.toggleClass( 'mw-input-hidden', isAllNS );
		},

		init: function () {
			$select = $( '#namespace' );
			$checkboxes = $( '#nsassociated, #nsinvert' ).closest( '.mw-input-with-label' );

			// Bind to change event of the checkboxes.
			// The initial state is already set in HTML.
			$select.on( 'change', rc.updateCheckboxes );
		}
	};

	$( rc.init );

	module.exports = rc;

}() );
