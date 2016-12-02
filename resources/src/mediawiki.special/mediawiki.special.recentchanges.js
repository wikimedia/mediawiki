/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	var rc;
	var $namespace, $namespaceFilterOpts;
	var $enableDisplayOpts, $changesCountFieldLayout, $displayOptions;

	/**
	 * @class mw.special.recentchanges
	 * @singleton
	 */
	rc = {
		/**
		 * Handler to disable/enable the namespace selector checkboxes when the
		 * special 'all' namespace is selected/unselected respectively.
		 */
		updateNamespaceFilterForm: function () {
			// The option element for the 'all' namespace has an empty value
			var isAllNS = $namespace.getValue() === '';

			// Iterates over checkboxes and propagate the selected option
			$namespaceFilterOpts.forEach( function ( checkbox ) {
				checkbox.setDisabled( isAllNS );
			} );
		},

		/**
		 * Enable or disable the fields in the display options form, according to
		 * $enableDisplayOpts field.
         */
		updateDisplayOptionsForm: function () {
			var enable = $enableDisplayOpts.isSelected();

			$displayOptions.forEach( function ( element ) {
				element.setDisabled( !enable );
			} );
		},

		/**
		 * Check if the given changes count is within the accepted range.
         */
		validateChangesCount: function () {
			var $field = $displayOptions[0];
			var value = parseInt( $field.getValue() );

			if ( value < 1 ) {
				$changesCountFieldLayout.setNotices( [
					mw.message( 'recentchanges-changescount-notice-invalidnumber', 1 ).text(),
				] );
			} else {
				$changesCountFieldLayout.setNotices( [] );
			}
		},

		/** */
		init: function () {
			$namespace = OO.ui.infuse( 'namespace' );
			$namespaceFilterOpts = [
				OO.ui.infuse( 'nsassociated' ),
				OO.ui.infuse( 'nsinvert' ),
			];

			$enableDisplayOpts = OO.ui.infuse( 'enabledisplayopts' );
			$changesCountFieldLayout = OO.ui.infuse( 'changescount-fieldlayout' );
			$displayOptions = [
				OO.ui.infuse( 'changescount' ),
				OO.ui.infuse( 'changesage' ),
			];

			rc.updateNamespaceFilterForm();
			rc.updateDisplayOptionsForm();

			$namespace.on( 'change', rc.updateNamespaceFilterForm );
			$enableDisplayOpts.on( 'change', rc.updateDisplayOptionsForm );
			$displayOptions[0].on( 'change', rc.validateChangesCount );
		}
	};

	$( rc.init );

	module.exports = rc;

}( mediaWiki, jQuery ) );
