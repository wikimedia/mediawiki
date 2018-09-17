/* eslint-disable no-restricted-properties */
( function () {
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
		var model = this;

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
				// Backwards-compat fix: Remove sticky parameters from
				// the given data, if they exist
				normalizedData.params = model.filtersModel.removeStickyParams( normalizedData.params );

				// Correct the invert state for effective selection
				if ( normalizedData.params.invert && !normalizedData.params.namespace ) {
					delete normalizedData.params.invert;
				}

				model.cleanupHighlights( normalizedData );

				id = String( id );

				// Skip the addNewQuery method because we don't want to unnecessarily manipulate
				// the given saved queries unless we literally intend to (like in backwards compat fixes)
				// And the addNewQuery method also uses a minimization routine that checks for the
				// validity of items and minimizes the query. This isn't necessary for queries loaded
				// from the backend, and has the risk of removing values if they're temporarily
				// invalid (example: if we temporarily removed a cssClass from a filter in the backend)
				model.addItems( [
					new mw.rcfilters.dm.SavedQueryItemModel(
						id,
						obj.label,
						normalizedData,
						{ 'default': isDefault }
					)
				] );

				if ( isDefault ) {
					model.default = id;
				}
			}
		} );

		this.emit( 'initialize' );
	};

	/**
	 * Clean up highlight parameters.
	 * 'highlight' used to be stored, it's not inferred based on the presence of absence of
	 * filter colors.
	 *
	 * @param {Object} data Saved query data
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.cleanupHighlights = function ( data ) {
		if (
			data.params.highlight === '0' &&
			data.highlights && Object.keys( data.highlights ).length
		) {
			data.highlights = {};
		}
		delete data.params.highlight;
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
		newData.params = this.filtersModel.getMinimizedParamRepresentation(
			this.filtersModel.getParametersFromFilters( fullFilterRepresentation )
		);

		// Highlights: appending _color to keys
		newData.highlights = {};
		$.each( data.highlights, function ( highlightedFilterName, value ) {
			if ( value ) {
				newData.highlights[ highlightedFilterName + '_color' ] = data.highlights[ highlightedFilterName ];
			}
		} );

		// Add highlight
		newData.params.highlight = String( Number( highlightEnabled || 0 ) );

		return newData;
	};

	/**
	 * Add a query item
	 *
	 * @param {string} label Label for the new query
	 * @param {Object} fulldata Full data representation for the new query, combining highlights and filters
	 * @param {boolean} isDefault Item is default
	 * @param {string} [id] Query ID, if exists. If this isn't given, a random
	 *  new ID will be created.
	 * @return {string} ID of the newly added query
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.addNewQuery = function ( label, fulldata, isDefault, id ) {
		var normalizedData = { params: {}, highlights: {} },
			highlightParamNames = Object.keys( this.filtersModel.getEmptyHighlightParameters() ),
			randomID = String( id || ( new Date() ).getTime() ),
			data = this.filtersModel.getMinimizedParamRepresentation( fulldata );

		// Split highlight/params
		$.each( data, function ( param, value ) {
			if ( param !== 'highlight' && highlightParamNames.indexOf( param ) > -1 ) {
				normalizedData.highlights[ param ] = value;
			} else {
				normalizedData.params[ param ] = value;
			}
		} );

		// Correct the invert state for effective selection
		if ( normalizedData.params.invert && !this.filtersModel.areNamespacesEffectivelyInverted() ) {
			delete normalizedData.params.invert;
		}

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
		fullQueryComparison = this.filtersModel.getMinimizedParamRepresentation( fullQueryComparison );

		// Correct the invert state for effective selection
		if ( fullQueryComparison.invert && !this.filtersModel.areNamespacesEffectivelyInverted() ) {
			delete fullQueryComparison.invert;
		}

		return this.getItems().filter( function ( item ) {
			return OO.compare(
				item.getCombinedData(),
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
	 * Get the full data representation of the default query, if it exists
	 *
	 * @return {Object|null} Representation of the default params if exists.
	 *  Null if default doesn't exist or if the user is not logged in.
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getDefaultParams = function () {
		return ( !mw.user.isAnon() && this.getItemParams( this.getDefault() ) ) || {};
	};

	/**
	 * Get a full parameter representation of an item data
	 *
	 * @param  {Object} queryID Query ID
	 * @return {Object} Parameter representation
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getItemParams = function ( queryID ) {
		var item = this.getItemByID( queryID ),
			data = item ? item.getData() : {};

		return !$.isEmptyObject( data ) ? this.buildParamsFromData( data ) : {};
	};

	/**
	 * Build a full parameter representation given item data and model sticky values state
	 *
	 * @param  {Object} data Item data
	 * @return {Object} Full param representation
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.buildParamsFromData = function ( data ) {
		data = data || {};
		// Return parameter representation
		return this.filtersModel.getMinimizedParamRepresentation( $.extend( true, {},
			data.params,
			data.highlights
		) );
	};

	/**
	 * Get the object representing the state of the entire model and items
	 *
	 * @return {Object} Object representing the state of the model and items
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getState = function () {
		var obj = { queries: {}, version: '2' };

		// Translate the items to the saved object
		this.getItems().forEach( function ( item ) {
			obj.queries[ item.getID() ] = item.getState();
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
}() );
