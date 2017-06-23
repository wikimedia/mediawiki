( function ( mw ) {
	/**
	 * Widget defining the popup to choose date for the results
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FilterGroup} model Group model for 'days'
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.DatePopupWidget = function MwRcfiltersUiDatePopupWidget( controller, model, config ) {
		var limitLabel = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-days-title' ),
			classes: [ 'mw-rcfilters-ui-datePopupWidget-limit-title' ]
		} );

		config = config || {};

		// Parent
		mw.rcfilters.ui.DatePopupWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;

		this.dateSelectWidget = new OO.ui.ButtonSelectWidget( {
			items: this.model.getItems().map( function ( filterItem ) {
				return new OO.ui.ButtonOptionWidget( {
					data: filterItem.getName(),
					label: filterItem.getLabel()
				} );
			} )
		} );
		this.dateSelectWidget.selectItemByData( this.model.getSelectedItems()[ 0 ] && this.model.getSelectedItems()[ 0 ].getName() );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.dateSelectWidget.connect( this, { choose: 'onDateSelectChoose' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-datePopupWidget' )
			.append(
				limitLabel.$element,
				this.dateSelectWidget.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.DatePopupWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.DatePopupWidget.prototype.onModelUpdate = function () {
		var selectedItem = this.model.getSelectedItems()[ 0 ];

		if ( selectedItem ) {
			this.dateSelectWidget.selectItemByData( selectedItem.getName() );
		}
	};

	/**
	 * Respond to limit select choose event
	 *
	 * @param {OO.ui.ButtonOptionWidget} chosenItem Chosen item
	 * @fires choose
	 */
	mw.rcfilters.ui.DatePopupWidget.prototype.onDateSelectChoose = function ( chosenItem ) {
		this.controller.toggleFilterSelect( chosenItem.getData(), true );

		this.emit( 'choose', chosenItem.getData() );
	};

}( mediaWiki ) );
