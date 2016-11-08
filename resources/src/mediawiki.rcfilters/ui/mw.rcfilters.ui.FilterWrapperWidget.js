( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} config Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 */
	mw.rcfilters.ui.FilterWrapperWidget = function MwRcfiltersUiFilterWrapperWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.controller = controller;
		this.model = model;
		this.filtersInCapsule = [];

		this.filterPopup = new mw.rcfilters.ui.FiltersListWidget(
			this.controller,
			this.model,
			{
				label: mw.msg( 'rcfilters-filterlist-title' )
			}
		);

		this.textInput = new OO.ui.TextInputWidget( {
			classes: [ 'mw-rcfilters-ui-filterWrapperWidget-search' ],
			icon: 'search',
			placeholder: mw.msg( 'rcfilters-search-placeholder' )
		} );

		this.capsule = new mw.rcfilters.ui.FilterCapsuleMultiselectWidget( this.textInput, {
			popup: {
				$content: this.filterPopup.$element,
				classes: [ 'mw-rcfilters-ui-filterWrapperWidget-popup' ]
			}
		} );

		// Events
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			itemUpdate: 'onModelItemUpdate'
		} );
		this.textInput.connect( this, {
			change: 'onTextInputChange'
		} );
		this.capsule.connect( this, {
			remove: 'onCapsuleRemoveItem'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget' )
			.append( this.capsule.$element, this.textInput.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/**
	 * Respond to text input change
	 *
	 * @param {string} newValue Current value
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onTextInputChange = function ( newValue ) {
		// Filter the results
		this.filterPopup.filter( this.model.findMatches( newValue ) );
	};

	/**
	 * Respond to an event where an item is removed from the capsule.
	 * This is the case where a user actively removes a filter box from the capsule widget.
	 *
	 * @param {string[]} filterNames An array of filter names that were removed
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onCapsuleRemoveItem = function ( filterNames ) {
		var filterItem,
			widget = this;

		filterNames.forEach( function ( filterName ) {
			// Go over filters
			filterItem = widget.model.getItemByName( filterName );
			filterItem.toggleSelected( false );
		} );
	};

	/**
	 * Respond to model update event and set up the available filters to choose
	 * from.
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelInitialize = function () {
		var items,
			filters = this.model.getItems();

		// Reset
		this.capsule.getMenu().clearItems();

		// Insert hidden options for the capsule to get its item data from
		items = filters.map( function ( filterItem ) {
			return new OO.ui.MenuOptionWidget( {
				data: filterItem.getName(),
				label: filterItem.getLabel()
			} );
		} );

		this.capsule.getMenu().addItems( items );
	};

	/**
	 * Respond to model item update
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item that was updated
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelItemUpdate = function ( item ) {
		if ( item.isSelected() ) {
			this.capsule.addItemsFromData( [ item.getName() ] );
		} else {
			this.capsule.removeItemsFromData( [ item.getName() ] );
		}
	};
}( mediaWiki ) );
