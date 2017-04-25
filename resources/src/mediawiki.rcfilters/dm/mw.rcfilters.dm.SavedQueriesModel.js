( function ( mw, $ ) {
	/**
	 * View mdel for saved queries
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	mw.rcfilters.dm.SavedQueriesModel = function MwRcfiltersDmSavedQueriesModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.default = null;

		// Events
		this.aggregate( { update: 'queryItemUpdate' } );
		this.connect( this, { queryItemUpdate: [ 'emit', 'itemUpdate' ] } );
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
	 * @event default
	 * @param {string} QueryID Query identifier
	 *
	 * Default value has changed
	 */

	/* Methods */

	/**
	 * Initialize the saved queries model by reading it from the user's settings.
	 * The structure of the saved queries is:
	 * {
	 *    query_id_1: {
	 *       data:{
	 *          params: (Object) Definition of the parameters
	 *          highlights: (Object) Definition of the highlights
	 *       },
	 *       label: (optional) Name of this query
	 *    }
	 * }
	 *
	 * @fires initialize
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.initialize = function () {
		var savedQueries = JSON.parse( mw.user.options.get( 'rcfilters-saved-queries' ) ) || {},
			items = [];

		this.clearItems();
		$.each( savedQueries, function ( id, data ) {
			items.push(
				new mw.rcfilters.dm.SavedQueryItemModel(
					id,
					data.label,
					data.data,
					{ default: savedQueries.default === id }
				)
			);
		} );

		this.addItems( items );

		this.emit( 'initialize' );
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
		var obj = {};

		// Translate the items to the saved object
		this.getItems().forEach( function ( item ) {
			obj[ item.getID() ] = item.getState();
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

			this.emit( 'default', this.default );
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
	 * Save the state in the user settings
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.save = function () {
		var stringified = JSON.stringify( this.getState() );

		// Save the preference in general
		new mw.Api().saveOption( 'rcfilters-saved-queries', stringified );
		// Save the preference for this session
		mw.user.options.set( 'rcfilters-saved-queries', stringified );
	};

}( mediaWiki, jQuery ) );
