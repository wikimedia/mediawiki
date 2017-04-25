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
	 * @cfg {Object} [baseState] The base state to use for normalizing
	 *  the filters.
	 */
	mw.rcfilters.dm.SavedQueriesModel = function MwRcfiltersDmSavedQueriesModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.default = config.default;
		this.baseState = config.baseState || {};

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
	 * @param {Object} savedQueries An object with the saved queries with
	 *  the above structure.
	 * @fires initialize
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.initialize = function ( savedQueries, normalizationFunc ) {
		var items = [],
			model = this;

		savedQueries = savedQueries || {};

		this.clearItems();
		$.each( savedQueries.queries, function ( id, obj ) {
			var normalizedData = $.extend( true, {}, model.getBaseState(), obj.data );

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
	 * Check whether a given full query (mix of highlights and filters) already exists
	 * as a saved query.
	 *
	 * @param {Object} fullQueryComparison Object representing all filters and highlights to compare
	 * @return {boolean} Query exists
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.contains = function ( fullQueryComparison ) {
		return !!this.getItems().some( function ( item ) {
			return OO.compare(
					item.getData(),
					fullQueryComparison
				);
		} );
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
	 * Get the base state representing all filters with their base
	 * values.
	 *
	 * @return {Object} Base state
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getBaseState = function () {
		return this.baseState;
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
