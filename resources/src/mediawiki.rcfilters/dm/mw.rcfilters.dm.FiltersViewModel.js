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
		this.invertedNamespaces = false;
		this.parameterMap = {};

		this.views = {};
		this.currentView = 'default';

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
	 * @event update
	 *
	 * Model has been updated
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

	/**
	 * @event invertChange
	 * @param {boolean} isInverted Namespace selected is inverted
	 *
	 * Namespace selection is inverted or straight forward
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
	 * @param {Array} filterGroups Filters definition
	 * @param {Object} [views] Extra views definition
	 *  Expected in the following format:
	 *  {
	 *     namespaces: {
	 *       label: 'namespaces', // Message key
	 *       trigger: ':',
	 *       groups: [
	 *         {
	 *            // Group info
	 *            name: 'namespaces' // Parameter name
	 *            title: 'namespaces' // Message key
	 *            type: 'string_options',
	 *            separator: ';',
	 *            labelPrefixKey: { 'default': 'rcfilters-tag-prefix-namespace', inverted: 'rcfilters-tag-prefix-namespace-inverted' },
	 *            fullCoverage: true
	 *            items: []
	 *         }
	 *       ]
	 *     }
	 *  }
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.initializeFilters = function ( filterGroups, views ) {
		var filterConflictResult, groupConflictResult,
			allViews = {},
			model = this,
			items = [],
			groupConflictMap = {},
			filterConflictMap = {},
			/*!
			 * Expand a conflict definition from group name to
			 * the list of all included filters in that group.
			 * We do this so that the direct relationship in the
			 * models are consistently item->items rather than
			 * mixing item->group with item->item.
			 *
			 * @param {Object} obj Conflict definition
			 * @return {Object} Expanded conflict definition
			 */
			expandConflictDefinitions = function ( obj ) {
				var result = {};

				$.each( obj, function ( key, conflicts ) {
					var filterName,
						adjustedConflicts = {};

					conflicts.forEach( function ( conflict ) {
						var filter;

						if ( conflict.filter ) {
							filterName = model.groups[ conflict.group ].getPrefixedName( conflict.filter );
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
		this.views = {};

		// Clone
		filterGroups = OO.copy( filterGroups );

		// Normalize definition from the server
		filterGroups.forEach( function ( data ) {
			var i;
			// What's this information needs to be normalized
			data.whatsThis = {
				body: data.whatsThisBody,
				header: data.whatsThisHeader,
				linkText: data.whatsThisLinkText,
				url: data.whatsThisUrl
			};

			// Title is a msg-key
			data.title = data.title ? mw.msg( data.title ) : data.name;

			// Filters are given to us with msg-keys, we need
			// to translate those before we hand them off
			for ( i = 0; i < data.filters.length; i++ ) {
				data.filters[ i ].label = data.filters[ i ].label ? mw.msg( data.filters[ i ].label ) : data.filters[ i ].name;
				data.filters[ i ].description = data.filters[ i ].description ? mw.msg( data.filters[ i ].description ) : '';
			}
		} );

		// Collect views
		allViews = $.extend( true, {
			'default': {
				title: mw.msg( 'rcfilters-filterlist-title' ),
				groups: filterGroups
			}
		}, views );

		// Go over all views
		$.each( allViews, function ( viewName, viewData ) {
			// Define the view
			model.views[ viewName ] = {
				name: viewData.name,
				title: viewData.title,
				trigger: viewData.trigger
			};

			// Go over groups
			viewData.groups.forEach( function ( groupData ) {
				var group = groupData.name;

				if ( !model.groups[ group ] ) {
					model.groups[ group ] = new mw.rcfilters.dm.FilterGroup(
						group,
						$.extend( true, {}, groupData, { view: viewName } )
					);
				}

				model.groups[ group ].initializeFilters( groupData.filters, groupData.default );
				items = items.concat( model.groups[ group ].getItems() );

				// Prepare conflicts
				if ( groupData.conflicts ) {
					// Group conflicts
					groupConflictMap[ group ] = groupData.conflicts;
				}

				groupData.filters.forEach( function ( itemData ) {
					var filterItem = model.groups[ group ].getItemByParamName( itemData.name );
					// Filter conflicts
					if ( itemData.conflicts ) {
						filterConflictMap[ filterItem.getName() ] = itemData.conflicts;
					}
				} );
			} );
		} );

		// Add item references to the model, for lookup
		this.addItems( items );

		// Expand conflicts
		groupConflictResult = expandConflictDefinitions( groupConflictMap );
		filterConflictResult = expandConflictDefinitions( filterConflictMap );

		// Set conflicts for groups
		$.each( groupConflictResult, function ( group, conflicts ) {
			model.groups[ group ].setConflicts( conflicts );
		} );

		// Set conflicts for items
		$.each( filterConflictResult, function ( filterName, conflicts ) {
			var filterItem = model.getItemByName( filterName );
			// set conflicts for items in the group
			filterItem.setConflicts( conflicts );
		} );

		// Create a map between known parameters and their models
		$.each( this.groups, function ( group, groupModel ) {
			if (
				groupModel.getType() === 'send_unselected_if_any' ||
				groupModel.getType() === 'boolean'
			) {
				// Individual filters
				groupModel.getItems().forEach( function ( filterItem ) {
					model.parameterMap[ filterItem.getParamName() ] = filterItem;
				} );
			} else if (
				groupModel.getType() === 'string_options' ||
				groupModel.getType() === 'single_option'
			) {
				// Group
				model.parameterMap[ groupModel.getName() ] = groupModel;
			}
		} );

		this.currentView = 'default';

		// Finish initialization
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
	 * Get the object that defines groups that match a certain view by their name.
	 *
	 * @param {string} [view] Requested view. If not given, uses current view
	 * @return {Object} Filter groups matching a display group
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFilterGroupsByView = function ( view ) {
		var result = {};

		view = view || this.getCurrentView();

		$.each( this.groups, function ( groupName, groupModel ) {
			if ( groupModel.getView() === view ) {
				result[ groupName ] = groupModel;
			}
		} );

		return result;
	};

	/**
	 * Get an array of filters matching the given display group.
	 *
	 * @param {string} [view] Requested view. If not given, uses current view
	 * @return {mw.rcfilters.dm.FilterItem} Filter items matching the group
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFiltersByView = function ( view ) {
		var groups,
			result = [];

		view = view || this.getCurrentView();

		groups = this.getFilterGroupsByView( view );

		$.each( groups, function ( groupName, groupModel ) {
			result = result.concat( groupModel.getItems() );
		} );

		return result;
	};

	/**
	 * Get the trigger for the requested view.
	 *
	 * @param {string} view View name
	 * @return {string} View trigger, if exists
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getViewTrigger = function ( view ) {
		return ( this.views[ view ] && this.views[ view ].trigger ) || '';
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
	 * Get an object representing default parameters state
	 *
	 * @return {Object} Default parameter values
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getDefaultParams = function () {
		var result = {};

		// Get default filter state
		$.each( this.groups, function ( name, model ) {
			$.extend( true, result, model.getDefaultParams() );
		} );

		return result;
	};

	/**
	 * Get a parameter representation of all sticky parameters
	 *
	 * @return {Object} Sticky parameter values
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getStickyParams = function () {
		var result = {};

		$.each( this.groups, function ( name, model ) {
			if ( model.isSticky() ) {
				$.extend( true, result, model.getDefaultParams() );
			}
		} );

		return result;
	};

	/**
	 * Get a filter representation of all sticky parameters
	 *
	 * @return {Object} Sticky filters values
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getStickyFiltersState = function () {
		var result = {};

		$.each( this.groups, function ( name, model ) {
			if ( model.isSticky() ) {
				$.extend( true, result, model.getSelectedState() );
			}
		} );

		return result;
	};

	/**
	 * Get a filter representation of all parameters that are marked
	 * as being excluded from saved query.
	 *
	 * @return {Object} Excluded filters values
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getExcludedFiltersState = function () {
		var result = {};

		$.each( this.groups, function ( name, model ) {
			if ( model.isExcludedFromSavedQueries() ) {
				$.extend( true, result, model.getSelectedState() );
			}
		} );

		return result;
	};

	/**
	 * Analyze the groups and their filters and output an object representing
	 * the state of the parameters they represent.
	 *
	 * @param {Object} [filterDefinition] An object defining the filter values,
	 *  keyed by filter names.
	 * @return {Object} Parameter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getParametersFromFilters = function ( filterDefinition ) {
		var groupItemDefinition,
			result = {},
			groupItems = this.getFilterGroups();

		if ( filterDefinition ) {
			groupItemDefinition = {};
			// Filter definition is "flat", but in effect
			// each group needs to tell us its result based
			// on the values in it. We need to split this list
			// back into groupings so we can "feed" it to the
			// loop below, and we need to expand it so it includes
			// all filters (set to false)
			this.getItems().forEach( function ( filterItem ) {
				groupItemDefinition[ filterItem.getGroupName() ] = groupItemDefinition[ filterItem.getGroupName() ] || {};
				groupItemDefinition[ filterItem.getGroupName() ][ filterItem.getName() ] = !!filterDefinition[ filterItem.getName() ];
			} );
		}

		$.each( groupItems, function ( group, model ) {
			$.extend(
				result,
				model.getParamRepresentation(
					groupItemDefinition ?
						groupItemDefinition[ group ] : null
				)
			);
		} );

		return result;
	};

	/**
	 * This is the opposite of the #getParametersFromFilters method; this goes over
	 * the given parameters and translates into a selected/unselected value in the filters.
	 *
	 * @param {Object} params Parameters query object
	 * @return {Object} Filter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFiltersFromParameters = function ( params ) {
		var groupMap = {},
			model = this,
			result = {};

		// Go over the given parameters, break apart to groupings
		// The resulting object represents the group with its parameter
		// values. For example:
		// {
		//    group1: {
		//       param1: "1",
		//       param2: "0",
		//       param3: "1"
		//    },
		//    group2: "param4|param5"
		// }
		$.each( params, function ( paramName, paramValue ) {
			var groupName,
				itemOrGroup = model.parameterMap[ paramName ];

			if ( itemOrGroup ) {
				groupName = itemOrGroup instanceof mw.rcfilters.dm.FilterItem ?
					itemOrGroup.getGroupName() : itemOrGroup.getName();

				groupMap[ groupName ] = groupMap[ groupName ] || {};
				groupMap[ groupName ][ paramName ] = paramValue;
			}
		} );

		// Go over all groups, so we make sure we get the complete output
		// even if the parameters don't include a certain group
		$.each( this.groups, function ( groupName, groupModel ) {
			result = $.extend( true, {}, result, groupModel.getFilterRepresentation( groupMap[ groupName ] ) );
		} );

		return result;
	};

	/**
	 * Get the highlight parameters based on current filter configuration
	 *
	 * @return {Object} Object where keys are `<filter name>_color` and values
	 *                  are the selected highlight colors.
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getHighlightParameters = function () {
		var result = {};

		this.getItems().forEach( function ( filterItem ) {
			result[ filterItem.getName() + '_color' ] = filterItem.getHighlightColor() || null;
		} );
		result.highlight = String( Number( this.isHighlightEnabled() ) );

		return result;
	};

	/**
	 * Extract the highlight values from given object. Since highlights are
	 * the same for filter and parameters, it doesn't matter which one is
	 * given; values will be returned with a full list of the highlights
	 * with colors or null values.
	 *
	 * @param {Object} representation Object containing representation of
	 *  some or all highlight values
	 * @return {Object} Object where keys are `<filter name>_color` and values
	 *                  are the selected highlight colors. The returned object
	 *                  contains all available filters either with a color value
	 *                  or with null.
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.extractHighlightValues = function ( representation ) {
		var result = {};

		this.getItems().forEach( function ( filterItem ) {
			var highlightName = filterItem.getName() + '_color';
			result[ highlightName ] = representation[ highlightName ] || null;
		} );

		return result;
	};

	/**
	 * Get an array of currently applied highlight colors
	 *
	 * @return {string[]} Currently applied highlight colors
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getCurrentlyUsedHighlightColors = function () {
		var result = [];

		this.getHighlightedItems().forEach( function ( filterItem ) {
			var color = filterItem.getHighlightColor();

			if ( result.indexOf( color ) === -1 ) {
				result.push( color );
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
	mw.rcfilters.dm.FiltersViewModel.prototype.sanitizeStringOptionGroup = function ( groupName, valueArray ) {
		var validNames = this.getGroupFilters( groupName ).map( function ( filterItem ) {
			return filterItem.getParamName();
		} );

		return mw.rcfilters.utils.normalizeParamOptions( valueArray, validNames );
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
			return !filterItem.getGroupModel().isHidden() && ( filterItem.isSelected() || filterItem.isHighlighted() );
		} );
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
			if ( !filterItem.getGroupModel().isSticky() ) {
				this.toggleFilterSelected( filterItem.getName(), false );
			}
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
		var i, searchIsEmpty,
			groupTitle,
			result = {},
			flatResult = [],
			view = this.getViewByTrigger( query.substr( 0, 1 ) ),
			items = this.getFiltersByView( view );

		// Normalize so we can search strings regardless of case and view
		query = query.trim().toLowerCase();
		if ( view !== 'default' ) {
			query = query.substr( 1 );
		}
		// Trim again to also intercept cases where the spaces were after the trigger
		// eg: '#   str'
		query = query.trim();

		// Check if the search if actually empty; this can be a problem when
		// we use prefixes to denote different views
		searchIsEmpty = query.length === 0;

		// item label starting with the query string
		for ( i = 0; i < items.length; i++ ) {
			if (
				searchIsEmpty ||
				items[ i ].getLabel().toLowerCase().indexOf( query ) === 0 ||
				(
					// For tags, we want the parameter name to be included in the search
					view === 'tags' &&
					items[ i ].getParamName().toLowerCase().indexOf( query ) > -1
				)
			) {
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
					searchIsEmpty ||
					items[ i ].getLabel().toLowerCase().indexOf( query ) > -1 ||
					items[ i ].getDescription().toLowerCase().indexOf( query ) > -1 ||
					groupTitle.toLowerCase().indexOf( query ) > -1 ||
					(
						// For tags, we want the parameter name to be included in the search
						view === 'tags' &&
						items[ i ].getParamName().toLowerCase().indexOf( query ) > -1
					)
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
	 * Get items that allow highlights even if they're not currently highlighted
	 *
	 * @return {mw.rcfilters.dm.FilterItem[]} Items supporting highlights
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getItemsSupportingHighlights = function () {
		return this.getItems().filter( function ( filterItem ) {
			return filterItem.isHighlightSupported();
		} );
	};

	/**
	 * Get all selected items
	 *
	 * @return {mw.rcfilters.dm.FilterItem[]} Selected items
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getSelectedItems = function () {
		var allSelected = [];

		$.each( this.getFilterGroups(), function ( groupName, groupModel ) {
			allSelected = allSelected.concat( groupModel.getSelectedItems() );
		} );

		return allSelected;
	};
	/**
	 * Switch the current view
	 *
	 * @param {string} view View name
	 * @fires update
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.switchView = function ( view ) {
		if ( this.views[ view ] && this.currentView !== view ) {
			this.currentView = view;
			this.emit( 'update' );
		}
	};

	/**
	 * Get the current view
	 *
	 * @return {string} Current view
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getCurrentView = function () {
		return this.currentView;
	};

	/**
	 * Get the label for the current view
	 *
	 * @param {string} viewName View name
	 * @return {string} Label for the current view
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getViewTitle = function ( viewName ) {
		viewName = viewName || this.getCurrentView();

		return this.views[ viewName ] && this.views[ viewName ].title;
	};

	/**
	 * Get an array of all available view names
	 *
	 * @return {string} Available view names
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getAvailableViews = function () {
		return Object.keys( this.views );
	};

	/**
	 * Get the view that fits the given trigger
	 *
	 * @param {string} trigger Trigger
	 * @return {string} Name of view
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getViewByTrigger = function ( trigger ) {
		var result = 'default';

		$.each( this.views, function ( name, data ) {
			if ( data.trigger === trigger ) {
				result = name;
			}
		} );

		return result;
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
	 * Toggle the inverted namespaces property on and off.
	 * Propagate the change to namespace filter items.
	 *
	 * @param {boolean} enable Inverted property is enabled
	 * @fires invertChange
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.toggleInvertedNamespaces = function ( enable ) {
		enable = enable === undefined ? !this.invertedNamespaces : enable;

		if ( this.invertedNamespaces !== enable ) {
			this.invertedNamespaces = enable;

			this.getFiltersByView( 'namespaces' ).forEach( function ( filterItem ) {
				filterItem.toggleInverted( this.invertedNamespaces );
			}.bind( this ) );

			this.emit( 'invertChange', this.invertedNamespaces );
		}
	};

	/**
	 * Check if the namespaces selection is set to be inverted
	 * @return {boolean}
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.areNamespacesInverted = function () {
		return !!this.invertedNamespaces;
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

	/**
	 * Return a version of the given string that is without any
	 * view triggers.
	 *
	 * @param {string} str Given string
	 * @return {string} Result
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.removeViewTriggers = function ( str ) {
		if ( this.getViewByTrigger( str.substr( 0, 1 ) ) !== 'default' ) {
			str = str.substr( 1 );
		}

		return str;
	};
}( mediaWiki, jQuery ) );
