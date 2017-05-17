( function ( mw, $ ) {
	/**
	 * View model for saved queries
	 *
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
	 * @fires initialize
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.initialize = function ( savedQueries, baseState ) {
		var items = [];

		savedQueries = savedQueries || {};

		this.baseState = baseState;

		this.clearItems();
		$.each( savedQueries.queries || {}, function ( id, obj ) {
			var normalizedData = $.extend( true, {}, baseState, obj.data );

			// Backwards-compat fix: We stored the 'highlight' state with
			// "1" and "0" instead of true/false; for already-stored states,
			// we need to fix that.
			// NOTE: Since this feature is only available in beta, we should
			// not need this line when we release this to the general wikis.
			// This method will automatically fix all saved queries anyways
			// for existing users, who are only betalabs users at the moment.
			normalizedData.highlights.highlight = !!Number( normalizedData.highlights.highlight );

			items.push(
				new mw.rcfilters.dm.SavedQueryItemModel(
					id,
					obj.label,
					normalizedData,
					{ 'default': savedQueries.default === id }
				)
			);
		} );

		this.default = savedQueries.default;

		this.addItems( items );

		this.emit( 'initialize' );
	};

	/**
	 * Add a query item
	 *
	 * @param {string} label Label for the new query
	 * @param {Object} data Data for the new query
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
	};

	/**
	 * Get an item that matches the requested query
	 *
	 * @param {Object} fullQueryComparison Object representing all filters and highlights to compare
	 * @return {mw.rcfilters.dm.SavedQueryItemModel} Matching item model
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.findMatchingQuery = function ( fullQueryComparison ) {
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
