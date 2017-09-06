( function ( mw ) {
	/**
	 * Widget to select and display target page on Special:RecentChangesLinked (AKA Related Changes)
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.controller} controller
	 * @param {mw.rcfilters.dm.FilterItem} targetPageModel
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.RclTargetPageWidget = function MwRcfiltersUiRclTargetPageWidget(
		controller, targetPageModel, config
	) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.RclTargetPageWidget.parent.call( this, config );

		this.controller = controller;

		this.titleSearch = new mw.widgets.TitleSearchWidget( {
			placeholder: mw.msg( 'rcfilters-target-page-placeholder' ),
			showImages: true,
			showDescription: true
		} );
		this.titleSearch.$element.prepend( this.titleSearch.$query );

		this.selectedPageLabel = new OO.ui.LabelWidget();

		// TEMPORARY: this is only so I can test the new group filter type (any_value)
		this.selectedPageLabel.$element.on( 'click', function () {
			this.$element.empty().append( this.titleSearch.$element );
			this.titleSearch.query.setValue( '' );
			this.titleSearch.query.focus();
			this.controller.setTargetPage( null );
		}.bind( this ) );

		// Events
		this.titleSearch.results.connect( this, { choose: 'onChooseTitle' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-rclTargetPageWidget' )
			.append( this.titleSearch.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.RclTargetPageWidget, OO.ui.Widget );

	/* Methods */

	mw.rcfilters.ui.RclTargetPageWidget.prototype.onChooseTitle = function ( chosenTitle ) {
		var target = chosenTitle.getData();
		this.titleSearch.results.toggle();
		this.selectedPageLabel.setLabel( target );
		this.$element.empty().append( this.selectedPageLabel.$element );

		this.controller.setTargetPage( target );
	};
}( mediaWiki ) );
