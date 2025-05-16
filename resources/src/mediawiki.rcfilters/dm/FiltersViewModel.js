const FilterGroup = require( './FilterGroup.js' ),
	FilterItem = require( './FilterItem.js' ),
	utils = require( '../utils.js' );

/**
 * View model for the filters selection and display.
 *
 * @class mw.rcfilters.dm.FiltersViewModel
 * @ignore
 * @mixes OO.EventEmitter
 * @mixes OO.EmitterList
 */
const FiltersViewModel = function MwRcfiltersDmFiltersViewModel() {
	// Mixin constructor
	OO.EventEmitter.call( this );
	OO.EmitterList.call( this );

	this.groups = {};
	this.defaultParams = {};
	this.highlightEnabled = false;
	this.parameterMap = {};
	this.emptyParameterState = null;

	this.views = {};
	this.currentView = 'default';
	this.searchQuery = null;

	// Events
	this.aggregate( { update: 'filterItemUpdate' } );
	this.connect( this, { filterItemUpdate: [ 'emit', 'itemUpdate' ] } );
};

/* Initialization */
OO.initClass( FiltersViewModel );
OO.mixinClass( FiltersViewModel, OO.EventEmitter );
OO.mixinClass( FiltersViewModel, OO.EmitterList );

/* Events */

/**
 * Filter list is initialized.
 *
 * @event initialize
 * @ignore
 */

/**
 * Model has been updated.
 *
 * @event update
 * @ignore
 */

/**
 * Filter item has changed.
 *
 * @event itemUpdate
 * @param {mw.rcfilters.dm.FilterItem} item Filter item updated
 * @ignore
 */

/**
 * Highlight feature has been toggled enabled or disabled.
 *
 * @event highlightChange
 * @param {boolean} Highlight feature is enabled
 * @ignore
 */

/* Methods */

/**
 * Re-assess the states of filter items based on the interactions between them
 *
 * @param {mw.rcfilters.dm.FilterItem} [item] Changed item. If not given, the
 *  method will go over the state of all items
 */
