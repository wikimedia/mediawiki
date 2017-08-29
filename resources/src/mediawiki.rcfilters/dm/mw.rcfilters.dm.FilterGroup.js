( function ( mw ) {
	/**
	 * View model for a filter group
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 * @param {string} name Group name
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [type='send_unselected_if_any'] Group type
	 * @cfg {string} [view='default'] Name of the display group this group
	 *  is a part of.
	 * @cfg {boolean} [isSticky] This group is using a 'sticky' default; meaning
	 *  that every time a value is changed, it becomes the new default
	 * @cfg {boolean} [excludedFromSavedQueries] A specific requirement to exclude
	 *  this filter from saved queries. This is always true if the filter is 'sticky'
	 *  but can be used for non-sticky filters as an additional requirement. Similarly
	 *  to 'sticky' it works for the entire group as a whole.
	 * @cfg {string} [title] Group title
	 * @cfg {boolean} [hidden] This group is hidden from the regular menu views
	 * @cfg {boolean} [allowArbitrary] Allows for an arbitrary value to be added to the
	 *  group from the URL, even if it wasn't initially set up.
	 * @cfg {number} [range] An object defining minimum and maximum values for numeric
	 *  groups. { min: x, max: y }
	 * @cfg {number} [minValue] Minimum value for numeric groups
	 * @cfg {string} [separator='|'] Value separator for 'string_options' groups
	 * @cfg {boolean} [active] Group is active
	 * @cfg {boolean} [fullCoverage] This filters in this group collectively cover all results
	 * @cfg {Object} [conflicts] Defines the conflicts for this filter group
	 * @cfg {string|Object} [labelPrefixKey] An i18n key defining the prefix label for this
	 *  group. If the prefix has 'invert' state, the parameter is expected to be an object
	 *  with 'default' and 'inverted' as keys.
	 * @cfg {Object} [whatsThis] Defines the messages that should appear for the 'what's this' popup
	 * @cfg {string} [whatsThis.header] The header of the whatsThis popup message
	 * @cfg {string} [whatsThis.body] The body of the whatsThis popup message
	 * @cfg {string} [whatsThis.url] The url for the link in the whatsThis popup message
	 * @cfg {string} [whatsThis.linkMessage] The text for the link in the whatsThis popup message
	 */
	mw.rcfilters.dm.FilterGroup = function MwRcfiltersDmFilterGroup( name, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.name = name;
		this.type = config.type || 'send_unselected_if_any';
		this.view = config.view || 'default';
		this.sticky = !!config.isSticky;
		this.excludedFromSavedQueries = this.sticky || !!config.excludedFromSavedQueries;
		this.title = config.title || name;
		this.hidden = !!config.hidden;
		this.allowArbitrary = !!config.allowArbitrary;
		this.numericRange = config.range;
		this.separator = config.separator || '|';
		this.labelPrefixKey = config.labelPrefixKey;

		this.currSelected = null;
		this.active = !!config.active;
		this.fullCoverage = !!config.fullCoverage;

		this.whatsThis = config.whatsThis || {};

		this.conflicts = config.conflicts || {};
		this.defaultParams = {};
		this.defaultFilters = {};

		this.aggregate( { update: 'filterItemUpdate' } );
		this.connect( this, { filterItemUpdate: 'onFilterItemUpdate' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.FilterGroup );
	OO.mixinClass( mw.rcfilters.dm.FilterGroup, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.FilterGroup, OO.EmitterList );

	/* Events */

	/**
	 * @event update
	 *
	 * Group state has been updated
	 */

	/* Methods */

	/**
	 * Initialize the group and create its filter items
	 *
	 * @param {Object} filterDefinition Filter definition for this group
	 * @param {string|Object} [groupDefault] Definition of the group default
	 */
	mw.rcfilters.dm.FilterGroup.prototype.initializeFilters = function ( filterDefinition, groupDefault ) {
		var defaultParam,
			supersetMap = {},
			model = this,
			items = [];

		filterDefinition.forEach( function ( filter ) {
			// Instantiate an item
			var subsetNames = [],
				filterItem = new mw.rcfilters.dm.FilterItem( filter.name, model, {
					group: model.getName(),
					label: filter.label || filter.name,
					description: filter.description || '',
					labelPrefixKey: model.labelPrefixKey,
					cssClass: filter.cssClass,
					identifiers: filter.identifiers
				} );

			if ( filter.subset ) {
				filter.subset = filter.subset.map( function ( el ) {
					return el.filter;
				} );

				subsetNames = [];

				filter.subset.forEach( function ( subsetFilterName ) { // eslint-disable-line no-loop-func
					// Subsets (unlike conflicts) are always inside the same group
					// We can re-map the names of the filters we are getting from
					// the subsets with the group prefix
					var subsetName = model.getPrefixedName( subsetFilterName );
					// For convenience, we should store each filter's "supersets" -- these are
					// the filters that have that item in their subset list. This will just
					// make it easier to go through whether the item has any other items
					// that affect it (and are selected) at any given time
					supersetMap[ subsetName ] = supersetMap[ subsetName ] || [];
					mw.rcfilters.utils.addArrayElementsUnique(
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

			items.push( filterItem );

			// Store default parameter state; in this case, default is defined per filter
			if (
				model.getType() === 'send_unselected_if_any' ||
				model.getType() === 'boolean'
			) {
				// Store the default parameter state
				// For this group type, parameter values are direct
				// We need to convert from a boolean to a string ('1' and '0')
				model.defaultParams[ filter.name ] = String( Number( filter.default || 0 ) );
			}
		} );

		// Add items
		this.addItems( items );

		// Now that we have all items, we can apply the superset map
		this.getItems().forEach( function ( filterItem ) {
			filterItem.setSuperset( supersetMap[ filterItem.getName() ] );
		} );

		// Store default parameter state; in this case, default is defined per the
		// entire group, given by groupDefault method parameter
		if ( this.getType() === 'string_options' ) {
			// Store the default parameter group state
			// For this group, the parameter is group name and value is the names
			// of selected items
			this.defaultParams[ this.getName() ] = mw.rcfilters.utils.normalizeParamOptions(
				// Current values
				groupDefault ?
					groupDefault.split( this.getSeparator() ) :
					[],
				// Legal values
				this.getItems().map( function ( item ) {
					return item.getParamName();
				} )
			).join( this.getSeparator() );
		} else if ( this.getType() === 'single_option' ) {
			defaultParam = groupDefault !== undefined ?
				groupDefault : this.getItems()[ 0 ].getParamName();

			// For this group, the parameter is the group name,
			// and a single item can be selected: default or first item
			this.defaultParams[ this.getName() ] = defaultParam;
		}

		// Store default filter state based on default params
		this.defaultFilters = this.getFilterRepresentation( this.getDefaultParams() );

		// Check for filters that should be initially selected by their default value
		if ( this.isSticky() ) {
			$.each( this.defaultFilters, function ( filterName, filterValue ) {
				model.getItemByName( filterName ).toggleSelected( filterValue );
			} );
		}

		// Verify that single_option group has at least one item selected
		if (
			this.getType() === 'single_option' &&
			this.getSelectedItems().length === 0
		) {
			defaultParam = groupDefault !== undefined ?
				groupDefault : this.getItems()[ 0 ].getParamName();

			// Single option means there must be a single option
			// selected, so we have to either select the default
			// or select the first option
			this.selectItemByParamName( defaultParam );
		}
	};

	/**
	 * Respond to filterItem update event
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Updated filter item
	 * @fires update
	 */
	mw.rcfilters.dm.FilterGroup.prototype.onFilterItemUpdate = function ( item ) {
		// Update state
		var changed = false,
			active = this.areAnySelected(),
			model = this;

		if ( this.getType() === 'single_option' ) {
			// This group must have one item selected always
			// and must never have more than one item selected at a time
			if ( this.getSelectedItems().length === 0 ) {
				// Nothing is selected anymore
				// Select the default or the first item
				this.currSelected = this.getItemByParamName( this.defaultParams[ this.getName() ] ) ||
					this.getItems()[ 0 ];
				this.currSelected.toggleSelected( true );
				changed = true;
			} else if ( this.getSelectedItems().length > 1 ) {
				// There is more than one item selected
				// This should only happen if the item given
				// is the one that is selected, so unselect
				// all items that is not it
				this.getSelectedItems().forEach( function ( itemModel ) {
					// Note that in case the given item is actually
					// not selected, this loop will end up unselecting
					// all items, which would trigger the case above
					// when the last item is unselected anyways
					var selected = itemModel.getName() === item.getName() &&
						item.isSelected();

					itemModel.toggleSelected( selected );
					if ( selected ) {
						model.currSelected = itemModel;
					}
				} );
				changed = true;
			}
		}

		if (
			changed ||
			this.active !== active ||
			this.currSelected !== item
		) {
			if ( this.isSticky() ) {
				// If this group is sticky, then change the default according to the
				// current selection.
				this.defaultParams = this.getParamRepresentation( this.getSelectedState() );
			}

			this.active = active;
			this.currSelected = item;

			this.emit( 'update' );
		}
	};

	/**
	 * Get group active state
	 *
	 * @return {boolean} Active state
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isActive = function () {
		return this.active;
	};

	/**
	 * Get group hidden state
	 *
	 * @return {boolean} Hidden state
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isHidden = function () {
		return this.hidden;
	};

	/**
	 * Get group allow arbitrary state
	 *
	 * @return {boolean} Group allows an arbitrary value from the URL
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isAllowArbitrary = function () {
		return this.allowArbitrary;
	};

	/**
	 * Get group maximum value for numeric groups
	 *
	 * @return {number|null} Group max value
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getMaxValue = function () {
		return this.numericRange && this.numericRange.max !== undefined ?
			this.numericRange.max : null;
	};

	/**
	 * Get group minimum value for numeric groups
	 *
	 * @return {number|null} Group max value
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getMinValue = function () {
		return this.numericRange && this.numericRange.min !== undefined ?
			this.numericRange.min : null;
	};

	/**
	 * Get group name
	 *
	 * @return {string} Group name
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Get the default param state of this group
	 *
	 * @return {Object} Default param state
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getDefaultParams = function () {
		return this.defaultParams;
	};

	/**
	 * Get the default filter state of this group
	 *
	 * @return {Object} Default filter state
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getDefaultFilters = function () {
		return this.defaultFilters;
	};

	/**
	 * This is for a single_option and string_options group types
	 * it returns the value of the default
	 *
	 * @return {string} Value of the default
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getDefaulParamValue = function () {
		return this.defaultParams[ this.getName() ];
	};
	/**
	 * Get the messags defining the 'whats this' popup for this group
	 *
	 * @return {Object} What's this messages
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getWhatsThis = function () {
		return this.whatsThis;
	};

	/**
	 * Check whether this group has a 'what's this' message
	 *
	 * @return {boolean} This group has a what's this message
	 */
	mw.rcfilters.dm.FilterGroup.prototype.hasWhatsThis = function () {
		return !!this.whatsThis.body;
	};

	/**
	 * Get the conflicts associated with the entire group.
	 * Conflict object is set up by filter name keys and conflict
	 * definition. For example:
	 * [
	 * 		{
	 * 			filterName: {
	 * 				filter: filterName,
	 * 				group: group1
	 * 			}
	 * 		},
	 * 		{
	 * 			filterName2: {
	 * 				filter: filterName2,
	 * 				group: group2
	 * 			}
	 * 		}
	 * ]
	 * @return {Object} Conflict definition
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getConflicts = function () {
		return this.conflicts;
	};

	/**
	 * Set conflicts for this group. See #getConflicts for the expected
	 * structure of the definition.
	 *
	 * @param {Object} conflicts Conflicts for this group
	 */
	mw.rcfilters.dm.FilterGroup.prototype.setConflicts = function ( conflicts ) {
		this.conflicts = conflicts;
	};

	/**
	 * Set conflicts for each filter item in the group based on the
	 * given conflict map
	 *
	 * @param {Object} conflicts Object representing the conflict map,
	 *  keyed by the item name, where its value is an object for all its conflicts
	 */
	mw.rcfilters.dm.FilterGroup.prototype.setFilterConflicts = function ( conflicts ) {
		this.getItems().forEach( function ( filterItem ) {
			if ( conflicts[ filterItem.getName() ] ) {
				filterItem.setConflicts( conflicts[ filterItem.getName() ] );
			}
		} );
	};

	/**
	 * Check whether this item has a potential conflict with the given item
	 *
	 * This checks whether the given item is in the list of conflicts of
	 * the current item, but makes no judgment about whether the conflict
	 * is currently at play (either one of the items may not be selected)
	 *
	 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item
	 * @return {boolean} This item has a conflict with the given item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.existsInConflicts = function ( filterItem ) {
		return Object.prototype.hasOwnProperty.call( this.getConflicts(), filterItem.getName() );
	};

	/**
	 * Check whether there are any items selected
	 *
	 * @return {boolean} Any items in the group are selected
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAnySelected = function () {
		return this.getItems().some( function ( filterItem ) {
			return filterItem.isSelected();
		} );
	};

	/**
	 * Check whether all items selected
	 *
	 * @return {boolean} All items are selected
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAllSelected = function () {
		var selected = [],
			unselected = [];

		this.getItems().forEach( function ( filterItem ) {
			if ( filterItem.isSelected() ) {
				selected.push( filterItem );
			} else {
				unselected.push( filterItem );
			}
		} );

		if ( unselected.length === 0 ) {
			return true;
		}

		// check if every unselected is a subset of a selected
		return unselected.every( function ( unselectedFilterItem ) {
			return selected.some( function ( selectedFilterItem ) {
				return selectedFilterItem.existsInSubset( unselectedFilterItem.getName() );
			} );
		} );
	};

	/**
	 * Get all selected items in this group
	 *
	 * @param {mw.rcfilters.dm.FilterItem} [excludeItem] Item to exclude from the list
	 * @return {mw.rcfilters.dm.FilterItem[]} Selected items
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getSelectedItems = function ( excludeItem ) {
		var excludeName = ( excludeItem && excludeItem.getName() ) || '';

		return this.getItems().filter( function ( item ) {
			return item.getName() !== excludeName && item.isSelected();
		} );
	};

	/**
	 * Check whether all selected items are in conflict with the given item
	 *
	 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item to test
	 * @return {boolean} All selected items are in conflict with this item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAllSelectedInConflictWith = function ( filterItem ) {
		var selectedItems = this.getSelectedItems( filterItem );

		return selectedItems.length > 0 &&
			(
				// The group as a whole is in conflict with this item
				this.existsInConflicts( filterItem ) ||
				// All selected items are in conflict individually
				selectedItems.every( function ( selectedFilter ) {
					return selectedFilter.existsInConflicts( filterItem );
				} )
			);
	};

	/**
	 * Check whether any of the selected items are in conflict with the given item
	 *
	 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item to test
	 * @return {boolean} Any of the selected items are in conflict with this item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.areAnySelectedInConflictWith = function ( filterItem ) {
		var selectedItems = this.getSelectedItems( filterItem );

		return selectedItems.length > 0 && (
			// The group as a whole is in conflict with this item
			this.existsInConflicts( filterItem ) ||
			// Any selected items are in conflict individually
			selectedItems.some( function ( selectedFilter ) {
				return selectedFilter.existsInConflicts( filterItem );
			} )
		);
	};

	/**
	 * Get the parameter representation from this group
	 *
	 * @param {Object} [filterRepresentation] An object defining the state
	 *  of the filters in this group, keyed by their name and current selected
	 *  state value.
	 * @return {Object} Parameter representation
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getParamRepresentation = function ( filterRepresentation ) {
		var values,
			areAnySelected = false,
			buildFromCurrentState = !filterRepresentation,
			defaultFilters = this.getDefaultFilters(),
			result = {},
			model = this,
			filterParamNames = {},
			getSelectedParameter = function ( filters ) {
				var item,
					selected = [];

				// Find if any are selected
				$.each( filters, function ( name, value ) {
					if ( value ) {
						selected.push( name );
					}
				} );

				item = model.getItemByName( selected[ 0 ] );
				return ( item && item.getParamName() ) || '';
			};

		filterRepresentation = filterRepresentation || {};

		// Create or complete the filterRepresentation definition
		this.getItems().forEach( function ( item ) {
			// Map filter names to their parameter names
			filterParamNames[ item.getName() ] = item.getParamName();

			if ( buildFromCurrentState ) {
				// This means we have not been given a filter representation
				// so we are building one based on current state
				filterRepresentation[ item.getName() ] = item.isSelected();
			} else if ( filterRepresentation[ item.getName() ] === undefined ) {
				// We are given a filter representation, but we have to make
				// sure that we fill in the missing filters if there are any
				// we will assume they are all falsey
				if ( model.isSticky() ) {
					filterRepresentation[ item.getName() ] = !!defaultFilters[ item.getName() ];
				} else {
					filterRepresentation[ item.getName() ] = false;
				}
			}

			if ( filterRepresentation[ item.getName() ] ) {
				areAnySelected = true;
			}
		} );

		// Build result
		if (
			this.getType() === 'send_unselected_if_any' ||
			this.getType() === 'boolean'
		) {
			// First, check if any of the items are selected at all.
			// If none is selected, we're treating it as if they are
			// all false

			// Go over the items and define the correct values
			$.each( filterRepresentation, function ( name, value ) {
				// We must store all parameter values as strings '0' or '1'
				if ( model.getType() === 'send_unselected_if_any' ) {
					result[ filterParamNames[ name ] ] = areAnySelected ?
						String( Number( !value ) ) :
						'0';
				} else if ( model.getType() === 'boolean' ) {
					// Representation is straight-forward and direct from
					// the parameter value to the filter state
					result[ filterParamNames[ name ] ] = String( Number( !!value ) );
				}
			} );
		} else if ( this.getType() === 'string_options' ) {
			values = [];

			$.each( filterRepresentation, function ( name, value ) {
				// Collect values
				if ( value ) {
					values.push( filterParamNames[ name ] );
				}
			} );

			result[ this.getName() ] = ( values.length === Object.keys( filterRepresentation ).length ) ?
				'all' : values.join( this.getSeparator() );
		} else if ( this.getType() === 'single_option' ) {
			result[ this.getName() ] = getSelectedParameter( filterRepresentation );
		}

		return result;
	};

	/**
	 * Get the filter representation this group would provide
	 * based on given parameter states.
	 *
	 * @param {Object} [paramRepresentation] An object defining a parameter
	 *  state to translate the filter state from. If not given, an object
	 *  representing all filters as falsey is returned; same as if the parameter
	 *  given were an empty object, or had some of the filters missing.
	 * @return {Object} Filter representation
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getFilterRepresentation = function ( paramRepresentation ) {
		var areAnySelected, paramValues, item, currentValue,
			oneWasSelected = false,
			defaultParams = this.getDefaultParams(),
			expandedParams = $.extend( true, {}, paramRepresentation ),
			model = this,
			paramToFilterMap = {},
			result = {};

		if ( this.isSticky() ) {
			// If the group is sticky, check if all parameters are represented
			// and for those that aren't represented, add them with their default
			// values
			paramRepresentation = $.extend( true, {}, this.getDefaultParams(), paramRepresentation );
		}

		paramRepresentation = paramRepresentation || {};
		if (
			this.getType() === 'send_unselected_if_any' ||
			this.getType() === 'boolean'
		) {
			// Go over param representation; map and check for selections
			this.getItems().forEach( function ( filterItem ) {
				var paramName = filterItem.getParamName();

				expandedParams[ paramName ] = paramRepresentation[ paramName ] || '0';
				paramToFilterMap[ paramName ] = filterItem;

				if ( Number( paramRepresentation[ filterItem.getParamName() ] ) ) {
					areAnySelected = true;
				}
			} );

			$.each( expandedParams, function ( paramName, paramValue ) {
				var filterItem = paramToFilterMap[ paramName ];

				if ( model.getType() === 'send_unselected_if_any' ) {
					// Flip the definition between the parameter
					// state and the filter state
					// This is what the 'toggleSelected' value of the filter is
					result[ filterItem.getName() ] = areAnySelected ?
						!Number( paramValue ) :
						// Otherwise, there are no selected items in the
						// group, which means the state is false
						false;
				} else if ( model.getType() === 'boolean' ) {
					// Straight-forward definition of state
					result[ filterItem.getName() ] = !!Number( paramRepresentation[ filterItem.getParamName() ] );
				}
			} );
		} else if ( this.getType() === 'string_options' ) {
			currentValue = paramRepresentation[ this.getName() ] || '';

			// Normalize the given parameter values
			paramValues = mw.rcfilters.utils.normalizeParamOptions(
				// Given
				currentValue.split(
					this.getSeparator()
				),
				// Allowed values
				this.getItems().map( function ( filterItem ) {
					return filterItem.getParamName();
				} )
			);
			// Translate the parameter values into a filter selection state
			this.getItems().forEach( function ( filterItem ) {
				// All true (either because all values are written or the term 'all' is written)
				// is the same as all filters set to true
				result[ filterItem.getName() ] = (
					// If it is the word 'all'
					paramValues.length === 1 && paramValues[ 0 ] === 'all' ||
					// All values are written
					paramValues.length === model.getItemCount()
				) ?
					true :
					// Otherwise, the filter is selected only if it appears in the parameter values
					paramValues.indexOf( filterItem.getParamName() ) > -1;
			} );
		} else if ( this.getType() === 'single_option' ) {
			// There is parameter that fits a single filter and if not, get the default
			this.getItems().forEach( function ( filterItem ) {
				var selected = filterItem.getParamName() === paramRepresentation[ model.getName() ];

				result[ filterItem.getName() ] = selected;
				oneWasSelected = oneWasSelected || selected;
			} );
		}

		// Go over result and make sure all filters are represented.
		// If any filters are missing, they will get a falsey value
		this.getItems().forEach( function ( filterItem ) {
			if ( result[ filterItem.getName() ] === undefined ) {
				result[ filterItem.getName() ] = false;
			}
		} );

		// Make sure that at least one option is selected in
		// single_option groups, no matter what path was taken
		// If none was selected by the given definition, then
		// we need to select the one in the base state -- either
		// the default given, or the first item
		if (
			this.getType() === 'single_option' &&
			!oneWasSelected
		) {
			item = this.getItems()[ 0 ];
			if ( defaultParams[ this.getName() ] ) {
				item = this.getItemByParamName( defaultParams[ this.getName() ] );
			}

			result[ item.getName() ] = true;
		}

		return result;
	};

	/**
	 * Get current selected state of all filter items in this group
	 *
	 * @return {Object} Selected state
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getSelectedState = function () {
		var state = {};

		this.getItems().forEach( function ( filterItem ) {
			state[ filterItem.getName() ] = filterItem.isSelected();
		} );

		return state;
	};

	/**
	 * Get item by its filter name
	 *
	 * @param {string} filterName Filter name
	 * @return {mw.rcfilters.dm.FilterItem} Filter item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getItemByName = function ( filterName ) {
		return this.getItems().filter( function ( item ) {
			return item.getName() === filterName;
		} )[ 0 ];
	};

	/**
	 * Select an item by its parameter name
	 *
	 * @param {string} paramName Filter parameter name
	 */
	mw.rcfilters.dm.FilterGroup.prototype.selectItemByParamName = function ( paramName ) {
		this.getItems().forEach( function ( item ) {
			item.toggleSelected( item.getParamName() === String( paramName ) );
		} );
	};

	/**
	 * Get item by its parameter name
	 *
	 * @param {string} paramName Parameter name
	 * @return {mw.rcfilters.dm.FilterItem} Filter item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getItemByParamName = function ( paramName ) {
		return this.getItems().filter( function ( item ) {
			return item.getParamName() === String( paramName );
		} )[ 0 ];
	};

	/**
	 * Get group type
	 *
	 * @return {string} Group type
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getType = function () {
		return this.type;
	};

	/**
	 * Get display group
	 *
	 * @return {string} Display group
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getView = function () {
		return this.view;
	};

	/**
	 * Get the prefix used for the filter names inside this group.
	 *
	 * @param {string} [name] Filter name to prefix
	 * @return {string} Group prefix
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getNamePrefix = function () {
		return this.getName() + '__';
	};

	/**
	 * Get a filter name with the prefix used for the filter names inside this group.
	 *
	 * @param {string} name Filter name to prefix
	 * @return {string} Group prefix
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getPrefixedName = function ( name ) {
		return this.getNamePrefix() + name;
	};

	/**
	 * Get group's title
	 *
	 * @return {string} Title
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getTitle = function () {
		return this.title;
	};

	/**
	 * Get group's values separator
	 *
	 * @return {string} Values separator
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getSeparator = function () {
		return this.separator;
	};

	/**
	 * Check whether the group is defined as full coverage
	 *
	 * @return {boolean} Group is full coverage
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isFullCoverage = function () {
		return this.fullCoverage;
	};

	/**
	 * Check whether the group is defined as sticky default
	 *
	 * @return {boolean} Group is sticky default
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isSticky = function () {
		return this.sticky;
	};

	/**
	 * Check whether the group value is excluded from saved queries
	 *
	 * @return {boolean} Group value is excluded from saved queries
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isExcludedFromSavedQueries = function () {
		return this.excludedFromSavedQueries;
	};
}( mediaWiki ) );
