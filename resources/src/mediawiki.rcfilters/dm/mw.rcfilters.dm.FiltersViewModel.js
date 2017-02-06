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
		this.reassessFilterInteractions( item );

		this.emit( 'itemUpdate', item );
	};

	/**
	 * Re-assess the states of filter items based on the interactions between them
	 *
	 * NOTE: The group-level state for 'fullCoverage' is reassessed at the group
	 * level. This, however, requires knowledge of all filters regardless of grouping
	 * (because conflicts and/or subsets may arise between groups) and so this is
	 * done in the general view model.
	 * This method should not worry about the group-level coverage case.
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Changed item
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.reassessFilterInteractions = function ( item ) {
		var model = this,
			changeIncludedIfNeeded = function( itemAffecting, itemInSubset ) {
				if (
					itemAffecting.getName() !== itemInSubset.getName() &&
					itemAffecting.existsInSubset( itemInSubset.getName() )
				) {
					// itemInSubset is in itemA's subsets. Check first if itemInSubset
					// is in any other **selected** items that also have it as their
					// subset
					if (
						// If any of itemInSubset's supersets are selected (not including
						// itemAffecting), it means that we shouldn't touch the state
						// of this item's included status
						!itemInSubset.getSuperset().some( function ( supersetName ) {
							return (
								itemAffecting.getName() !== supersetName &&
								model.getItemByName( supersetName ).isSelected()
							);
						} )
					) {
						itemInSubset.toggleIncluded( itemAffecting.isSelected() );
					}
				}
			};

		// Check for subsets (included filters):
		this.getItems().forEach( function ( filterItem ) {
			// 1. Check if item is a subset of filterItem (hence, item.toggleIncluded( ... ))
			changeIncludedIfNeeded( item, filterItem );
			// 2. Check if filterItem is a subset of item (hence, filterItem.toggleIncluded( ... ))
			changeIncludedIfNeeded( filterItem, item );
		} );
	};

	/**
	 * Set filters and preserve a group relationship based on
	 * the definition given by an object
	 *
	 * @param {Object} filters Filter group definition
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.initializeFilters = function ( filters ) {
		var i, filterItem, selectedFilterNames,
			model = this,
			items = [],
			addArrayElementsUnique = function ( arr, elements ) {
				elements = Array.isArray( elements ) ? elements : [ elements ];

				elements.forEach( function ( element ) {
					if ( arr.indexOf( element ) === -1 ) {
						arr.push( element );
					}
				} );

				return arr;
			},
			conflictMap = {},
			supersetMap = {};

		// Reset
		this.clearItems();
		this.groups = {};

		$.each( filters, function ( group, data ) {
			if ( !model.groups[ group ] ) {
				model.groups[ group ] = new mw.rcfilters.dm.FilterGroup( group, {
					type: data.type,
					title: data.title,
					separator: data.separator,
					fullCoverage: !!data.fullCoverage
				} );
			}

			selectedFilterNames = [];
			for ( i = 0; i < data.filters.length; i++ ) {
				filterItem = new mw.rcfilters.dm.FilterItem( data.filters[ i ].name, {
					group: group,
					label: data.filters[ i ].label,
					description: data.filters[ i ].description,
					selected: data.filters[ i ].selected,
					'default': data.filters[ i ].default,
					subset: data.filters[ i ].subset
				} );

				// For convenience, we should store each filter's "supersets" -- these are
				// the filters that have that item in their subset list. This will just
				// make it easier to go through whether the item has any other items
				// that affect it (and are selected) at any given time
				if ( data.filters[ i ].subset ) {
					data.filters[ i ].subset.forEach( function ( subsetFilterName ) { // eslint-disable-line no-loop-func
						supersetMap[ subsetFilterName ] = supersetMap[ subsetFilterName ] || [];
						addArrayElementsUnique(
							supersetMap[ subsetFilterName ],
							filterItem.getName()
						);
					} );
				}

				// Conflicts are bi-directional, which means FilterA can define having
				// a conflict with FilterB, and this conflict should appear in **both**
				// filter definitions.
				// We need to remap all the 'conflicts' so they reflect the entire state
				// in either direction regardless of which filter defined the other as conflicting.
				if ( data.filters[ i ].conflicts ) {
					conflictMap[ filterItem.getName() ] = conflictMap[ filterItem.getName() ] || [];
					addArrayElementsUnique(
						conflictMap[ filterItem.getName() ],
						data.filters[ i ].conflicts
					);

					data.filters[ i ].conflicts.forEach( function ( conflictingFilterName ) { // eslint-disable-line no-loop-func
						// Add this filter to the conflicts of each of the filters in its list
						conflictMap[ conflictingFilterName ] = conflictMap[ conflictingFilterName ] || [];
						addArrayElementsUnique(
							conflictMap[ conflictingFilterName ],
							filterItem.getName()
						);
					} );
				}

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

				model.groups[ group ].addItems( filterItem );
				items.push( filterItem );
			}

			if ( data.type === 'string_options' ) {
				// Store the default parameter group state
				// For this group, the parameter is group name and value is the names
				// of selected items
				model.defaultParams[ group ] = model.sanitizeStringOptionGroup( group, selectedFilterNames ).join( model.groups[ group ].getSeparator() );
			}
		} );

		items.forEach( function ( filterItem ) {
			// Apply conflict map to the items
			// Now that we mapped all items and conflicts bi-directionally
			// we need to apply the definition to each filter again
			filterItem.setConflicts( conflictMap[ filterItem.getName() ] );

			// Apply the superset map
			filterItem.setSuperset( supersetMap[ filterItem.getName() ] );
		} );

		// Add items to the model
		this.addItems( items );

		this.emit( 'initialize' );

		// Select based on defaults
		this.setFiltersToDefaults();
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
	 * Get the object that defines groups by their name.
	 *
	 * @return {Object} Filter groups
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFilterGroups = function () {
		return this.groups;
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
				conflicted: items[ i ].isConflicted(),
				included: items[ i ].isIncluded()
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

		$.each( groupItems, function ( group, model ) {
			filterItems = model.getItems();

			if ( model.getType() === 'send_unselected_if_any' ) {
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
			} else if ( model.getType() === 'string_options' ) {
				values = [];
				for ( i = 0; i < filterItems.length; i++ ) {
					if ( filterItems[ i ].isSelected() ) {
						values.push( filterItems[ i ].getName() );
					}
				}

				if ( values.length === 0 || values.length === filterItems.length ) {
					result[ group ] = 'all';
				} else {
					result[ group ] = values.join( model.getSeparator() );
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
			validNames = this.getGroupFilters( groupName ).map( function ( filterItem ) {
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
				groupMap[ paramName ] = { filters: model.groups[ paramName ].getItems() };
			}
		} );

		// Now that we know the groups' selection states, we need to go over
		// the filters in the groups and mark their selected states appropriately
		$.each( groupMap, function ( group, data ) {
			var paramValues, filterItem,
				allItemsInGroup = data.filters;

			if ( model.groups[ group ].getType() === 'send_unselected_if_any' ) {
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
			} else if ( model.groups[ group ].getType() === 'string_options' ) {
				paramValues = model.sanitizeStringOptionGroup( group, params[ group ].split( model.groups[ group ].getSeparator() ) );

				for ( i = 0; i < allItemsInGroup.length; i++ ) {
					filterItem = allItemsInGroup[ i ];

					result[ filterItem.getName() ] = (
							// If it is the word 'all'
							paramValues.length === 1 && paramValues[ 0 ] === 'all' ||
							// All values are written
							paramValues.length === model.groups[ group ].getItemCount()
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
	 * Get a group model from its name
	 *
	 * @param {string} groupName Group name
	 * @return {mw.rcfilters.dm.FilterGroup} Group model
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getGroup = function ( groupName ) {
		return this.groups[ groupName ];
	};

	/**
	 * Get all filters within a specified group by its name
	 *
	 * @param {string} groupName Group name
	 * @return {mw.rcfilters.dm.FilterItem[]} Filters belonging to this group
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getGroupFilters = function ( groupName ) {
		return ( this.getGroup( groupName ) && this.getGroup( groupName ).getItems() ) || [];
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
