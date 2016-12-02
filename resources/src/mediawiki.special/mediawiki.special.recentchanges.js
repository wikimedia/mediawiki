/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	var rc,
		$namespace, $namespaceFilterOpts,
		$changesCountFieldLayout,
		$displayOptions;

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
		 * Check if the given changes count is within the accepted range.
         */
		validateChangesCount: function () {
			var $field = $displayOptions[ 0 ],
				value = parseInt( $field.getValue() );

			if ( value < 0 || value > 500 ) {
				$changesCountFieldLayout.setNotices( [
					mw.message( 'recentchanges-limit-notice-invalidnumber', 0, 500 ).text()
				] );
			} else {
				$changesCountFieldLayout.setNotices( [] );
			}
		},

		init: function () {
			var panel;

			try {
				$namespace = OO.ui.infuse( 'namespace' );
				$namespaceFilterOpts = [
					OO.ui.infuse( 'nsassociated' ),
					OO.ui.infuse( 'nsinvert' )
				];

				$changesCountFieldLayout = OO.ui.infuse( 'limit-fieldlayout' );
				$displayOptions = [
					OO.ui.infuse( 'limit' ),
					OO.ui.infuse( 'maxage' ),
					OO.ui.infuse( 'from' )
				];

				rc.updateNamespaceFilterForm();
				rc.validateChangesCount();

				$namespace.on( 'change', rc.updateNamespaceFilterForm );
				$displayOptions[ 0 ].on( 'change', rc.validateChangesCount );

				panel = $( '#filterform-panel' );
				$( panel ).makeCollapsible( {
					collapsed: mw.user.options.get( 'rcpanelcollapsed' ) === true,
					expandText: mw.message( 'recentchanges-panel-expand' ).text(),
				} );
			} catch ( err ) {
				mw.log.warn( err.message + '\nJavaScript enhancements disabled.' );
			}
		}
	};

	$( rc.init );

	module.exports = rc;

}( mediaWiki, jQuery ) );
