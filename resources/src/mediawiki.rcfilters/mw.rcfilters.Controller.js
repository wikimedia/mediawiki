( function ( mw, $ ) {
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( model, config ) {
		config = config || {};

		this.model = model;

		// Update the URL when a filter is updated
		this.model.connect( this, { itemUpdate: 'updateURL' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Initialize the filter and parameter states
	 */
	mw.rcfilters.Controller.prototype.initialize = function () {
		var uri = new mw.Uri();

		this.model.updateFilters(
			// Translate the url params to filter select states
			this.model.getParametersToFilters( uri.query )
		);

		this.model.updateParameters( uri.query );
	};

	mw.rcfilters.Controller.prototype.updateFilter = function ( filterName, isSelected ) {
		var obj = {};

		obj[ filterName ] = isSelected;
		this.model.updateFilters( obj );
	};

	/**
	 * Update the URL of the page to reflect current filters
	 */
	mw.rcfilters.Controller.prototype.updateURL = function () {
		var uri = new mw.Uri();

		uri.query = this.makeFiltersQuery();

		// Update the URL itself
		window.history.pushState( uri.query, '', uri.toString() );
	};

	/**
	 * Create the object for the url query based on filter states
	 *
	 * @return {Object} Filter query
	 */
	mw.rcfilters.Controller.prototype.makeFiltersQuery = function () {
		var filter,
			uri = new mw.Uri(),
			currFilters = this.model.getFiltersToParameters();

		// Translate true/false to integer values
		for ( filter in currFilters ) {
			currFilters[ filter ] = Number( currFilters[ filter ] );
		}

		// Add to existing queries in URL
		currFilters = $.extend( uri.query, currFilters );

		// TODO: Clean up the list of filters; perhaps 'falsy' filters
		// shouldn't appear at all? Or compare to existing query string
		// and see if current state of a specific filter is needed?
		return currFilters;
	};

} )( mediaWiki, jQuery );
