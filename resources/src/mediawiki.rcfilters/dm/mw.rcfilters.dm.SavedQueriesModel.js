( function ( mw, $ ) {
	/**
	 * View mdel for saved queries
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters view model
	 */
	mw.rcfilters.dm.SavedQueriesModel = function MwRcfiltersDmSavedQueriesModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );

		// Initialize
		this.queries = JSON.parse( mw.user.options.get( 'rcfilters-saved-queries' ) ) || {};
		this.default = null;
	};

	/* Initialization */

	OO.initClass( mw.rcfilters.dm.SavedQueriesModel );
	OO.mixinClass( mw.rcfilters.dm.SavedQueriesModel, OO.EventEmitter );

	/* Events */

	/**
	 * @event add
	 * @param {string} queryID Query ID
	 *
	 * Saved query added
	 */

	/**
	 * @event remove
	 * @param {string} queryID Query ID
	 *
	 * Saved query removed
	 */

	/**
	 * @event edit
	 * @param {string} queryID Query ID
	 *
	 * Saved query edited
	 */

	/* Methods */

	/**
	 * Get all queries
	 *
	 * @return {Object} Queries object
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getQueries = function () {
		return this.queries;
	};

	/**
	 * Get a specific query by its ID
	 *
	 * @param {string} queryId Query ID
	 * @return {Object} Query data
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getQueryByID = function ( queryId ) {
		return this.queries[ queryId ];
	};

	/**
	 * Add a query
	 *
	 * @param {string} queryId Query ID
	 * @fires remove
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.removeQuery = function ( queryId ) {
		if ( this.getQueryByID( queryId ) ) {
			delete this.queries[ queryId ];
			this.saveQueriesObject();

			this.emit( 'remove', queryId );
		}
	};

	/**
	 * Add a query
	 *
	 * @param {string} queryId Query ID
	 * @param {Object} data Query data
	 * @fires add
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.addQuery = function ( queryId, data ) {
		if ( !this.getQueryByID( queryId ) ) {
			this.queries[ queryId ] = data;
			this.saveQueriesObject();

			this.emit( 'add', queryId );
		}
	};

	mw.rcfilters.dm.SavedQueriesModel.prototype.editQueryLabel = function ( queryId, newLabel ) {
		var data = this.queries[ queryId ];

		if ( newLabel && data.label !== newLabel ) {
			this.queries[ queryId ].label = newLabel;
			this.saveQueriesObject();

			this.emit( 'edit', queryId );
		}
	};

	/**
	 * Set a query as default
	 *
	 * @param {string} [queryId] Query to set as default. If none is given
	 *  there will be no defaults.
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.setDefault = function ( queryId ) {
		var model = this;

		$.each( this.queries, function ( id, data ) {
			model.queries[ id ].default = id === queryId;
		} );
		this.default = queryId;

		this.saveQueriesObject();

		this.emit( 'default', queryId );
	};

	/**
	 * Get the default query
	 *
	 * @return {string} Query id
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.getDefault = function () {
		var model = this,
			foundId = null;

		$.each( this.queries, function ( id, data ) {
			if ( !foundId && data.default ) {
				foundId = id;
			}
		} );

		return foundId;
	};

	/**
	 * Save the object in the preferences
	 *
	 * @private
	 */
	mw.rcfilters.dm.SavedQueriesModel.prototype.saveQueriesObject = function () {
		var stringified = JSON.stringify( this.queries );

		// Save the preference in general
		new mw.Api().saveOption( 'rcfilters-saved-queries', stringified );
		// Save the preference for this session
		mw.user.options.set( 'rcfilters-saved-queries', stringified );
	};
}( mediaWiki, jQuery ) );
