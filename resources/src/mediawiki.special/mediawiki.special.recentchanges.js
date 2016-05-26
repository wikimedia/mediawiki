/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	var rc, $checkboxes, $select;

	/**
	 * @class mw.special.recentchanges
	 * @singleton
	 */
	rc = {
		/**
		 * Handler to disable/enable the namespace selector checkboxes when the
		 * special 'all' namespace is selected/unselected respectively.
		 */
		updateCheckboxes: function () {
			// The option element for the 'all' namespace has an empty value
			var isAllNS = $select.val() === '';

			// Iterates over checkboxes and propagate the selected option
			$checkboxes.prop( 'disabled', isAllNS );
		},

		/** */
		init: function () {
			$select = $( '#namespace' );
			$checkboxes = $( '#nsassociated, #nsinvert' );

			// Bind to change event, and trigger once to set the initial state of the checkboxes.
			rc.updateCheckboxes();
			$select.change( rc.updateCheckboxes );
		}
	};

	$( rc.init );

	module.exports = rc;

}( mediaWiki, jQuery ) );
