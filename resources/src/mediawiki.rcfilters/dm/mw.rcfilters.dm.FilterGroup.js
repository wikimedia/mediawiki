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
	 * @cfg {string} [title] Group title
	 * @cfg {string} [separator='|'] Value separator for 'string_options' groups
	 * @cfg {boolean} [active] Group is active
	 * @cfg {boolean} [fullCoverage] This filters in this group collectively cover all results
	 * @cfg {Object} [conflicts] Defines the conflicts for this filter group
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
		this.title = config.title;
		this.separator = config.separator || '|';

		this.active = !!config.active;
		this.fullCoverage = !!config.fullCoverage;

		this.whatsThis = config.whatsThis || {};

		this.conflicts = config.conflicts || {};
		this.defaultParams = {};

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
		var supersetMap = {},
			model = this,
			items = [];

		filterDefinition.forEach( function ( filter ) {
			// Instantiate an item
			var subsetNames = [],
				filterItem = new mw.rcfilters.dm.FilterItem( filter.name, model, {
					group: model.getName(),
					label: mw.msg( filter.label ),
					description: mw.msg( filter.description ),
					cssClass: filter.cssClass
				} );

			filter.subset = filter.subset || [];
			filter.subset = filter.subset.map( function ( el ) {
				return el.filter;
			} );

			if ( filter.subset ) {
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
			if ( model.getType() === 'send_unselected_if_any' ) {
				// Store the default parameter state
				// For this group type, parameter values are direct
				// We need to convert from a boolean to a string ('1' and '0')
				model.defaultParams[ filter.name ] = String( Number( !!filter.default ) );
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
		}
	};

	/**
	 * Respond to filterItem update event
	 *
	 * @fires update
	 */
	mw.rcfilters.dm.FilterGroup.prototype.onFilterItemUpdate = function () {
		// Update state
		var active = this.areAnySelected();

		if ( this.active !== active ) {
			this.active = active;
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
			result = {},
			filterParamNames = {};

		filterRepresentation = filterRepresentation || {};

		// Create or complete the filterRepresentation definition
		this.getItems().forEach( function ( item ) {
			// Map filter names to their parameter names
			filterParamNames[ item.getName() ] = item.getParamName();

			if ( buildFromCurrentState ) {
				// This means we have not been given a filter representation
				// so we are building one based on current state
				filterRepresentation[ item.getName() ] = item.isSelected();
			} else if ( !filterRepresentation[ item.getName() ] ) {
				// We are given a filter representation, but we have to make
				// sure that we fill in the missing filters if there are any
				// we will assume they are all falsey
				filterRepresentation[ item.getName() ] = false;
			}

			if ( filterRepresentation[ item.getName() ] ) {
				areAnySelected = true;
			}
		} );

		// Build result
		if ( this.getType() === 'send_unselected_if_any' ) {
			// First, check if any of the items are selected at all.
			// If none is selected, we're treating it as if they are
			// all false

			// Go over the items and define the correct values
			$.each( filterRepresentation, function ( name, value ) {
				result[ filterParamNames[ name ] ] = areAnySelected ?
					// We must store all parameter values as strings '0' or '1'
					String( Number( !value ) ) :
					'0';
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
		}

		return result;
	};

	/**
	 * Get the filter representation this group would provide
	 * based on given parameter states.
	 *
	 * @param {Object|string} [paramRepresentation] An object defining a parameter
	 *  state to translate the filter state from. If not given, an object
	 *  representing all filters as falsey is returned; same as if the parameter
	 *  given were an empty object, or had some of the filters missing.
	 * @return {Object} Filter representation
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getFilterRepresentation = function ( paramRepresentation ) {
		var areAnySelected, paramValues,
			model = this,
			paramToFilterMap = {},
			result = {};

		if ( this.getType() === 'send_unselected_if_any' ) {
			paramRepresentation = paramRepresentation || {};
			// Expand param representation to include all filters in the group
			this.getItems().forEach( function ( filterItem ) {
				var paramName = filterItem.getParamName();

				paramRepresentation[ paramName ] = paramRepresentation[ paramName ] || '0';
				paramToFilterMap[ paramName ] = filterItem;

				if ( Number( paramRepresentation[ filterItem.getParamName() ] ) ) {
					areAnySelected = true;
				}
			} );

			$.each( paramRepresentation, function ( paramName, paramValue ) {
				var filterItem = paramToFilterMap[ paramName ];

				result[ filterItem.getName() ] = areAnySelected ?
					// Flip the definition between the parameter
					// state and the filter state
					// This is what the 'toggleSelected' value of the filter is
					!Number( paramValue ) :
					// Otherwise, there are no selected items in the
					// group, which means the state is false
					false;
			} );
		} else if ( this.getType() === 'string_options' ) {
			paramRepresentation = paramRepresentation || '';

			// Normalize the given parameter values
			paramValues = mw.rcfilters.utils.normalizeParamOptions(
				// Given
				paramRepresentation.split(
					this.getSeparator()
				),
				// Allowed values
				this.getItems().map( function ( filterItem ) {
					return filterItem.getParamName();
				} )
			);
			// Translate the parameter values into a filter selection state
			this.getItems().forEach( function ( filterItem ) {
				result[ filterItem.getName() ] = (
						// If it is the word 'all'
						paramValues.length === 1 && paramValues[ 0 ] === 'all' ||
						// All values are written
						paramValues.length === model.getItemCount()
					) ?
					// All true (either because all values are written or the term 'all' is written)
					// is the same as all filters set to true
					true :
					// Otherwise, the filter is selected only if it appears in the parameter values
					paramValues.indexOf( filterItem.getParamName() ) > -1;
			} );
		}

		// Go over result and make sure all filters are represented.
		// If any filters are missing, they will get a falsey value
		this.getItems().forEach( function ( filterItem ) {
			result[ filterItem.getName() ] = !!result[ filterItem.getName() ];
		} );

		return result;
	};

	/**
	 * Get item by its parameter name
	 *
	 * @param {string} paramName Parameter name
	 * @return {mw.rcfilters.dm.FilterItem} Filter item
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getItemByParamName = function ( paramName ) {
		return this.getItems().filter( function ( item ) {
			return item.getParamName() === paramName;
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
}( mediaWiki ) );
