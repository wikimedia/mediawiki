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
		this.highlightEnabled = false;
		this.parameterMap = {};

		// Events
		this.aggregate( { update: 'filterItemUpdate' } );
		this.connect( this, { filterItemUpdate: [ 'emit', 'itemUpdate' ] } );
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

	/**
	 * @event highlightChange
	 * @param {boolean} Highlight feature is enabled
	 *
	 * Highlight feature has been toggled enabled or disabled
	 */

	/* Methods */

	/**
	 * Re-assess the states of filter items based on the interactions between them
	 *
	 * @param {mw.rcfilters.dm.FilterItem} [item] Changed item. If not given, the
	 *  method will go over the state of all items
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.reassessFilterInteractions = function ( item ) {
		var allSelected,
			model = this,
			iterationItems = item !== undefined ? [ item ] : this.getItems();

		iterationItems.forEach( function ( checkedItem ) {
			var allCheckedItems = checkedItem.getSubset().concat( [ checkedItem.getName() ] ),
				groupModel = checkedItem.getGroupModel();

			// Check for subsets (included filters) plus the item itself:
			allCheckedItems.forEach( function ( filterItemName ) {
				var itemInSubset = model.getItemByName( filterItemName );

				itemInSubset.toggleIncluded(
					// If any of itemInSubset's supersets are selected, this item
					// is included
					itemInSubset.getSuperset().some( function ( supersetName ) {
						return ( model.getItemByName( supersetName ).isSelected() );
					} )
				);
			} );

			// Update coverage for the changed group
			if ( groupModel.isFullCoverage() ) {
				allSelected = groupModel.areAllSelected();
				groupModel.getItems().forEach( function ( filterItem ) {
					filterItem.toggleFullyCovered( allSelected );
				} );
			}
		} );

		// Check for conflicts
		// In this case, we must go over all items, since
		// conflicts are bidirectional and depend not only on
		// individual items, but also on the selected states of
		// the groups they're in.
		this.getItems().forEach( function ( filterItem ) {
			var inConflict = false,
				filterItemGroup = filterItem.getGroupModel();

			// For each item, see if that item is still conflicting
			$.each( model.groups, function ( groupName, groupModel ) {
				if ( filterItem.getGroupName() === groupName ) {
					// Check inside the group
					inConflict = groupModel.areAnySelectedInConflictWith( filterItem );
				} else {
					// According to the spec, if two items conflict from two different
					// groups, the conflict only lasts if the groups **only have selected
					// items that are conflicting**. If a group has selected items that
					// are conflicting and non-conflicting, the scope of the result has
					// expanded enough to completely remove the conflict.

					// For example, see two groups with conflicts:
					// userExpLevel: [
					//   {
					//     name: 'experienced',
					//     conflicts: [ 'unregistered' ]
					//   }
					// ],
					// registration: [
					//   {
					//     name: 'registered',
					//   },
					//   {
					//     name: 'unregistered',
					//   }
					// ]
					// If we select 'experienced', then 'unregistered' is in conflict (and vice versa),
					// because, inherently, 'experienced' filter only includes registered users, and so
					// both filters are in conflict with one another.
					// However, the minute we select 'registered', the scope of our results
					// has expanded to no longer have a conflict with 'experienced' filter, and
					// so the conflict is removed.

					// In our case, we need to check if the entire group conflicts with
					// the entire item's group, so we follow the above spec
					inConflict = (
						// The foreign group is in conflict with this item
						groupModel.areAllSelectedInConflictWith( filterItem ) &&
						// Every selected member of the item's own group is also
						// in conflict with the other group
						filterItemGroup.getSelectedItems().every( function ( otherGroupItem ) {
							return groupModel.areAllSelectedInConflictWith( otherGroupItem );
						} )
					);
				}

				// If we're in conflict, this will return 'false' which
				// will break the loop. Otherwise, we're not in conflict
				// and the loop continues
				return !inConflict;
			} );

			// Toggle the item state
			filterItem.toggleConflicted( inConflict );
		} );
	};

	/**
	 * Get whether the model has any conflict in its items
	 *
	 * @return {boolean} There is a conflict
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.hasConflict = function () {
		return this.getItems().some( function ( filterItem ) {
			return filterItem.isSelected() && filterItem.isConflicted();
		} );
	};

	/**
	 * Get the first item with a current conflict
	 *
	 * @return {mw.rcfilters.dm.FilterItem} Conflicted item
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFirstConflictedItem = function () {
		var conflictedItem;

		$.each( this.getItems(), function ( index, filterItem ) {
			if ( filterItem.isSelected() && filterItem.isConflicted() ) {
				conflictedItem = filterItem;
				return false;
			}
		} );

		return conflictedItem;
	};

	/**
	 * Set filters and preserve a group relationship based on
	 * the definition given by an object
	 *
	 * @param {Array} filters Filter group definition
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.initializeFilters = function ( filters ) {
		var i, filterItem, filterConflictResult, groupConflictResult, subsetNames,
			model = this,
			items = [],
			supersetMap = {},
			groupConflictMap = {},
			filterConflictMap = {},
			addArrayElementsUnique = function ( arr, elements ) {
				elements = Array.isArray( elements ) ? elements : [ elements ];

				elements.forEach( function ( element ) {
					if ( arr.indexOf( element ) === -1 ) {
						arr.push( element );
					}
				} );

				return arr;
			},
			expandConflictDefinitions = function ( obj ) {
				var result = {};

				$.each( obj, function ( key, conflicts ) {
					var filterName,
						adjustedConflicts = {};

					conflicts.forEach( function ( conflict ) {
						var filter;

						if ( conflict.filter ) {
							filterName = model.groups[ conflict.group ].getNamePrefix() + conflict.filter;
							filter = model.getItemByName( filterName );

							// Rename
							adjustedConflicts[ filterName ] = $.extend(
								{},
								conflict,
								{
									filter: filterName,
									item: filter
								}
							);
						} else {
							// This conflict is for an entire group. Split it up to
							// represent each filter

							// Get the relevant group items
							model.groups[ conflict.group ].getItems().forEach( function ( groupItem ) {
								// Rebuild the conflict
								adjustedConflicts[ groupItem.getName() ] = $.extend(
									{},
									conflict,
									{
										filter: groupItem.getName(),
										item: groupItem
									}
								);
							} );
						}
					} );

					result[ key ] = adjustedConflicts;
				} );

				return result;
			};

		// Reset
		this.clearItems();
		this.groups = {};

		filters.forEach( function ( data ) {
			var group = data.name;

			if ( !model.groups[ group ] ) {
				model.groups[ group ] = new mw.rcfilters.dm.FilterGroup( group, {
					type: data.type,
					title: mw.msg( data.title ),
					separator: data.separator,
					fullCoverage: !!data.fullCoverage,
					whatsThis: {
						body: data.whatsThisBody,
						header: data.whatsThisHeader,
						linkText: data.whatsThisLinkText,
						url: data.whatsThisUrl
					}
				} );
			}

			if ( data.conflicts ) {
				groupConflictMap[ group ] = data.conflicts;
			}

			for ( i = 0; i < data.filters.length; i++ ) {
				data.filters[ i ].subset = data.filters[ i ].subset || [];
				data.filters[ i ].subset = data.filters[ i ].subset.map( function ( el ) {
					return el.filter;
				} );

				filterItem = new mw.rcfilters.dm.FilterItem( data.filters[ i ].name, model.groups[ group ], {
					group: group,
					label: mw.msg( data.filters[ i ].label ),
					description: mw.msg( data.filters[ i ].description ),
					cssClass: data.filters[ i ].cssClass
				} );

				if ( data.filters[ i ].subset ) {
					subsetNames = [];
					data.filters[ i ].subset.forEach( function ( subsetFilterName ) { // eslint-disable-line no-loop-func
						var subsetName = model.groups[ group ].getNamePrefix() + subsetFilterName;
						// For convenience, we should store each filter's "supersets" -- these are
						// the filters that have that item in their subset list. This will just
						// make it easier to go through whether the item has any other items
						// that affect it (and are selected) at any given time
						supersetMap[ subsetName ] = supersetMap[ subsetName ] || [];
						addArrayElementsUnique(
							supersetMap[ subsetName ],
							filterItem.getName()
						);

						// Translate subset param name to add the group name, so we
						// get consistent naming. We know that subsets are only within
						// the same group
						subsetNames.push( subsetName );
					} );

					// Set translated subset
					filterItem.setSubset( subsetNames );
				}

				// Store conflicts
				if ( data.filters[ i ].conflicts ) {
					filterConflictMap[ filterItem.getName() ] = data.filters[ i ].conflicts;
				}

				if ( data.type === 'send_unselected_if_any' ) {
					// Store the default parameter state
					// For this group type, parameter values are direct
					model.defaultParams[ data.filters[ i ].name ] = Number( !!data.filters[ i ].default );
				}

				model.groups[ group ].addItems( filterItem );
				items.push( filterItem );
			}

			if ( data.type === 'string_options' && data.default ) {
				// Store the default parameter group state
				// For this group, the parameter is group name and value is the names
				// of selected items
				model.defaultParams[ group ] = model.sanitizeStringOptionGroup(
					group,
					data.default.split( model.groups[ group ].getSeparator() )
				).join( model.groups[ group ].getSeparator() );
			}
		} );

		// Add items to the model
		this.addItems( items );

		// Expand conflicts
		groupConflictResult = expandConflictDefinitions( groupConflictMap );
		filterConflictResult = expandConflictDefinitions( filterConflictMap );

		// Set conflicts for groups
		$.each( groupConflictResult, function ( group, conflicts ) {
			model.groups[ group ].setConflicts( conflicts );
		} );

		items.forEach( function ( filterItem ) {
			// Apply the superset map
			filterItem.setSuperset( supersetMap[ filterItem.getName() ] );

			// set conflicts for item
			if ( filterConflictResult[ filterItem.getName() ] ) {
				filterItem.setConflicts( filterConflictResult[ filterItem.getName() ] );
			}
		} );

		// Create a map between known parameters and their models
		$.each( this.groups, function ( group, groupModel ) {
			if ( groupModel.getType() === 'send_unselected_if_any' ) {
				// Individual filters
				groupModel.getItems().forEach( function ( filterItem ) {
					model.parameterMap[ filterItem.getParamName() ] = filterItem;
				} );
			} else if ( groupModel.getType() === 'string_options' ) {
				// Group
				model.parameterMap[ groupModel.getName() ] = groupModel;
			}
		} );

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

		this.toggleFiltersSelected( defaultFilterStates );
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
		var result = {},
			groupItems = filterGroups || this.getFilterGroups();

		$.each( groupItems, function ( group, model ) {
			$.extend( result, model.getParamRepresentation() );
		} );

		return result;
	};

	/**
	 * Get the highlight parameters based on current filter configuration
	 *
	 * @return {object} Object where keys are "<filter name>_color" and values
	 *                  are the selected highlight colors.
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getHighlightParameters = function () {
		var result = { highlight: Number( this.isHighlightEnabled() ) };

		this.getItems().forEach( function ( filterItem ) {
			result[ filterItem.getName() + '_color' ] = filterItem.getHighlightColor();
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
				return filterItem.getParamName();
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
		// Check if there are either any selected items or any items
		// that have highlight enabled
		return !this.getItems().some( function ( filterItem ) {
			return filterItem.isSelected() || filterItem.isHighlighted();
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
		var i,
			groupMap = {},
			model = this,
			base = this.getDefaultParams(),
			result = {};

		params = $.extend( {}, base, params );

		// Go over the given parameters
		$.each( params, function ( paramName, paramValue ) {
			var itemOrGroup = model.parameterMap[ paramName ];

			if ( itemOrGroup instanceof mw.rcfilters.dm.FilterItem ) {
				// Mark the group if it has any items that are selected
				groupMap[ itemOrGroup.getGroupName() ] = groupMap[ itemOrGroup.getGroupName() ] || {};
				groupMap[ itemOrGroup.getGroupName() ].hasSelected = (
					groupMap[ itemOrGroup.getGroupName() ].hasSelected ||
					!!Number( paramValue )
				);

				// Add filters
				groupMap[ itemOrGroup.getGroupName() ].filters = groupMap[ itemOrGroup.getGroupName() ].filters || [];
				groupMap[ itemOrGroup.getGroupName() ].filters.push( itemOrGroup );
			} else if ( itemOrGroup instanceof mw.rcfilters.dm.FilterGroup ) {
				groupMap[ itemOrGroup.getName() ] = groupMap[ itemOrGroup.getName() ] || {};
				// This parameter represents a group (values are the filters)
				// this is equivalent to checking if the group is 'string_options'
				groupMap[ itemOrGroup.getName() ].filters = itemOrGroup.getItems();
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

					result[ filterItem.getName() ] = groupMap[ filterItem.getGroupName() ].hasSelected ?
						// Flip the definition between the parameter
						// state and the filter state
						// This is what the 'toggleSelected' value of the filter is
						!Number( params[ filterItem.getParamName() ] ) :
						// Otherwise, there are no selected items in the
						// group, which means the state is false
						false;
				}
			} else if ( model.groups[ group ].getType() === 'string_options' ) {
				paramValues = model.sanitizeStringOptionGroup(
					group,
					params[ group ].split(
						model.groups[ group ].getSeparator()
					)
				);

				for ( i = 0; i < allItemsInGroup.length; i++ ) {
					filterItem = allItemsInGroup[ i ];

					result[ filterItem.getName() ] = (
							// If it is the word 'all'
							paramValues.length === 1 && paramValues[ 0 ] === 'all' ||
							// All values are written
							paramValues.length === model.groups[ group ].getItemCount()
						) ?
						// All true (either because all values are written or the term 'all' is written)
						// is the same as all filters set to true
						true :
						// Otherwise, the filter is selected only if it appears in the parameter values
						paramValues.indexOf( filterItem.getParamName() ) > -1;
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
		this.getItems().forEach( function ( filterItem ) {
			this.toggleFilterSelected( filterItem.getName(), false );
		}.bind( this ) );
	};

	/**
	 * Toggle selected state of one item
	 *
	 * @param {string} name Name of the filter item
	 * @param {boolean} [isSelected] Filter selected state
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.toggleFilterSelected = function ( name, isSelected ) {
		var item = this.getItemByName( name );

		if ( item ) {
			item.toggleSelected( isSelected );
		}
	};

	/**
	 * Toggle selected state of items by their names
	 *
	 * @param {Object} filterDef Filter definitions
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.toggleFiltersSelected = function ( filterDef ) {
		Object.keys( filterDef ).forEach( function ( name ) {
			this.toggleFilterSelected( name, filterDef[ name ] );
		}.bind( this ) );
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
	 * @param {string} query Search string
	 * @param {boolean} [returnFlat] Return a flat array. If false, the result
	 *  is an object whose keys are the group names and values are an array of
	 *  filters per group. If set to true, returns an array of filters regardless
	 *  of their groups.
	 * @return {Object} An object of items to show
	 *  arranged by their group names
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.findMatches = function ( query, returnFlat ) {
		var i,
			groupTitle,
			result = {},
			flatResult = [],
			items = this.getItems();

		// Normalize so we can search strings regardless of case
		query = query.toLowerCase();

		// item label starting with the query string
		for ( i = 0; i < items.length; i++ ) {
			if ( items[ i ].getLabel().toLowerCase().indexOf( query ) === 0 ) {
				result[ items[ i ].getGroupName() ] = result[ items[ i ].getGroupName() ] || [];
				result[ items[ i ].getGroupName() ].push( items[ i ] );
				flatResult.push( items[ i ] );
			}
		}

		if ( $.isEmptyObject( result ) ) {
			// item containing the query string in their label, description, or group title
			for ( i = 0; i < items.length; i++ ) {
				groupTitle = items[ i ].getGroupModel().getTitle();
				if (
					items[ i ].getLabel().toLowerCase().indexOf( query ) > -1 ||
					items[ i ].getDescription().toLowerCase().indexOf( query ) > -1 ||
					groupTitle.toLowerCase().indexOf( query ) > -1
				) {
					result[ items[ i ].getGroupName() ] = result[ items[ i ].getGroupName() ] || [];
					result[ items[ i ].getGroupName() ].push( items[ i ] );
					flatResult.push( items[ i ] );
				}
			}
		}

		return returnFlat ? flatResult : result;
	};

	/**
	 * Get items that are highlighted
	 *
	 * @return {mw.rcfilters.dm.FilterItem[]} Highlighted items
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getHighlightedItems = function () {
		return this.getItems().filter( function ( filterItem ) {
			return filterItem.isHighlightSupported() &&
				filterItem.getHighlightColor();
		} );
	};

	/**
	 * Toggle the highlight feature on and off.
	 * Propagate the change to filter items.
	 *
	 * @param {boolean} enable Highlight should be enabled
	 * @fires highlightChange
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.toggleHighlight = function ( enable ) {
		enable = enable === undefined ? !this.highlightEnabled : enable;

		if ( this.highlightEnabled !== enable ) {
			this.highlightEnabled = enable;

			this.getItems().forEach( function ( filterItem ) {
				filterItem.toggleHighlight( this.highlightEnabled );
			}.bind( this ) );

			this.emit( 'highlightChange', this.highlightEnabled );
		}
	};

	/**
	 * Check if the highlight feature is enabled
	 * @return {boolean}
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.isHighlightEnabled = function () {
		return !!this.highlightEnabled;
	};

	/**
	 * Set highlight color for a specific filter item
	 *
	 * @param {string} filterName Name of the filter item
	 * @param {string} color Selected color
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.setHighlightColor = function ( filterName, color ) {
		this.getItemByName( filterName ).setHighlightColor( color );
	};

	/**
	 * Clear highlight for a specific filter item
	 *
	 * @param {string} filterName Name of the filter item
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.clearHighlightColor = function ( filterName ) {
		this.getItemByName( filterName ).clearHighlightColor();
	};

	/**
	 * Clear highlight for all filter items
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.clearAllHighlightColors = function () {
		this.getItems().forEach( function ( filterItem ) {
			filterItem.clearHighlightColor();
		} );
	};
}( mediaWiki, jQuery ) );
