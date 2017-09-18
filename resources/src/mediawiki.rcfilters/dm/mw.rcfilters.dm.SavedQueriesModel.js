( function ( mw, $ ) {
	/**
	 * View model for saved queries
	 *
	 * @class
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [default] Default query ID
	 */
	mw.rcfilters.dm.SavedQueriesModel = function MwRcfiltersDmSavedQueriesModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.default = config.default;
		this.baseState = {};

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
	 * @param {Object} [baseState] An object representing the base state
	 *  so we can normalize the data
	 * @param {string[]} [ignoreFilters] Filters to ignore and remove from
	 *  the data
	 * @fires initialize
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.initialize = function ( savedQueries, baseState, ignoreFilters ) {
		var items = [],
			defaultItem = null;

		savedQueries = savedQueries || {};
		ignoreFilters = ignoreFilters || {};

		this.baseState = baseState;

		this.clearItems();
		$.each( savedQueries.queries || {}, function ( id, obj ) {
			var item,
				normalizedData = $.extend( true, {}, baseState, obj.data ),
				isDefault = String( savedQueries.default ) === String( id );

			// Backwards-compat fix: We stored the 'highlight' state with
			// "1" and "0" instead of true/false; for already-stored states,
			// we need to fix that.
			// NOTE: Since this feature is only available in beta, we should
			// not need this line when we release this to the general wikis.
			// This method will automatically fix all saved queries anyways
			// for existing users, who are only betalabs users at the moment.
			normalizedData.highlights.highlight = !!Number( normalizedData.highlights.highlight );

			// Backwards-compat fix: Remove sticky parameters from the 'ignoreFilters' list
			ignoreFilters.forEach( function ( name ) {
				delete normalizedData.filters[ name ];
			} );

			item = new mw.rcfilters.dm.SavedQueryItemModel(
				id,
				obj.label,
				normalizedData,
				{ 'default': isDefault }
			);

			if ( isDefault ) {
				defaultItem = item;
			}

			items.push( item );
		} );

		if ( defaultItem ) {
			this.default = defaultItem.getID();
		}

		this.addItems( items );

		this.emit( 'initialize' );
	};

	/**
	 * Add a query item
	 *
	 * @param {string} label Label for the new query
	 * @param {Object} data Data for the new query
	 * @return {string} ID of the newly added query
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.addNewQuery = function ( label, data ) {
		var randomID = ( new Date() ).getTime(),
			normalizedData = $.extend( true, {}, this.baseState, data );

		// Add item
		this.addItems( [
			new mw.rcfilters.dm.SavedQueryItemModel(
				randomID,
				label,
				normalizedData
			)
		] );

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
		var model = this;

		fullQueryComparison = this.getDifferenceFromBase( fullQueryComparison );

		return this.getItems().filter( function ( item ) {
			var comparedData = model.getDifferenceFromBase( item.getData() );
			return OO.compare(
				comparedData,
				fullQueryComparison
			);
		} )[ 0 ];
	};

	/**
	 * Get a minimal representation of the state for comparison
	 *
	 * @param {Object} state Given state
	 * @return {Object} Minimal state
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getDifferenceFromBase = function ( state ) {
		var result = { filters: {}, highlights: {}, invert: state.invert },
			baseState = this.baseState;

		// XOR results
		$.each( state.filters, function ( name, value ) {
			if ( baseState.filters !== undefined && baseState.filters[ name ] !== value ) {
				result.filters[ name ] = value;
			}
		} );

		$.each( state.highlights, function ( name, value ) {
			if ( baseState.highlights !== undefined && baseState.highlights[ name ] !== value && name !== 'highlight' ) {
				result.highlights[ name ] = value;
			}
		} );

		return result;
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
	 * Get the object representing the state of the entire model and items
	 *
	 * @return {Object} Object representing the state of the model and items
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getState = function () {
		var obj = { queries: {} };

		// Translate the items to the saved object
		this.getItems().forEach( function ( item ) {
			var itemState = item.getState();

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
}( mediaWiki, jQuery ) );
