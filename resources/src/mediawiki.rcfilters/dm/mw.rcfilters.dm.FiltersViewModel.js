( function ( mw, $ ) {
	/**
	 * View model for the filters selection and display
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.dm.FiltersViewModel = function MwRcfiltersDmFiltersViewModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.groups = {};
		this.parameters = {};
		this.loading = true;

		// Events
		this.aggregate( { update: 'filterItemUpdate' } );
		this.connect( this, { filterItemUpdate: 'onFilterItemUpdate' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.FiltersViewModel );
	OO.mixinClass( mw.rcfilters.dm.FiltersViewModel, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.FiltersViewModel, OO.EmitterList );

	/* Events */

	/**
	 * @event initialize
	 *
	 * Filter list is initialized
	 */

	/**
	 * @event itemUpdate
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item updated
	 * @param {boolean} isSelected Filter is selected
	 *
	 * Filter item has changed
	 */

	/* Methods */

	/**
	 * Respond to filter item change. Update parameter values and emit event.
	 *
	 * @fires filterUpdate
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.onFilterItemUpdate = function ( item, isSelected ) {
		// Update parameter state
		this.updateParameters( this.getFiltersToParameters() );
		this.emit( 'itemUpdate', item, isSelected );
	};

	/**
	 * Set filters and preserve a group relationship based on
	 * the definition given by an object
	 *
	 * @param {Object} filters Filter group definition
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.initializeFilters = function ( filters ) {
		var i, filterItem, group,
			model = this,
			items = [];

		// Reset
		this.clearItems();
		this.groups = {};

		$.each( filters, function ( group, data ) {
			model.groups[ group ] = model.groups[ group ] || {};
			model.groups[ group ].filters = model.groups[ group ].filters || [];

			model.groups[ group ].title = data.title;
			model.groups[ group ].type = data.type;

			for ( i = 0; i < data.filters.length; i++ ) {
				filterItem = new mw.rcfilters.dm.FilterItem( data.filters[ i ].name, {
					group: group,
					label: data.filters[ i ].label,
					description: data.filters[ i ].description,
					selected: data.filters[ i ].selected
				} );

				model.groups[ group ].filters.push( filterItem );
				items.push( filterItem );
			}
		} );

		this.addItems( items );
		this.emit( 'initialize' );
	};

	mw.rcfilters.dm.FiltersViewModel.prototype.toggleLoading = function ( isLoading ) {
		isLoading = isLoading === undefined ? !this.loading : isLoading;

		if ( this.loading !== isLoading ) {
			this.loading = isLoading;

			this.emit( 'loading', this.loading );
		}
	};

	mw.rcfilters.dm.FiltersViewModel.prototype.isLoading = function () {
		return this.loading;
	};
	/**
	 * Get the names of all available filters
	 *
	 * @return {string[]} An array of filter names
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFilterNames = function () {
		return this.getItems().map( function ( item ) { return item.getName(); } );
	};

	/**
	 * Check whether the values of the parameters in the filter group
	 * to see whether it is valid.
	 *
	 * @param {string} groupName Group name
	 * @return {boolean} Group parameter values is valid
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.isParameterGroupValid = function ( groupName ) {
		var model = this,
			groupParamValues = [];

		if ( this.groups[ groupName ] ) {
			this.groups[ groupName ].filters.forEach( function ( filter ) {
				// Get the parameter value
				groupParamValues.push( Number( model.parameters[ filter.getName() ] || 0 ) );
			} );

			// Go over the values and validate
			if ( this.groups[ groupName ].type === 'send_unselected_if_any' ) {
				// With this type of group, if all params in the group are true
				// the group is invalid
				return groupParamValues.reduce( function ( a, b ) { return Number( a ) + Number( b ); } ) !== groupParamValues.length;
			}
		}

		return false;
	};

	mw.rcfilters.dm.FiltersViewModel.prototype.areAllParameterGroupsValid = function () {
		var model = this,
			anyInvalid = false;

		$.each( this.groups, function ( groupName, data ) {
			anyInvalid = anyInvalid || !model.isParameterGroupValid( groupName );
		} );

		return !anyInvalid;
	};

	/**
	 * Fix invalid parameter states and, if needed, update the corresponding
	 * filters.
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.fixInvalidParameters = function () {
		var model = this,
			paramGroup = {};

		$.each( this.groups, function ( groupName, data ) {
			if ( !model.isParameterGroupValid( groupName ) ) {
				// Fix the parameters in this group
				if ( model.groups[ groupName ].type === 'send_unselected_if_any' ) {
					// Make all parameters falsy to "fix" the state and
					// make it fit the filter UI
					model.groups[ groupName ].filters.forEach( function ( filterItem ) {
						// If the corresponding parameter exists
						if ( model.parameters[ filterItem.getName() ] ) {
							// Fix its value
							paramGroup[ filterItem.getName() ] = false;
						}
					} );
					model.updateParameters( paramGroup );
				}
			}
		} );
	};

	/**
	 * Get the object that defines groups and their filter items
	 *
	 * @return {Object} Filter groups
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFilterGroups = function () {
		return this.groups;
	};

	/**
	 * Update the representation of the parameters. These are the back-end
	 * parameters representing the filters, but they represent the given
	 * current state regardless of validity.
	 *
	 * This should only run after filters are already set.
	 *
	 * @param {Object} params Parameter state
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.updateParameters = function ( params ) {
		var model = this;

		$.each( params, function ( name, value ) {
			// Only store the parameters that exist in the system
			if ( model.getItemByName( name ) ) {
				model.parameters[ name ] = Number( value );
			}
		} );
	};

	/**
	 * Get the value of a specific parameter
	 *
	 * @param {string} name Parameter name
	 * @return {number|string} Parameter value
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getParamValue = function ( name ) {
		return this.parameters[ name ];
	};

	/**
	 * Get the current state of the filters
	 *
	 * @return {Object} Filters current state
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getState = function () {
		var i,
			items = this.getItems(),
			result = {};

		for ( i = 0; i < items.length; i++ ) {
			result[ items[ i ].getName() ] = items[ i ].isSelected();
		}

		return result;
	};

	/**
	 * Analyze the groups and their filters and output an object representing
	 * the state of the parameters they represent.
	 *
	 * @return {Object} Parameter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFiltersToParameters = function () {
		var i, filterItems, anySelected,
			result = {},
			groupItems = this.getFilterGroups();

		$.each( groupItems, function( group, data ) {
			if ( data.type === 'send_unselected_if_any' ) {
				filterItems = data.filters;

				// First, check if any of the items are selected at all.
				// If none is selected, we're treating it as if they are
				// all false
				anySelected = false;
				for ( i = 0; i < filterItems.length; i++ ) {
					if ( filterItems[ i ].isSelected() ) {
						anySelected = true;
						break;
					}
				}

				// Go over the items and define the correct values
				for ( i = 0; i < filterItems.length; i++ ) {
					result[ filterItems[ i ].getName() ] = anySelected ?
						!filterItems[ i ].isSelected() : false;
				}
			}
		} );

		return result;
	};

	/**
	 * This is the opposite of the #getFiltersToParameters method; this goes over
	 * the parameters and translates into a selected/unselected value in the filters.
	 *
	 * @param {Object} [params] Parameters query object
	 * @return {Object} Filter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getParametersToFilters = function ( params ) {
		var i, p, filterItem, group, allItemsInGroup,
			groupMap = {},
			model = this,
			// Start with current state
			result = this.getState();

		params = params || ( new mw.Uri() ).query;

		$.each( params, function ( p, data ) {
			// Find the filter item
			filterItem = model.getItemByName( p );

			// Ignore if no filter item exists
			if ( filterItem ) {
				groupMap[ filterItem.getGroup() ] = groupMap[ filterItem.getGroup() ] || {};

				// Mark the group if it has any items that are selected
				groupMap[ filterItem.getGroup() ].hasSelected = Math.max(
					Number( groupMap[ filterItem.getGroup() ].hasSelected || 0 ),
					Number( params[ p ] )
				);

				// Add the relevant filter into the group map
				groupMap[ filterItem.getGroup() ].filters = groupMap[ filterItem.getGroup() ].filters || [];
				groupMap[ filterItem.getGroup() ].filters.push( filterItem );
			}
		} );

		// Now that we know the groups' selection states, we need to go over
		// the filters in the groups and mark their selected states appropriately
		$.each( groupMap, function ( group, data ) {
			if (
				model.groups[ group ].type === 'send_unselected_if_any' &&
				// For this type, we only care about groups that have any
				// parameters selected (meaning any 'hideXXX' = 1)
				data.hasSelected
			) {
				allItemsInGroup = model.groups[ group ].filters;

				for ( i = 0; i < allItemsInGroup.length; i++ ) {
					filterItem = allItemsInGroup[ i ];

					// Flip the definition between the parameter
					// state and the filter state
					// This is what the 'toggleSelected' value of the filter is
					result[ filterItem.getName() ] = !Number( params[ filterItem.getName() ] );
				}
			}
		} );
		return result;
	};

	/**
	 * Get the item that matches the given name
	 *
	 * @param {string} name Filter name
	 * @return {mw.rcfilters.dm.FilterItem} Filter item
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getItemByName = function ( name ) {
		return this.getItems().filter( function ( item ) {
			return name === item.getName();
		} )[ 0 ];
	};

	/**
	 * Toggle selected state of items by their names
	 *
	 * @param {Object} filterDef Filter definitions
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.updateFilters = function ( filterDef ) {
		var name, filterItem;

		for ( name in filterDef ) {
			filterItem = this.getItemByName( name );
			filterItem.toggleSelected( filterDef[ name ] );
		}
	};
	/**
	 * Find items whose labels match the given string
	 *
	 * @param {String} str Search string
	 * @return {Object} An object of items to show
	 *  arranged by their group names
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.findMatches = function ( str ) {
		var i,
			result = {},
			items = this.getItems();

		// Normalize so we can search strings regardless of case
		str = str.toLowerCase();
		for ( i = 0; i < items.length; i++ ) {
			if ( items[ i ].getLabel().toLowerCase().indexOf( str ) > -1 ) {
				result[ items[ i ].getGroup() ] = result[ items[ i ].getGroup() ] || [];
				result[ items[ i ].getGroup() ].push( items[ i ] );
			}
		}
		return result;
	};

} )( mediaWiki, jQuery );
