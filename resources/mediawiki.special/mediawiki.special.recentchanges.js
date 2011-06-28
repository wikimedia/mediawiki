/* JavaScript for Special:RecentChanges */
( function( $, mw ) {

mw.special.recentchanges = {
	// -- Variables
	'select' : false,
	'checkboxes' : [ 'nsassociated', 'nsinvert' ],

	// -- Methods
	'init' : function() {
		this.select = $( 'select#namespace' );

		// Register an onChange trigger for the <select> element
		this.select.change( function() {
			mw.special.recentchanges.updateCheckboxes();
		});
		// on load, trigger the event to eventually update checkboxes statuses
		this.select.change();
	},
	
	/**
	 * handler to disable/enable the namespace selector checkboxes when the
	 * special 'all' namespace is selected/unselected respectively.
	 */
 	'updateCheckboxes' : function() {
		// The 'all' namespace is the FIRST in the list.
		var isAllNS = this.select.find( 'option' ).first().is( ':selected' );

		// Iterates over checkboxes and propagate the selected option
		$.map( this.checkboxes, function(id) {
			$( 'input#'+id ).attr( 'disabled', isAllNS );
		});
	},
};

mw.special.recentchanges.init();

}(jQuery, mediaWiki ) );
