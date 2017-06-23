( function ( mw ) {
	/**
	 * Widget defining the popup to choose number of results
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FilterGroup} model Group model for 'limit'
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.ChangesLimitPopupWidget = function MwRcfiltersUiChangesLimitPopupWidget( controller, model, config ) {
		var limitLabel = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-limit-title' )
		} );

		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesLimitPopupWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;

		this.limitSelectWidget = new OO.ui.ButtonSelectWidget( {
			items: this.model.getItems().map( function ( filterItem ) {
				return new OO.ui.ButtonOptionWidget( {
					data: filterItem.getName(),
					label: filterItem.getLabel()
				} );
			} )
		} );
		this.limitSelectWidget.selectItemByData( this.model.getSelectedItems()[ 0 ] && this.model.getSelectedItems()[ 0 ].getName() );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.limitSelectWidget.connect( this, { choose: 'onLimitSelectChoose' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-changesLimitPopupWidget' )
			.append(
				limitLabel.$element,
				this.limitSelectWidget.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesLimitPopupWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.ChangesLimitPopupWidget.prototype.onModelUpdate = function () {
		var selectedItem = this.model.getSelectedItems()[ 0 ];

		if ( selectedItem ) {
			this.limitSelectWidget.selectItemByData( selectedItem.getName() );
		}
	};

	/**
	 * Respond to limit select choose event
	 *
	 * @param {OO.ui.ButtonOptionWidget} chosenItem Chosen item
	 * @fires choose
	 */
	mw.rcfilters.ui.ChangesLimitPopupWidget.prototype.onLimitSelectChoose = function ( chosenItem ) {
		this.controller.toggleFilterSelect( chosenItem.getData(), true );

		this.emit( 'choose', chosenItem.getData() );
	};

}( mediaWiki ) );