FiltersViewModel.prototype.reassessFilterInteractions = function ( item ) {
	const iterationItems = item !== undefined ? [ item ] : this.getItems();

	iterationItems.forEach( ( checkedItem ) => {
		const allCheckedItems = checkedItem.getSubset().concat( [ checkedItem.getName() ] ),
			groupModel = checkedItem.getGroupModel();

		// Check for subsets (included filters) plus the item itself:
		allCheckedItems.forEach( ( filterItemName ) => {
			const itemInSubset = this.getItemByName( filterItemName );

			itemInSubset.toggleIncluded(
				// If any of itemInSubset's supersets are selected, this item
				// is included
				itemInSubset.getSuperset().some( ( supersetName ) => ( this.getItemByName( supersetName ).isSelected() ) )
			);
		} );

		// Update coverage for the changed group
		if ( groupModel.isFullCoverage() ) {
			const allSelected = groupModel.areAllSelected();
			groupModel.getItems().forEach( ( filterItem ) => {
				filterItem.toggleFullyCovered( allSelected );
			} );
		}
	} );

	// Check for conflicts
	// In this case, we must go over all items, since
	// conflicts are bidirectional and depend not only on
	// individual items, but also on the selected states of
	// the groups they're in.
	this.getItems().forEach( ( filterItem ) => {
		let inConflict = false;
		const filterItemGroup = filterItem.getGroupModel();

		// For each item, see if that item is still conflicting
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.groups, ( groupName, groupModel ) => {
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
					filterItemGroup.findSelectedItems().every( ( otherGroupItem ) => groupModel.areAllSelectedInConflictWith( otherGroupItem ) )
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
FiltersViewModel.prototype.hasConflict = function () {
	return this.getItems().some( ( filterItem ) => filterItem.isSelected() && filterItem.isConflicted() );
};

/**
 * Get the first item with a current conflict
 *
 * @ignore
 * @return {mw.rcfilters.dm.FilterItem|undefined} Conflicted item or undefined when not found
 */
FiltersViewModel.prototype.getFirstConflictedItem = function () {
	const items = this.getItems();
	for ( let i = 0; i < items.length; i++ ) {
		const filterItem = items[ i ];
		if ( filterItem.isSelected() && filterItem.isConflicted() ) {
			return filterItem;
		}
	}
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
FiltersViewModel.prototype.initializeFilters = function ( filterGroups, views ) {
	const items = [],
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
		expandConflictDefinitions = ( obj ) => {
			const result = {};

			// eslint-disable-next-line no-jquery/no-each-util
			$.each( obj, ( key, conflicts ) => {
				const adjustedConflicts = {};

				conflicts.forEach( ( conflict ) => {
					let filter;

					if ( conflict.filter ) {
						const filterName = this.groups[ conflict.group ].getPrefixedName( conflict.filter );
						filter = this.getItemByName( filterName );

						// Rename
						adjustedConflicts[ filterName ] = Object.assign(
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
						this.groups[ conflict.group ].getItems().forEach( ( groupItem ) => {
							// Rebuild the conflict
							adjustedConflicts[ groupItem.getName() ] = Object.assign(
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
	filterGroups.forEach( ( data ) => {
		let i;
		// What's this information needs to be normalized
		data.whatsThis = {
			body: data.whatsThisBody,
			header: data.whatsThisHeader,
			linkText: data.whatsThisLinkText,
			url: data.whatsThisUrl
		};

		// Title is a msg-key
		// eslint-disable-next-line mediawiki/msg-doc
		data.title = data.title ? mw.msg( data.title ) : data.name;

		// Filters are given to us with msg-keys, we need
		// to translate those before we hand them off
		for ( i = 0; i < data.filters.length; i++ ) {
			// eslint-disable-next-line mediawiki/msg-doc
			data.filters[ i ].label = data.filters[ i ].label ? mw.msg( data.filters[ i ].label ) : data.filters[ i ].name;
			// eslint-disable-next-line mediawiki/msg-doc
			data.filters[ i ].description = data.filters[ i ].description ? mw.msg( data.filters[ i ].description ) : '';
		}
	} );

	// Collect views
	const allViews = $.extend( true, {
		default: {
			title: mw.msg( 'rcfilters-filterlist-title' ),
			groups: filterGroups
		}
	}, views );

	// Go over all views
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( allViews, ( viewName, viewData ) => {
		// Define the view
		this.views[ viewName ] = {
			name: viewData.name,
			title: viewData.title,
			trigger: viewData.trigger
		};

		// Go over groups
		viewData.groups.forEach( ( groupData ) => {
			const group = groupData.name;

			if ( !this.groups[ group ] ) {
				this.groups[ group ] = new FilterGroup(
					group,
					$.extend( true, {}, groupData, { view: viewName } )
				);
			}

			this.groups[ group ].initializeFilters( groupData.filters, groupData.default );
			items.push( ...this.groups[ group ].getItems() );

			// Prepare conflicts
			if ( groupData.conflicts ) {
				// Group conflicts
				groupConflictMap[ group ] = groupData.conflicts;
			}

			groupData.filters.forEach( ( itemData ) => {
				const filterItem = this.groups[ group ].getItemByParamName( itemData.name );
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
	const groupConflictResult = expandConflictDefinitions( groupConflictMap );
	const filterConflictResult = expandConflictDefinitions( filterConflictMap );

	// Set conflicts for groups
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( groupConflictResult, ( group, conflicts ) => {
		this.groups[ group ].setConflicts( conflicts );
	} );

	// Set conflicts for items
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( filterConflictResult, ( filterName, conflicts ) => {
		const filterItem = this.getItemByName( filterName );
		// set conflicts for items in the group
		filterItem.setConflicts( conflicts );
	} );

	// Create a map between known parameters and their models
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.groups, ( group, groupModel ) => {
		if (
			groupModel.getType() === 'send_unselected_if_any' ||
			groupModel.getType() === 'boolean' ||
			groupModel.getType() === 'any_value'
		) {
			// Individual filters
			groupModel.getItems().forEach( ( filterItem ) => {
				this.parameterMap[ filterItem.getParamName() ] = filterItem;
			} );
		} else if (
			groupModel.getType() === 'string_options' ||
			groupModel.getType() === 'single_option'
		) {
			// Group
			this.parameterMap[ groupModel.getName() ] = groupModel;
		}
	} );

	this.setSearch( '' );

	this.updateHighlightedState();

	// Finish initialization
	this.emit( 'initialize' );
};

/**
 * Update filter view model state based on a parameter object
 *
 * @param {Object} params Parameters object
 */
FiltersViewModel.prototype.updateStateFromParams = function ( params ) {
	// For arbitrary numeric single_option values make sure the values
	// are normalized to fit within the limits
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.getFilterGroups(), ( groupName, groupModel ) => {
		params[ groupName ] = groupModel.normalizeArbitraryValue( params[ groupName ] );
	} );

	// Update filter values
	const filtersValue = this.getFiltersFromParameters( params );
	for ( const filterName in filtersValue ) {
		this.getItemByName( filterName ).setValue( filtersValue[ filterName ] );
	}

	// Update highlight state
	this.getItemsSupportingHighlights().forEach( ( filterItem ) => {
		const color = params[ filterItem.getName() + '_color' ];
		if ( color ) {
			filterItem.setHighlightColor( color );
		} else {
			filterItem.clearHighlightColor();
		}
	} );
	this.updateHighlightedState();

	// Check all filter interactions
	this.reassessFilterInteractions();
};

/**
 * Get a representation of an empty (falsey) parameter state
 *
 * @return {Object} Empty parameter state
 */
FiltersViewModel.prototype.getEmptyParameterState = function () {
	if ( !this.emptyParameterState ) {
		this.emptyParameterState = $.extend(
			true,
			{},
			this.getParametersFromFilters( {} ),
			this.getEmptyHighlightParameters()
		);
	}
	return this.emptyParameterState;
};

/**
 * Get a representation of only the non-falsey parameters
 *
 * @param {Object} [parameters] A given parameter state to minimize. If not given the current
 *  state of the system will be used.
 * @return {Object} Empty parameter state
 */
FiltersViewModel.prototype.getMinimizedParamRepresentation = function ( parameters ) {
	const result = {};

	parameters = parameters ? $.extend( true, {}, parameters ) : this.getCurrentParameterState();

	// Params
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.getEmptyParameterState(), ( param, value ) => {
		if ( parameters[ param ] !== undefined && parameters[ param ] !== value ) {
			result[ param ] = parameters[ param ];
		}
	} );

	// Highlights
	for ( const param in this.getEmptyHighlightParameters() ) {
		if ( parameters[ param ] ) {
			// If a highlight parameter is not undefined and not null
			// add it to the result
			result[ param ] = parameters[ param ];
		}
	}

	return result;
};

/**
 * Get a representation of the full parameter list, including all base values
 *
 * @return {Object} Full parameter representation
 */
FiltersViewModel.prototype.getExpandedParamRepresentation = function () {
	return $.extend(
		true,
		{},
		this.getEmptyParameterState(),
		this.getCurrentParameterState()
	);
};

/**
 * Get a parameter representation of the current state of the model
 *
 * @param {boolean} [removeStickyParams] Remove sticky filters from final result
 * @return {Object} Parameter representation of the current state of the model
 */
FiltersViewModel.prototype.getCurrentParameterState = function ( removeStickyParams ) {
	let state = this.getMinimizedParamRepresentation( $.extend(
		true,
		{},
		this.getParametersFromFilters( this.getSelectedState() ),
		this.getHighlightParameters()
	) );

	if ( removeStickyParams ) {
		state = this.removeStickyParams( state );
	}

	return state;
};

/**
 * Delete sticky parameters from given object.
 *
 * @param {Object} paramState Parameter state
 * @return {Object} Parameter state without sticky parameters
 */
FiltersViewModel.prototype.removeStickyParams = function ( paramState ) {
	this.getStickyParams().forEach( ( paramName ) => {
		delete paramState[ paramName ];
	} );

	return paramState;
};

/**
 * Turn the highlight feature on or off
 */
FiltersViewModel.prototype.updateHighlightedState = function () {
	this.toggleHighlight( this.getHighlightedItems().length > 0 );
};

/**
 * Get the object that defines groups by their name.
 *
 * @return {Object} Filter groups
 */
FiltersViewModel.prototype.getFilterGroups = function () {
	return this.groups;
};

/**
 * Get the object that defines groups that match a certain view by their name.
 *
 * @param {string} [view] Requested view. If not given, uses current view
 * @return {Object} Filter groups matching a display group
 */
FiltersViewModel.prototype.getFilterGroupsByView = function ( view ) {
	const result = {};

	view = view || this.getCurrentView();

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.groups, ( groupName, groupModel ) => {
		if ( groupModel.getView() === view ) {
			result[ groupName ] = groupModel;
		}
	} );

	return result;
};

/**
 * Get an array of filters matching the given display group.
 *
 * @ignore
 * @param {string} [view] Requested view. If not given, uses current view
 * @return {mw.rcfilters.dm.FilterItem} Filter items matching the group
 */
FiltersViewModel.prototype.getFiltersByView = function ( view ) {
	const result = [];

	view = view || this.getCurrentView();

	const groups = this.getFilterGroupsByView( view );

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( groups, ( groupName, groupModel ) => {
		result.push( ...groupModel.getItems() );
	} );

	return result;
};

/**
 * Get the trigger for the requested view.
 *
 * @param {string} view View name
 * @return {string} View trigger, if exists
 */
FiltersViewModel.prototype.getViewTrigger = function ( view ) {
	return ( this.views[ view ] && this.views[ view ].trigger ) || '';
};

/**
 * Get the value of a specific parameter
 *
 * @param {string} name Parameter name
 * @return {number|string} Parameter value
 */
FiltersViewModel.prototype.getParamValue = function ( name ) {
	return this.parameters[ name ];
};

/**
 * Get the current selected state of the filters
 *
 * @param {boolean} [onlySelected] return an object containing only the filters with a value
 * @return {Object} Filters selected state
 */
FiltersViewModel.prototype.getSelectedState = function ( onlySelected ) {
	const items = this.getItems(),
		result = {};

	for ( let i = 0; i < items.length; i++ ) {
		if ( !onlySelected || items[ i ].getValue() ) {
			result[ items[ i ].getName() ] = items[ i ].getValue();
		}
	}

	return result;
};

/**
 * Get the current full state of the filters
 *
 * @return {Object} Filters full state
 */
FiltersViewModel.prototype.getFullState = function () {
	const items = this.getItems(),
		result = {};

	for ( let i = 0; i < items.length; i++ ) {
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
FiltersViewModel.prototype.getDefaultParams = function () {
	const result = {};

	// Get default filter state
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.groups, ( name, model ) => {
		if ( !model.isSticky() ) {
			$.extend( true, result, model.getDefaultParams() );
		}
	} );

	return result;
};

/**
 * Get a parameter representation of all sticky parameters
 *
 * @return {Object} Sticky parameter values
 */
FiltersViewModel.prototype.getStickyParams = function () {
	let result = [];

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.groups, ( name, model ) => {
		if ( model.isSticky() ) {
			if ( model.isPerGroupRequestParameter() ) {
				result.push( name );
			} else {
				// Each filter is its own param
				result = result.concat( model.getItems().map( ( filterItem ) => filterItem.getParamName() ) );
			}
		}
	} );

	return result;
};

/**
 * Get a parameter representation of all sticky parameters
 *
 * @return {Object} Sticky parameter values
 */
FiltersViewModel.prototype.getStickyParamsValues = function () {
	const result = {};

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.groups, ( name, model ) => {
		if ( model.isSticky() ) {
			$.extend( true, result, model.getParamRepresentation() );
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
FiltersViewModel.prototype.getParametersFromFilters = function ( filterDefinition ) {
	let groupItemDefinition;
	const result = {},
		groupItems = this.getFilterGroups();

	if ( filterDefinition ) {
		groupItemDefinition = {};
		// Filter definition is "flat", but in effect
		// each group needs to tell us its result based
		// on the values in it. We need to split this list
		// back into groupings so we can "feed" it to the
		// loop below, and we need to expand it so it includes
		// all filters (set to false)
		this.getItems().forEach( ( filterItem ) => {
			groupItemDefinition[ filterItem.getGroupName() ] = groupItemDefinition[ filterItem.getGroupName() ] || {};
			groupItemDefinition[ filterItem.getGroupName() ][ filterItem.getName() ] = filterItem.coerceValue( filterDefinition[ filterItem.getName() ] );
		} );
	}

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( groupItems, ( group, model ) => {
		Object.assign(
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
FiltersViewModel.prototype.getFiltersFromParameters = function ( params ) {
	const groupMap = {};
	let result = {};

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
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( params, ( paramName, paramValue ) => {
		const itemOrGroup = this.parameterMap[ paramName ];

		if ( itemOrGroup ) {
			const groupName = itemOrGroup instanceof FilterItem ?
				itemOrGroup.getGroupName() : itemOrGroup.getName();

			groupMap[ groupName ] = groupMap[ groupName ] || {};
			groupMap[ groupName ][ paramName ] = paramValue;
		}
	} );

	// Go over all groups, so we make sure we get the complete output
	// even if the parameters don't include a certain group
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.groups, ( groupName, groupModel ) => {
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
FiltersViewModel.prototype.getHighlightParameters = function () {
	const highlightEnabled = this.isHighlightEnabled(),
		result = {};

	this.getItems().forEach( ( filterItem ) => {
		if ( filterItem.isHighlightSupported() ) {
			result[ filterItem.getName() + '_color' ] = highlightEnabled && filterItem.isHighlighted() ?
				filterItem.getHighlightColor() :
				null;
		}
	} );

	return result;
};

/**
 * Get an object representing the complete empty state of highlights
 *
 * @return {Object} Object containing all the highlight parameters set to their negative value
 */
FiltersViewModel.prototype.getEmptyHighlightParameters = function () {
	const result = {};

	this.getItems().forEach( ( filterItem ) => {
		if ( filterItem.isHighlightSupported() ) {
			result[ filterItem.getName() + '_color' ] = null;
		}
	} );

	return result;
};

/**
 * Get an array of currently applied highlight colors
 *
 * @return {string[]} Currently applied highlight colors
 */
FiltersViewModel.prototype.getCurrentlyUsedHighlightColors = function () {
	const result = [];

	if ( this.isHighlightEnabled() ) {
		this.getHighlightedItems().forEach( ( filterItem ) => {
			const color = filterItem.getHighlightColor();

			if ( !result.includes( color ) ) {
				result.push( color );
			}
		} );
	}

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
FiltersViewModel.prototype.sanitizeStringOptionGroup = function ( groupName, valueArray ) {
	const validNames = this.getGroupFilters( groupName ).map( ( filterItem ) => filterItem.getParamName() );

	return utils.normalizeParamOptions( valueArray, validNames );
};

/**
 * Check whether no visible filter is selected.
 *
 * Filter groups that are hidden or sticky are not shown in the
 * active filters area and therefore not included in this check.
 *
 * @return {boolean} No visible filter is selected
 */
FiltersViewModel.prototype.areVisibleFiltersEmpty = function () {
	// Check if there are either any selected items or any items
	// that have highlight enabled
	return !this.getItems().some( ( filterItem ) => {
		const visible = !filterItem.getGroupModel().isSticky() && !filterItem.getGroupModel().isHidden(),
			active = ( filterItem.isSelected() || filterItem.isHighlighted() );
		return visible && active;
	} );
};

/**
 * Check whether the namespace invert state is a valid one. A valid invert state is one
 * where there are actual namespaces selected.
 *
 * This is done to compare states to previous ones that may have had the invert model
 * selected but effectively had no namespaces, so are not effectively different than
 * ones where invert is not selected.
 *
 * @return {boolean} Invert is effectively selected
 */
FiltersViewModel.prototype.areNamespacesEffectivelyInverted = function () {
	return this.getNamespacesInvertModel().isSelected() &&
		this.findSelectedItems().some( ( itemModel ) => itemModel.getGroupModel().getName() === 'namespace' );
};

/**
 * Check whether the tag invert state is a valid one. A valid invert state is one
 * where there are actual tags selected.
 *
 * This is done to compare states to previous ones that may have had the invert model
 * selected but effectively had no tags, so are not effectively different than
 * ones where invert is not selected.
 *
 * @return {boolean} Invert is effectively selected
 */
FiltersViewModel.prototype.areTagsEffectivelyInverted = function () {
	return this.getTagsInvertModel().isSelected() &&
		this.findSelectedItems().some( ( itemModel ) => itemModel.getGroupModel().getName() === 'tagfilter' );
};

/**
 * Get the item that matches the given name
 *
 * @ignore
 * @param {string} name Filter name
 * @return {mw.rcfilters.dm.FilterItem} Filter item
 */
FiltersViewModel.prototype.getItemByName = function ( name ) {
	return this.getItems().filter( ( item ) => name === item.getName() )[ 0 ];
};

/**
 * Set all filters to false or empty/all
 * This is equivalent to display all.
 */
FiltersViewModel.prototype.emptyAllFilters = function () {
	this.getItems().forEach( ( filterItem ) => {
		if ( !filterItem.getGroupModel().isSticky() ) {
			this.toggleFilterSelected( filterItem.getName(), false );
		}
	} );
};

/**
 * Toggle selected state of one item
 *
 * @param {string} name Name of the filter item
 * @param {boolean} [isSelected] Filter selected state
 */
FiltersViewModel.prototype.toggleFilterSelected = function ( name, isSelected ) {
	const item = this.getItemByName( name );

	if ( item ) {
		item.toggleSelected( isSelected );
	}
};

/**
 * Toggle selected state of items by their names
 *
 * @param {Object} filterDef Filter definitions
 */
FiltersViewModel.prototype.toggleFiltersSelected = function ( filterDef ) {
	for ( const name in filterDef ) {
		this.toggleFilterSelected( name, filterDef[ name ] );
	}
};

/**
 * Get a group model from its name
 *
 * @ignore
 * @param {string} groupName Group name
 * @return {mw.rcfilters.dm.FilterGroup} Group model
 */
FiltersViewModel.prototype.getGroup = function ( groupName ) {
	return this.groups[ groupName ];
};

/**
 * Get all filters within a specified group by its name
 *
 * @ignore
 * @param {string} groupName Group name
 * @return {mw.rcfilters.dm.FilterItem[]} Filters belonging to this group
 */
FiltersViewModel.prototype.getGroupFilters = function ( groupName ) {
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
FiltersViewModel.prototype.findMatches = function ( query, returnFlat ) {
	const result = {},
		flatResult = [],
		view = this.getViewByTrigger( query.slice( 0, 1 ) ),
		items = this.getFiltersByView( view );

	// Normalize so we can search strings regardless of case and view
	query = query.trim().toLowerCase();
	if ( view !== 'default' ) {
		query = query.slice( 1 );
	}
	// Trim again to also intercept cases where the spaces were after the trigger
	// eg: '#   str'
	query = query.trim();

	// Check if the search if actually empty; this can be a problem when
	// we use prefixes to denote different views
	const searchIsEmpty = query.length === 0;

	// item label starting with the query string
	for ( let i = 0; i < items.length; i++ ) {
		if (
			searchIsEmpty ||
			items[ i ].getLabel().toLowerCase().startsWith( query ) ||
			(
				// For tags, we want the parameter name to be included in the search
				view === 'tags' &&
				items[ i ].getParamName().toLowerCase().includes( query )
			)
		) {
			result[ items[ i ].getGroupName() ] = result[ items[ i ].getGroupName() ] || [];
			result[ items[ i ].getGroupName() ].push( items[ i ] );
			flatResult.push( items[ i ] );
		}
	}

	if ( $.isEmptyObject( result ) ) {
		// item containing the query string in their label, description, or group title
		for ( let i = 0; i < items.length; i++ ) {
			const groupTitle = items[ i ].getGroupModel().getTitle();
			if (
				searchIsEmpty ||
				items[ i ].getLabel().toLowerCase().includes( query ) ||
				items[ i ].getDescription().toLowerCase().includes( query ) ||
				groupTitle.toLowerCase().includes( query ) ||
				(
					// For tags, we want the parameter name to be included in the search
					view === 'tags' &&
					items[ i ].getParamName().toLowerCase().includes( query )
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
 * @ignore
 * @return {mw.rcfilters.dm.FilterItem[]} Highlighted items
 */
FiltersViewModel.prototype.getHighlightedItems = function () {
	return this.getItems().filter( ( filterItem ) => filterItem.isHighlightSupported() &&
			filterItem.getHighlightColor() );
};

/**
 * Get items that allow highlights even if they're not currently highlighted
 *
 * @ignore
 * @return {mw.rcfilters.dm.FilterItem[]} Items supporting highlights
 */
FiltersViewModel.prototype.getItemsSupportingHighlights = function () {
	return this.getItems().filter( ( filterItem ) => filterItem.isHighlightSupported() );
};

/**
 * Get all selected items
 *
 * @ignore
 * @return {mw.rcfilters.dm.FilterItem[]} Selected items
 */
FiltersViewModel.prototype.findSelectedItems = function () {
	let allSelected = [];

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.getFilterGroups(), ( groupName, groupModel ) => {
		allSelected = allSelected.concat( groupModel.findSelectedItems() );
	} );

	return allSelected;
};

/**
 * Get the current view
 *
 * @return {string} Current view
 */
FiltersViewModel.prototype.getCurrentView = function () {
	return this.currentView;
};

/**
 * Get the label for the current view
 *
 * @param {string} viewName View name
 * @return {string} Label for the current view
 */
FiltersViewModel.prototype.getViewTitle = function ( viewName ) {
	viewName = viewName || this.getCurrentView();

	return this.views[ viewName ] && this.views[ viewName ].title;
};

/**
 * Get the view that fits the given trigger
 *
 * @param {string} trigger Trigger
 * @return {string} Name of view
 */
FiltersViewModel.prototype.getViewByTrigger = function ( trigger ) {
	let result = 'default';

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( this.views, ( name, data ) => {
		if ( data.trigger === trigger ) {
			result = name;
		}
	} );

	return result;
};

/**
 * Return a version of the given string that is without any
 * view triggers.
 *
 * @param {string} str Given string
 * @return {string} Result
 */
FiltersViewModel.prototype.removeViewTriggers = function ( str ) {
	if ( this.getViewFromString( str ) !== 'default' ) {
		str = str.slice( 1 );
	}

	return str;
};

/**
 * Get the view from the given string by a trigger, if it exists
 *
 * @param {string} str Given string
 * @return {string} View name
 */
FiltersViewModel.prototype.getViewFromString = function ( str ) {
	return this.getViewByTrigger( str.slice( 0, 1 ) );
};

/**
 * Set the current search for the system.
 * This also dictates what items and groups are visible according
 * to the search in #findMatches
 *
 * @param {string} searchQuery Search query, including triggers
 * @fires searchChange
 */
FiltersViewModel.prototype.setSearch = function ( searchQuery ) {
	let visibleGroups, visibleGroupNames;

	if ( this.searchQuery !== searchQuery ) {
		// Check if the view changed
		this.switchView( this.getViewFromString( searchQuery ) );

		visibleGroups = this.findMatches( searchQuery );
		visibleGroupNames = Object.keys( visibleGroups );

		// Update visibility of items and groups
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.getFilterGroups(), ( groupName, groupModel ) => {
			// Check if the group is visible at all
			groupModel.toggleVisible( visibleGroupNames.includes( groupName ) );
			groupModel.setVisibleItems( visibleGroups[ groupName ] || [] );
		} );

		this.searchQuery = searchQuery;
		this.emit( 'searchChange', this.searchQuery );
	}
};

/**
 * Get the current search
 *
 * @return {string} Current search query
 */
FiltersViewModel.prototype.getSearch = function () {
	return this.searchQuery;
};

/**
 * Switch the current view
 *
 * @private
 * @param {string} view View name
 */
FiltersViewModel.prototype.switchView = function ( view ) {
	if ( this.views[ view ] && this.currentView !== view ) {
		this.currentView = view;
	}
};

/**
 * Toggle the highlight feature on and off.
 * Propagate the change to filter items.
 *
 * @param {boolean} enable Highlight should be enabled
 * @fires highlightChange
 */
FiltersViewModel.prototype.toggleHighlight = function ( enable ) {
	enable = enable === undefined ? !this.highlightEnabled : enable;

	if ( this.highlightEnabled !== enable ) {
		this.highlightEnabled = enable;
		this.emit( 'highlightChange', this.highlightEnabled );
	}
};

/**
 * Check if the highlight feature is enabled
 *
 * @return {boolean}
 */
FiltersViewModel.prototype.isHighlightEnabled = function () {
	return !!this.highlightEnabled;
};

/**
 * Toggle the inverted tags property on and off.
 * Propagate the change to tag filter items.
 *
 * @param {boolean} enable Inverted property is enabled
 */
FiltersViewModel.prototype.toggleInvertedTags = function ( enable ) {
	this.toggleFilterSelected( this.getTagsInvertModel().getName(), enable );
};

/**
 * Toggle the inverted namespaces property on and off.
 * Propagate the change to namespace filter items.
 *
 * @param {boolean} enable Inverted property is enabled
 */
FiltersViewModel.prototype.toggleInvertedNamespaces = function ( enable ) {
	this.toggleFilterSelected( this.getNamespacesInvertModel().getName(), enable );
};

/**
 * Get the model object that represents the 'invert' filter
 *
 * @ignore
 * @param {string} view
 * @return {mw.rcfilters.dm.FilterItem|null}
 */
FiltersViewModel.prototype.getInvertModel = function ( view ) {
	if ( view === 'namespaces' ) {
		return this.getNamespacesInvertModel();
	}
	if ( view === 'tags' ) {
		return this.getTagsInvertModel();
	}

	return null;
};

/**
 * Get the model object that represents the 'invert' filter
 *
 * @ignore
 * @return {mw.rcfilters.dm.FilterItem}
 */
FiltersViewModel.prototype.getNamespacesInvertModel = function () {
	return this.getGroup( 'invertGroup' ).getItemByParamName( 'invert' );
};

/**
 * Get the model object that represents the 'invert' filter
 *
 * @ignore
 * @return {mw.rcfilters.dm.FilterItem}
 */
FiltersViewModel.prototype.getTagsInvertModel = function () {
	return this.getGroup( 'invertTagsGroup' ).getItemByParamName( 'inverttags' );
};

/**
 * Set highlight color for a specific filter item
 *
 * @param {string} filterName Name of the filter item
 * @param {string} color Selected color
 */
FiltersViewModel.prototype.setHighlightColor = function ( filterName, color ) {
	this.getItemByName( filterName ).setHighlightColor( color );
};

/**
 * Clear highlight for a specific filter item
 *
 * @param {string} filterName Name of the filter item
 */
FiltersViewModel.prototype.clearHighlightColor = function ( filterName ) {
	this.getItemByName( filterName ).clearHighlightColor();
};

module.exports = FiltersViewModel;
