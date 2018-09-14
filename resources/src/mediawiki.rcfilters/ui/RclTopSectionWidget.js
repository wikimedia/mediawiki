( function () {
	var RclToOrFromWidget = require( './RclToOrFromWidget.js' ),
		RclTargetPageWidget = require( './RclTargetPageWidget.js' ),
		RclTopSectionWidget;

	/**
	 * Top section (between page title and filters) on Special:RecentChangesLinked (AKA RelatedChanges)
	 *
	 * @class mw.rcfilters.ui.RclTopSectionWidget
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FilterItem} showLinkedToModel Model for 'showlinkedto' parameter
	 * @param {mw.rcfilters.dm.FilterItem} targetPageModel Model for 'target' parameter
	 * @param {Object} [config] Configuration object
	 */
	RclTopSectionWidget = function MwRcfiltersUiRclTopSectionWidget(
		savedLinksListWidget, controller, showLinkedToModel, targetPageModel, config
	) {
		var toOrFromWidget,
			targetPage;
		config = config || {};

		// Parent
		RclTopSectionWidget.parent.call( this, config );

		this.controller = controller;

		toOrFromWidget = new RclToOrFromWidget( controller, showLinkedToModel );
		targetPage = new RclTargetPageWidget( controller, targetPageModel );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-rclTopSectionWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.append( toOrFromWidget.$element )
							),
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.append( targetPage.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-table-placeholder' )
									.addClass( 'mw-rcfilters-ui-cell' ),
								!mw.user.isAnon() ?
									$( '<div>' )
										.addClass( 'mw-rcfilters-ui-cell' )
										.addClass( 'mw-rcfilters-ui-rclTopSectionWidget-savedLinks' )
										.append( savedLinksListWidget.$element ) :
									null
							)
					)
			);
	};

	/* Initialization */

	OO.inheritClass( RclTopSectionWidget, OO.ui.Widget );

	module.exports = RclTopSectionWidget;
}() );
