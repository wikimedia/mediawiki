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

	mw.rcfilters.dm.FiltersViewModel.prototype.getFiltersState = function () {
		var i,
			result = {},
			items = this.getItems();

		for ( i = 0; i < items.length; i++ ) {
			result[ items[ i ].getName() ] = items[ i ].isSelected();
		}

		return result;
	};


	mw.rcfilters.dm.FiltersViewModel.prototype.getItemsByName = function ( names ) {
		return this.getItems().filter( function ( item ) {
			return names.indexOf( item.getName() ) > -1;
		} );
	};

	/**
	 * Toggle selected state of items by their names
	 *
	 * @param {Object} Filter definitions
	 */
	mw.rcfilters.dm.FiltersViewModel.prototype.updateFilters = function ( filterDef ) {
		var name, filterItem;

		for ( name in filterDef ) {
			filterItem = this.getItemsByName( name )[ 0 ];
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
