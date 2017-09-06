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
		this.model = targetPageModel;

		this.titleSearch = new mw.widgets.TitleSearchWidget( {
			placeholder: mw.msg( 'rcfilters-target-page-placeholder' ),
			showImages: true,
			showDescription: true
		} );
		this.titleSearch.$element.prepend( this.titleSearch.$query );

		this.selectedTitleWidget = new OO.ui.LabelWidget();

		// Events
		this.titleSearch.results.connect( this, { choose: 'onChooseTitle' } );
		this.selectedTitleWidget.$element.on( 'click', this.onClearSelectedTitle.bind( this ) );
		this.model.connect( this, { update: 'onModelUpdate' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-rclTargetPageWidget' )
			.append( this.titleSearch.$element );

		this.onModelUpdate();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.RclTargetPageWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Respond to the user choosing a title
	 *
	 * @param {mw.widgets.TitleOptionWidget} chosenTitle
	 */
	mw.rcfilters.ui.RclTargetPageWidget.prototype.onChooseTitle = function ( chosenTitle ) {
		this.controller.setTargetPage( chosenTitle.getData() );
	};

	/**
	 * Respond to the user clearing selected title
	 */
	mw.rcfilters.ui.RclTargetPageWidget.prototype.onClearSelectedTitle = function () {
		this.controller.setTargetPage( null );
	};

	/**
	 * Respond to the model being updated
	 */
	mw.rcfilters.ui.RclTargetPageWidget.prototype.onModelUpdate = function () {
		var target = this.model.getValue();
		if ( target ) {
			this.titleSearch.$element.detach();
			this.$element.append( this.selectedTitleWidget.$element );
			this.selectedTitleWidget.setLabel( target );
		} else {
			this.selectedTitleWidget.$element.detach();
			this.$element.append( this.titleSearch.$element );
			this.titleSearch.query.setValue( '' );
			this.titleSearch.query.focus();
		}
	};
}( mediaWiki ) );
