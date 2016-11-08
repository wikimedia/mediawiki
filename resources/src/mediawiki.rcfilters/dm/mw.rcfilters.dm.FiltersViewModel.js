( function ( mw ) {
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

		// Events
		this.aggregate( { update: 'itemUpdate' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.FiltersViewModel );
	OO.mixinClass( mw.rcfilters.dm.FiltersViewModel, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.FiltersViewModel, OO.EmitterList );

	/* Events */

	/**
	 * @event update
	 *
	 * Filter list has changed
	 */

	/* Methods */

	/**
	 * Set filters and preserve a group relationship based on
	 * the definition given by an object
	 *
	 * @param {Object} filters Filter group definition
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.setFilters = function ( filters ) {
		var i, filterItem, group,
			items = [];

		for ( group in filters ) {
			this.groups[ group ] = this.groups[ group ] || {};
			this.groups[ group ].filters = this.groups[ group ].filters || [];

			this.groups[ group ].title = filters[ group ].title;
			this.groups[ group ].type = filters[ group ].type;

			for ( i = 0; i < filters[ group ].filters.length; i++ ) {
				filterItem = new mw.rcfilters.dm.FilterItem( filters[ group ].filters[ i ].name, {
					group: group,
					label: filters[ group ].filters[ i ].label,
					description: filters[ group ].filters[ i ].description,
					selected: filters[ group ].filters[ i ].selected
				} );

				this.groups[ group ].filters.push( filterItem );
				items.push( filterItem );
			}
		}

		this.addItems( items );
		this.emit( 'update' );
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
	 * Get the object that defines groups and their filter items
	 *
	 * @return {Object} Filter groups
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFilterGroups = function () {
		return this.groups;
	};

	/**
	 * Analyze the groups and their filters and output an object representing
	 * the state of the parameters they represent.
	 *
	 * @return {Object} Parameter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getFiltersToParameters = function () {
		var i, group, filterItems,
			result = {},
			groupItems = this.getFilterGroups();

		for ( group in groupItems ) {
			if ( groupItems[ group ].type === 'send_unselected_if_any' ) {
				filterItems = groupItems[ group ].filters;

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
		}

		return result;
	};

	/**
	 * This is the opposite of the #getFiltersToParameters method; this goes over
	 * the parameters and translates into a selected/unselected value in the filters.
	 *
	 * @param {Object} [params] Parameteres query object
	 * @return {Object} Filter state object
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getParametersToFilters = function ( params ) {
		var i, p, filterItem, group, allItemsInGroup,
			groupMap = {},
			result = {};

		params = params || ( new mw.Uri() ).query;

		for ( p in params ) {
			// Find the filter item
			filterItem = this.getItemByName( p );

			if ( !filterItem ) {
				// No filter item exists
				continue;
			}

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

		// Now that we know the groups selection states, we need to go over
		// the filters in the groups and mark their selected states appropriately
		for ( group in groupMap ) {
			if (
				this.groups[ group ].type === 'send_unselected_if_any' &&
				// For this type, we only care about groups that have any
				// parameters selected
				groupMap[ group ].hasSelected
			) {
				allItemsInGroup = this.groups[ group ].filters;

				for ( i = 0; i < allItemsInGroup.length; i++ ) {
					filterItem = allItemsInGroup[ i ];

					// Flip the definition between the parameter
					// state and the filter state
					// This is what the 'toggleSelected' value of the filter is
					result[ filterItem.getName() ] = !Number( params[ filterItem.getName() ] );
				}
			}
		}
		return result;
	};

	/**
	 * Get the item that matches the given name
	 * @param {string} name Filter name
	 * @return {mw.rcfilters.dm.FilterItem} Filter item
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.getItemByName = function ( names ) {
		return this.getItems().filter( function ( item ) {
			return names.indexOf( item.getName() ) > -1;
		} )[ 0 ];
	};

	/**
	 * Toggle selected state of items by their names
	 *
	 * @param {Object} Filter definitions
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

		for ( i = 0; i < items.length; i++ ) {
			if ( items[ i ].getLabel().toLowerCase().indexOf( str.toLowerCase() ) > -1 ) {
				result[ items[ i ].getGroup() ] = result[ items[ i ].getGroup() ] || [];
				result[ items[ i ].getGroup() ].push( items[ i ] );
			}
		}
		return result;
	};

} )( mediaWiki );
