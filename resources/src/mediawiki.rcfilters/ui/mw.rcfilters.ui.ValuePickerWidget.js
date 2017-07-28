( function ( mw ) {
	/**
	 * Widget defining the behavior used to choose from a set of values
	 * in a single_value group
	 *
	 * @class
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.LabelElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FilterGroup} model Group model
	 * @param {Object} [config] Configuration object
	 * @cfg {Function} [itemFilter] A filter function for the items from the
	 *  model. If not given, all items will be included. The function must
	 *  handle item models and return a boolean whether the item is included
	 *  or not. Example: function ( itemModel ) { return itemModel.isSelected(); }
	 */
	mw.rcfilters.ui.ValuePickerWidget = function MwRcfiltersUiValuePickerWidget( model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.ValuePickerWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, config );

		this.model = model;
		this.itemFilter = config.itemFilter || function () { return true; };

		// Build the selection from the item models
		this.selectWidget = new OO.ui.ButtonSelectWidget();
		this.initializeSelectWidget();

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.selectWidget.connect( this, { choose: 'onSelectWidgetChoose' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-valuePickerWidget' )
			.append(
				this.$label
					.addClass( 'mw-rcfilters-ui-valuePickerWidget-title' ),
				this.selectWidget.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ValuePickerWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.ValuePickerWidget, OO.ui.mixin.LabelElement );

	/* Events */

	/**
	 * @event choose
	 * @param {string} name Item name
	 *
	 * An item has been chosen
	 */

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.ValuePickerWidget.prototype.onModelUpdate = function () {
		this.selectCurrentModelItem();
	};

	/**
	 * Respond to select widget choose event
	 *
	 * @param {OO.ui.ButtonOptionWidget} chosenItem Chosen item
	 * @fires choose
	 */
	mw.rcfilters.ui.ValuePickerWidget.prototype.onSelectWidgetChoose = function ( chosenItem ) {
		this.emit( 'choose', chosenItem.getData() );
	};

	/**
	 * Initialize the select widget
	 */
	mw.rcfilters.ui.ValuePickerWidget.prototype.initializeSelectWidget = function () {
		var items = this.model.getItems()
			.filter( this.itemFilter )
			.map( function ( filterItem ) {
				return new OO.ui.ButtonOptionWidget( {
					data: filterItem.getName(),
					label: filterItem.getLabel()
				} );
			} );

		this.selectWidget.clearItems();
		this.selectWidget.addItems( items );

		this.selectCurrentModelItem();
	};

	/**
	 * Select the current item that corresponds with the model item
	 * that is currently selected
	 */
	mw.rcfilters.ui.ValuePickerWidget.prototype.selectCurrentModelItem = function () {
		var selectedItem = this.model.getSelectedItems()[ 0 ];

		if ( selectedItem ) {
			this.selectWidget.selectItemByData( selectedItem.getName() );
		}
	};
}( mediaWiki ) );
