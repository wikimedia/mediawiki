/*!
 * JavaScript for Special:RecentChanges
 */
( function () {
	var rc, $checkboxes, $select, namespaceDropdown;

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
			var value = $select.val(),
				isAllNS = value === 'all' || value === '';

			// Iterates over checkboxes and propagate the selected option
			$checkboxes.toggleClass( 'mw-input-hidden', isAllNS );
		},

		init: function () {
			$select = $( 'select#namespace' );
			$checkboxes = $( '#nsassociated, #nsinvert, .contribs-ns-filters' )
				.closest( '.mw-input-with-label' );

			if ( $select.length === 0 ) {
				$select = $( '#namespace select' );
				if ( $select.length > 0 ) {
					namespaceDropdown = OO.ui.infuse( $( '#namespace' ).closest( '[data-ooui]' ) );
					namespaceDropdown.on( 'change', rc.updateCheckboxes );
				}
			} else {
				// Bind to change event of the checkboxes.
				// The initial state is already set in HTML.
				$select.on( 'change', rc.updateCheckboxes );
			}
		}
	};

	$( rc.init );

	module.exports = rc;

}() );
