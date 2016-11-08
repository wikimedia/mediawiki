( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
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
				$autoCloseIgnore: this.textInput.$element,
				classes: [ 'mw-rcfilters-ui-filterWrapperWidget-popup' ]
			},
		} );

		// Events
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			loading: 'onModelLoading',
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
	 * This is the case where a user actively removes a filter box from the capusle widget.
	 *
	 * @param {string[]} filterName An array of filter names that were removed
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onCapsuleRemoveItem = function ( filterNames ) {
		var filterItem,
			widget = this;

		filterNames.forEach( function ( filterName ) {
			// Go over filters
			if ( filterName === 'invalid' ) {
				// If we're removing "invalid" we need to tell the model
				// to force validation on itself
				widget.model.fixInvalidParameters();
				// Toggle the valid state of all groups
				widget.filterPopup.getItems().forEach( function ( groupWidget ) {
					groupWidget.toggleValidState( widget.model.isParameterGroupValid( groupWidget.getName() ) );
				} );
			} else {
				filterItem = widget.model.getItemByName( filterName );
				filterItem.toggleSelected( false );
			}
		} );
	};

	/**
	 * Respond to model update event and set up the available filters to choose
	 * from.
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelInitialize = function () {
		var i,
			items = [],
			filters = this.model.getItems();

		// Reset
		this.capsule.getMenu().clearItems();

		// Add 'invalid' filter
		items.push(
			new OO.ui.MenuOptionWidget( {
				data: 'invalid',
				label: mw.msg( 'rcfilters-invalid-filter' ),
			} )
		);
		// Insert hidden options for the capsule to get its item data from
		for ( i = 0; i < filters.length; i++ ) {
			items.push(
				new OO.ui.MenuOptionWidget( {
					data: filters[ i ].getName(),
					label: filters[ i ].getLabel()
				} )
			);
		}

		this.capsule.getMenu().addItems( items );
	};

	/**
	 * Respond to a change in model loading state.
	 *
	 * @param {boolean} isLoading Model is loading
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelLoading = function ( isLoading ) {
		var widget = this,
			anyInvalid = false;

		if ( !isLoading ) {
			// See if any groups are invalid
			this.filterPopup.getItems().forEach( function ( groupWidget ) {
				var groupValid = widget.model.isParameterGroupValid( groupWidget.getName() );
				groupWidget.toggleValidState( groupValid );

				anyInvalid = anyInvalid || !groupValid;
			} );

			if ( anyInvalid ) {
				// Show the invalid filter
				this.capsule.addItemsFromData( [ 'invalid' ] );
			}
		}
	};

	/**
	 * Respond to model item update
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item that was updated
	 * @param {boolean} isSelected State of the filter
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelItemUpdate = function ( item, isSelected ) {
		if ( isSelected ) {
			this.capsule.addItemsFromData( [ item.getName() ] );
		} else {
			this.capsule.removeItemsFromData( [ item.getName() ] );
		}

		if ( !this.model.isLoading() ) {
			if ( this.model.areAllParameterGroupsValid() ) {
				this.capsule.removeItemsFromData( [ 'invalid' ] );
			} else {
				this.capsule.addItemsFromData( [ 'invalid' ] );
			}
		}
	};
} )( mediaWiki );
