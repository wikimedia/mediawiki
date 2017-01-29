( function ( mw, $ ) {
	/**
	 * View model for the filters selection and display
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 */
	mw.rcfilters.dm.FiltersViewModel = function MwRcfiltersDmFiltersViewModel() {
		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.groups = {};
		this.excludedByMap = {};
		this.defaultParams = {};
		this.defaultFiltersEmpty = null;

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
	 *
	 * Filter item has changed
	 */

	/* Methods */

	/**
	 * Respond to filter item change.
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Updated filter
	 * @fires itemUpdate
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.onFilterItemUpdate = function ( item ) {
		// Reapply the active state of filters
		this.reapplyActiveFilters( item );

		this.emit( 'itemUpdate', item );
	};

	/**
	 * Calculate the active state of the filters, based on selected filters in the group.
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Changed item
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.reapplyActiveFilters = function ( item ) {
		var selectedItemsCount,
			group = item.getGroup(),
			model = this;
		if (
			!this.groups[ group ].exclusionType ||
			this.groups[ group ].exclusionType === 'default'
		) {
			// Default behavior
			// If any parameter is selected, but:
			// - If there are unselected items in the group, they are inactive
			// - If the entire group is selected, all are inactive

			// Check what's selected in the group
			selectedItemsCount = this.groups[ group ].filters.filter( function ( filterItem ) {
				return filterItem.isSelected();
			} ).length;

			this.groups[ group ].filters.forEach( function ( filterItem ) {
				filterItem.toggleActive(
					selectedItemsCount > 0 ?
						// If some items are selected
						(
							selectedItemsCount === model.groups[ group ].filters.length ?
							// If **all** items are selected, they're all inactive
							false :
							// If not all are selected, then the selected are active
							// and the unselected are inactive
							filterItem.isSelected()
						) :
						// No item is selected, everything is active
						true
				);
			} );
		} else if ( this.groups[ group ].exclusionType === 'explicit' ) {
			// Explicit behavior
			// - Go over the list of excluded filters to change their
			//   active states accordingly

			// For each item in the list, see if there are other selected
			// filters that also exclude it. If it does, it will still be
			// inactive.

			item.getExcludedFilters().forEach( function ( filterName ) {
				var filterItem = model.getItemByName( filterName );

				// Note to reduce confusion:
				// - item is the filter whose state changed and should exclude the other filters
				//   in its list of exclusions
				// - filterItem is the filter that is potentially being excluded by the current item
				// - anotherExcludingFilter is any other filter that excludes filterItem; we must check
				//   if that filter is selected, because if it is, we should not touch the excluded item
				if (
					// Check if there are any filters (other than the current one)
					// that also exclude the filterName
					!model.excludedByMap[ filterName ].some( function ( anotherExcludingFilterName ) {
						var anotherExcludingFilter = model.getItemByName( anotherExcludingFilterName );

						return (
							anotherExcludingFilterName !== item.getName() &&
							anotherExcludingFilter.isSelected()
						);
					} )
				) {
					// Only change the state for filters that aren't
					// also affected by other excluding selected filters
					filterItem.toggleActive( !item.isSelected() );
				}
			} );
		}
	};

	/**
	 * Set filters and preserve a group relationship based on
	 * the definition given by an object
	 *
	 * @param {Object} filters Filter group definition
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.initializeFilters = function ( filters ) {
		var i, filterItem, selectedFilterNames, excludedFilters,
			model = this,
			items = [],
			addToMap = function ( excludedFilters ) {
				excludedFilters.forEach( function ( filterName ) {
					model.excludedByMap[ filterName ] = model.excludedByMap[ filterName ] || [];
					model.excludedByMap[ filterName ].push( filterItem.getName() );
				} );
			};

		// Reset
		this.clearItems();
		this.groups = {};
		this.excludedByMap = {};

		$.each( filters, function ( group, data ) {
			model.groups[ group ] = model.groups[ group ] || {};
			model.groups[ group ].filters = model.groups[ group ].filters || [];

			model.groups[ group ].title = data.title;
			model.groups[ group ].type = data.type;
			model.groups[ group ].separator = data.separator || '|';
			model.groups[ group ].exclusionType = data.exclusionType || 'default';

			selectedFilterNames = [];
			for ( i = 0; i < data.filters.length; i++ ) {
				excludedFilters = data.filters[ i ].excludes || [];

				filterItem = new mw.rcfilters.dm.FilterItem( data.filters[ i ].name, {
					group: group,
					label: data.filters[ i ].label,
					description: data.filters[ i ].description,
					selected: data.filters[ i ].selected,
					excludes: excludedFilters,
					'default': data.filters[ i ].default
				} );

				// Map filters and what excludes them
				addToMap( excludedFilters );

				if ( data.type === 'send_unselected_if_any' ) {
					// Store the default parameter state
					// For this group type, parameter values are direct
					model.defaultParams[ data.filters[ i ].name ] = Number( !!data.filters[ i ].default );
				} else if (
					data.type === 'string_options' &&
					data.filters[ i ].default
				) {
					selectedFilterNames.push( data.filters[ i ].name );
				}

				model.groups[ group ].filters.push( filterItem );
				items.push( filterItem );
			}

			if ( data.type === 'string_options' ) {
				// Store the default parameter group state
				// For this group, the parameter is group name and value is the names
				// of selected items
				model.defaultParams[ group ] = model.sanitizeStringOptionGroup( group, selectedFilterNames ).join( model.groups[ group ].separator );
			}
		} );

		this.addItems( items );

		this.emit( 'initialize' );
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
	 * Get the object that defines groups and their filter items.
	 * The structure of this response:
	 * {
	 *   groupName: {
	 *     title: {string} Group title
	 *     type: {string} Group type
	 *     filters: {string[]} Filters in the group
	 *   }
	 * }
	 *
	 * @return {Object} Filter groups
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFilterGroups = function () {
		return this.groups;
	};

	/**
	 * Get the definition object for an individual group.
	 * @param {string} groupName Group name
	 * @return {Object|null} Filter group
	 * @see #getFilterGroups
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFilterGroup = function ( groupName ) {
		return this.groups[ groupName ] || null;
	};

	/**
	 * Get the current state of the filters.
	 *
	 * Checks whether the filter group is active. This means at least one
	 * filter is selected, but not all filters are selected.
	 *
	 * @param {string} groupName Group name
	 * @return {boolean} Filter group is active
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.isFilterGroupActive = function ( groupName ) {
		var count = 0,
			filters = this.groups[ groupName ].filters;

		filters.forEach( function ( filterItem ) {
			count += Number( filterItem.isSelected() );
		} );

		return (
			count > 0 &&
			count < filters.length
		);
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
				model.parameters[ name ] = value;
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
	 * Get the current selected state of the filters
	 *
	 * @return {Object} Filters selected state
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getSelectedState = function () {
		var i,
			items = this.getItems(),
			result = {};

		for ( i = 0; i < items.length; i++ ) {
			result[ items[ i ].getName() ] = items[ i ].isSelected();
		}

		return result;
	};

	/**
	 * Get the current full state of the filters
	 *
	 * @return {Object} Filters full state
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFullState = function () {
		var i,
			items = this.getItems(),
			result = {};

		for ( i = 0; i < items.length; i++ ) {
			result[ items[ i ].getName() ] = {
				selected: items[ i ].isSelected(),
				active: items[ i ].isActive()
			};
		}

		return result;
	};

	/**
	 * Get the default parameters object
	 *
	 * @return {Object} Default parameter values
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getDefaultParams = function () {
		return this.defaultParams;
	};

	/**
	 * Set all filter states to default values
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.setFiltersToDefaults = function () {
		var defaultFilterStates = this.getFiltersFromParameters( this.getDefaultParams() );

		this.updateFilters( defaultFilterStates );
	};

	/**
	 * Analyze the groups and their filters and output an object representing
	 * the state of the parameters they represent.
	 *
	 * @param {Object} [filterGroups] An object defining the filter groups to
	 *  translate to parameters. Its structure must follow that of this.groups
	 *  see #getFilterGroups
	 * @return {Object} Parameter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getParametersFromFilters = function ( filterGroups ) {
		var i, filterItems, anySelected, values,
			result = {},
			groupItems = filterGroups || this.getFilterGroups();

		$.each( groupItems, function ( group, data ) {
			filterItems = data.filters;

			if ( data.type === 'send_unselected_if_any' ) {
				// First, check if any of the items are selected at all.
				// If none is selected, we're treating it as if they are
				// all false
				anySelected = filterItems.some( function ( filterItem ) {
					return filterItem.isSelected();
				} );

				// Go over the items and define the correct values
				for ( i = 0; i < filterItems.length; i++ ) {
					result[ filterItems[ i ].getName() ] = anySelected ?
						Number( !filterItems[ i ].isSelected() ) : 0;
				}
			} else if ( data.type === 'string_options' ) {
				values = [];
				for ( i = 0; i < filterItems.length; i++ ) {
					if ( filterItems[ i ].isSelected() ) {
						values.push( filterItems[ i ].getName() );
					}
				}

				if ( values.length === 0 || values.length === filterItems.length ) {
					result[ group ] = 'all';
				} else {
					result[ group ] = values.join( data.separator );
				}
			}
		} );

		return result;
	};

	/**
	 * Sanitize value group of a string_option groups type
	 * Remove duplicates and make sure to only use valid
	 * values.
	 *
	 * @private
	 * @param {string} groupName Group name
	 * @param {string[]} valueArray Array of values
	 * @return {string[]} Array of valid values
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.sanitizeStringOptionGroup = function( groupName, valueArray ) {
		var result = [],
			validNames = this.groups[ groupName ].filters.map( function ( filterItem ) {
				return filterItem.getName();
			} );

		if ( valueArray.indexOf( 'all' ) > -1 ) {
			// If anywhere in the values there's 'all', we
			// treat it as if only 'all' was selected.
			// Example: param=valid1,valid2,all
			// Result: param=all
			return [ 'all' ];
		}

		// Get rid of any dupe and invalid parameter, only output
		// valid ones
		// Example: param=valid1,valid2,invalid1,valid1
		// Result: param=valid1,valid2
		valueArray.forEach( function ( value ) {
			if (
				validNames.indexOf( value ) > -1 &&
				result.indexOf( value ) === -1
			) {
				result.push( value );
			}
		} );

		return result;
	};

	/**
	 * Check whether the current filter state is set to all false.
	 *
	 * @return {boolean} Current filters are all empty
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.areCurrentFiltersEmpty = function () {
		var currFilters = this.getSelectedState();

		return Object.keys( currFilters ).every( function ( filterName ) {
			return !currFilters[ filterName ];
		} );
	};

	/**
	 * Check whether the default values of the filters are all false.
	 *
	 * @return {boolean} Default filters are all false
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.areDefaultFiltersEmpty = function () {
		var defaultFilters;

		if ( this.defaultFiltersEmpty !== null ) {
			// We only need to do this test once,
			// because defaults are set once per session
			defaultFilters = this.getFiltersFromParameters();
			this.defaultFiltersEmpty = Object.keys( defaultFilters ).every( function ( filterName ) {
				return !defaultFilters[ filterName ];
			} );
		}

		return this.defaultFiltersEmpty;
	};

	/**
	 * This is the opposite of the #getParametersFromFilters method; this goes over
	 * the given parameters and translates into a selected/unselected value in the filters.
	 *
	 * @param {Object} params Parameters query object
	 * @return {Object} Filter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFiltersFromParameters = function ( params ) {
		var i, filterItem,
			groupMap = {},
			model = this,
			base = this.getDefaultParams(),
			result = {};

		params = $.extend( {}, base, params );

		$.each( params, function ( paramName, paramValue ) {
			// Find the filter item
			filterItem = model.getItemByName( paramName );
			// Ignore if no filter item exists
			if ( filterItem ) {
				groupMap[ filterItem.getGroup() ] = groupMap[ filterItem.getGroup() ] || {};

				// Mark the group if it has any items that are selected
				groupMap[ filterItem.getGroup() ].hasSelected = (
					groupMap[ filterItem.getGroup() ].hasSelected ||
					!!Number( paramValue )
				);

				// Add the relevant filter into the group map
				groupMap[ filterItem.getGroup() ].filters = groupMap[ filterItem.getGroup() ].filters || [];
				groupMap[ filterItem.getGroup() ].filters.push( filterItem );
			} else if ( model.groups.hasOwnProperty( paramName ) ) {
				// This parameter represents a group (values are the filters)
				// this is equivalent to checking if the group is 'string_options'
				groupMap[ paramName ] = { filters: model.groups[ paramName ].filters };
			}
		} );

		// Now that we know the groups' selection states, we need to go over
		// the filters in the groups and mark their selected states appropriately
		$.each( groupMap, function ( group, data ) {
			var paramValues, filterItem,
				allItemsInGroup = data.filters;

			if ( model.groups[ group ].type === 'send_unselected_if_any' ) {
				for ( i = 0; i < allItemsInGroup.length; i++ ) {
					filterItem = allItemsInGroup[ i ];

					result[ filterItem.getName() ] = data.hasSelected ?
						// Flip the definition between the parameter
						// state and the filter state
						// This is what the 'toggleSelected' value of the filter is
						!Number( params[ filterItem.getName() ] ) :
						// Otherwise, there are no selected items in the
						// group, which means the state is false
						false;
				}
			} else if ( model.groups[ group ].type === 'string_options' ) {
				paramValues = model.sanitizeStringOptionGroup( group, params[ group ].split( model.groups[ group ].separator ) );

				for ( i = 0; i < allItemsInGroup.length; i++ ) {
					filterItem = allItemsInGroup[ i ];

					result[ filterItem.getName() ] = (
							// If it is the word 'all'
							paramValues.length === 1 && paramValues[ 0 ] === 'all' ||
							// All values are written
							paramValues.length === model.groups[ group ].filters.length
						) ?
						// All true (either because all values are written or the term 'all' is written)
						// is the same as all filters set to false
						false :
						// Otherwise, the filter is selected only if it appears in the parameter values
						paramValues.indexOf( filterItem.getName() ) > -1;
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
	 * Set all filters to false or empty/all
	 * This is equivalent to display all.
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.emptyAllFilters = function () {
		var filters = {};

		this.getItems().forEach( function ( filterItem ) {
			filters[ filterItem.getName() ] = false;
		} );

		// Update filters
		this.updateFilters( filters );
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
	 * @param {string} str Search string
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

}( mediaWiki, jQuery ) );
