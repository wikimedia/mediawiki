( function ( mw, $ ) {
	/**
	 * View model for saved queries
	 *
	 * @class
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters model
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [default] Default query ID
	 */
	mw.rcfilters.dm.SavedQueriesModel = function MwRcfiltersDmSavedQueriesModel( filtersModel, config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.default = config.default;
		this.filtersModel = filtersModel;
		this.converted = false;

		// Events
		this.aggregate( { update: 'itemUpdate' } );
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.SavedQueriesModel );
	OO.mixinClass( mw.rcfilters.dm.SavedQueriesModel, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.SavedQueriesModel, OO.EmitterList );

	/* Events */

	/**
	 * @event initialize
	 *
	 * Model is initialized
	 */

	/**
	 * @event itemUpdate
	 * @param {mw.rcfilters.dm.SavedQueryItemModel} Changed item
	 *
	 * An item has changed
	 */

	/**
	 * @event default
	 * @param {string} New default ID
	 *
	 * The default has changed
	 */

	/* Methods */

	/**
	 * Initialize the saved queries model by reading it from the user's settings.
	 * The structure of the saved queries is:
	 * {
	 *    version: (string) Version number; if version 2, the query represents
	 *             parameters. Otherwise, the older version represented filters
	 *             and needs to be readjusted,
	 *    default: (string) Query ID
	 *    queries:{
	 *       query_id_1: {
	 *          data:{
	 *             filters: (Object) Minimal definition of the filters
	 *             highlights: (Object) Definition of the highlights
	 *          },
	 *          label: (optional) Name of this query
	 *       }
	 *    }
	 * }
	 *
	 * @param {Object} [savedQueries] An object with the saved queries with
	 *  the above structure.
	 * @fires initialize
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.initialize = function ( savedQueries ) {
		var model = this,
			excludedParams = this.filtersModel.getExcludedParams();

		savedQueries = savedQueries || {};

		this.clearItems();
		this.default = null;
		this.converted = false;

		if ( savedQueries.version !== '2' ) {
			// Old version dealt with filter names. We need to migrate to the new structure
			// The new structure:
			// {
			//   version: (string) '2',
			//   default: (string) Query ID,
			//   queries: {
			//     query_id: {
			//       label: (string) Name of the query
			//       data: {
			//         params: (object) Representing all the parameter states
			//         highlights: (object) Representing all the filter highlight states
			//     }
			//   }
			// }
			$.each( savedQueries.queries || {}, function ( id, obj ) {
				if ( obj.data && obj.data.filters ) {
					obj.data = model.convertToParameters( obj.data );
				}
			} );

			this.converted = true;
			savedQueries.version = '2';
		}

		// Initialize the query items
		$.each( savedQueries.queries || {}, function ( id, obj ) {
			var normalizedData = obj.data,
				isDefault = String( savedQueries.default ) === String( id );

			if ( normalizedData && normalizedData.params ) {
				// Backwards-compat fix: Remove excluded parameters from
				// the given data, if they exist
				excludedParams.forEach( function ( name ) {
					delete normalizedData.params[ name ];
				} );

				id = String( id );
				model.addNewQuery( obj.label, normalizedData, isDefault, id );

				if ( isDefault ) {
					model.default = id;
				}
			}
		} );

		this.emit( 'initialize' );
	};

	/**
	 * Convert from representation of filters to representation of parameters
	 *
	 * @param {Object} data Query data
	 * @return {Object} New converted query data
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.convertToParameters = function ( data ) {
		var newData = {},
			defaultFilters = this.filtersModel.getFiltersFromParameters( this.filtersModel.getDefaultParams() ),
			fullFilterRepresentation = $.extend( true, {}, defaultFilters, data.filters ),
			highlightEnabled = data.highlights.highlight;

		delete data.highlights.highlight;

		// Filters
		newData.params = this.filtersModel.getParametersFromFilters( fullFilterRepresentation );

		// Highlights (taking out 'highlight' itself, appending _color to keys)
		newData.highlights = {};
		Object.keys( data.highlights ).forEach( function ( highlightedFilterName ) {
			newData.highlights[ highlightedFilterName + '_color' ] = data.highlights[ highlightedFilterName ];
		} );

		// Add highlight and invert toggles to params
		newData.params.highlight = String( Number( highlightEnabled || 0 ) );
		newData.params.invert = String( Number( data.invert || 0 ) );

		return newData;
	};

	/**
	 * Get an object representing the base state of parameters
	 * and highlights.
	 *
	 * This is meant to make sure that the saved queries that are
	 * in memory are always the same structure as what we would get
	 * by calling the current model's "getSelectedState" and by checking
	 * highlight items.
	 *
	 * In cases where a user saved a query when the system had a certain
	 * set of params, and then a filter was added to the system, we want
	 * to make sure that the stored queries can still be comparable to
	 * the current state, which means that we need the base state for
	 * two operations:
	 *
	 * - Saved queries are stored in "minimal" view (only changed params
	 *   are stored); When we initialize the system, we merge each minimal
	 *   query with the base state (using 'getMinimalParamList') so all
	 *   saved queries have the exact same structure as what we would get
	 *   by checking the getSelectedState of the filter.
	 * - When we save the queries, we minimize the object to only represent
	 *   whatever has actually changed, rather than store the entire
	 *   object. To check what actually is different so we can store it,
	 *   we need to obtain a base state to compare against, this is
	 *   what #getMinimalParamList does
	 *
	 * @return {Object} Base parameter state
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getBaseParamState = function () {
		var allParams,
			highlightedItems = {};

		if ( !this.baseParamState ) {
			allParams = this.filtersModel.getParametersFromFilters( {} );

			// Prepare highlights
			this.filtersModel.getItemsSupportingHighlights().forEach( function ( item ) {
				highlightedItems[ item.getName() + '_color' ] = null;
			} );

			this.baseParamState = {
				params: $.extend( true, { invert: '0', highlight: '0' }, allParams ),
				highlights: highlightedItems
			};
		}

		return this.baseParamState;
	};

	/**
	 * Get an object that holds only the parameters and highlights that have
	 * values different than the base value.
	 *
	 * This is the reverse of the normalization we do initially on loading and
	 * initializing the saved queries model.
	 *
	 * @param {Object} valuesObject Object representing the state of both
	 *  filters and highlights in its normalized version, to be minimized.
	 * @return {Object} Minimal filters and highlights list
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getMinimalParamList = function ( valuesObject ) {
		var result = { params: {}, highlights: {} },
			baseState = this.getBaseParamState();

		// XOR results
		$.each( valuesObject.params, function ( name, value ) {
			if ( baseState.params !== undefined && baseState.params[ name ] !== value ) {
				result.params[ name ] = value;
			}
		} );

		$.each( valuesObject.highlights, function ( name, value ) {
			if ( baseState.highlights !== undefined && baseState.highlights[ name ] !== value ) {
				result.highlights[ name ] = value;
			}
		} );

		return result;
	};

	/**
	 * Add a query item
	 *
	 * @param {string} label Label for the new query
	 * @param {Object} data Data for the new query
	 * @param {boolean} isDefault Item is default
	 * @param {string} [id] Query ID, if exists. If this isn't given, a random
	 *  new ID will be created.
	 * @return {string} ID of the newly added query
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.addNewQuery = function ( label, data, isDefault, id ) {
		var randomID = String( id || ( new Date() ).getTime() ),
			normalizedData = this.getMinimalParamList( data );

		// Add item
		this.addItems( [
			new mw.rcfilters.dm.SavedQueryItemModel(
				randomID,
				label,
				normalizedData,
				{ 'default': isDefault }
			)
		] );

		if ( isDefault ) {
			this.setDefault( randomID );
		}

		return randomID;
	};

	/**
	 * Remove query from model
	 *
	 * @param {string} queryID Query ID
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.removeQuery = function ( queryID ) {
		var query = this.getItemByID( queryID );

		if ( query ) {
			// Check if this item was the default
			if ( String( this.getDefault() ) === String( queryID ) ) {
				// Nulify the default
				this.setDefault( null );
			}

			this.removeItems( [ query ] );
		}
	};

	/**
	 * Get an item that matches the requested query
	 *
	 * @param {Object} fullQueryComparison Object representing all filters and highlights to compare
	 * @return {mw.rcfilters.dm.SavedQueryItemModel} Matching item model
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.findMatchingQuery = function ( fullQueryComparison ) {
		// Minimize before comparison
		fullQueryComparison = this.getMinimalParamList( fullQueryComparison );

		return this.getItems().filter( function ( item ) {
			return OO.compare(
				item.getData(),
				fullQueryComparison
			);
		} )[ 0 ];
	};

	/**
	 * Get query by its identifier
	 *
	 * @param {string} queryID Query identifier
	 * @return {mw.rcfilters.dm.SavedQueryItemModel|undefined} Item matching
	 *  the search. Undefined if not found.
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getItemByID = function ( queryID ) {
		return this.getItems().filter( function ( item ) {
			return item.getID() === queryID;
		} )[ 0 ];
	};

	/**
	 * Get an item's full data
	 *
	 * @param {string} queryID Query identifier
	 * @return {Object} Item's full data
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getItemFullData = function ( queryID ) {
		var item = this.getItemByID( queryID );

		// Fill in the base params
		return item ? $.extend( true, {}, this.getBaseParamState(), item.getData() ) : {};
	};

	/**
	 * Get the object representing the state of the entire model and items
	 *
	 * @return {Object} Object representing the state of the model and items
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getState = function () {
		var model = this,
			obj = { queries: {}, version: '2' };

		// Translate the items to the saved object
		this.getItems().forEach( function ( item ) {
			var itemState = item.getState();

			itemState.data = model.getMinimalParamList( itemState.data );

			obj.queries[ item.getID() ] = itemState;
		} );

		if ( this.getDefault() ) {
			obj.default = this.getDefault();
		}

		return obj;
	};

	/**
	 * Set a default query. Null to unset default.
	 *
	 * @param {string} itemID Query identifier
	 * @fires default
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.setDefault = function ( itemID ) {
		if ( this.default !== itemID ) {
			this.default = itemID;

			// Set for individual itens
			this.getItems().forEach( function ( item ) {
				item.toggleDefault( item.getID() === itemID );
			} );

			this.emit( 'default', itemID );
		}
	};

	/**
	 * Get the default query ID
	 *
	 * @return {string} Default query identifier
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getDefault = function () {
		return this.default;
	};

	/**
	 * Check if the saved queries were converted
	 *
	 * @return {boolean} Saved queries were converted from the previous
	 *  version to the new version
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.isConverted = function () {
		return this.converted;
	};
}( mediaWiki, jQuery ) );
