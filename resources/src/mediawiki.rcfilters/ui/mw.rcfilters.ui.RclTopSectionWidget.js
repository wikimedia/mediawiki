( function () {
	/**
	 * Top section (between page title and filters) on Special:RecentChangesLinked (AKA RelatedChanges)
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FilterItem} showLinkedToModel Model for 'showlinkedto' parameter
	 * @param {mw.rcfilters.dm.FilterItem} targetPageModel Model for 'target' parameter
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.RclTopSectionWidget = function MwRcfiltersUiRclTopSectionWidget(
		savedLinksListWidget, controller, showLinkedToModel, targetPageModel, config
	) {
		var toOrFromWidget,
			targetPage;
		config = config || {};

		// Parent
		mw.rcfilters.ui.RclTopSectionWidget.parent.call( this, config );

		this.controller = controller;

		toOrFromWidget = new mw.rcfilters.ui.RclToOrFromWidget( controller, showLinkedToModel );
		targetPage = new mw.rcfilters.ui.RclTargetPageWidget( controller, targetPageModel );

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

	OO.inheritClass( mw.rcfilters.ui.RclTopSectionWidget, OO.ui.Widget );
}() );
