( function ( mw, $ ) {
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( model ) {
		this.model = model;

		// TODO: When we are ready, update the URL when a filter is updated
		// TODO should we do this on every itemUpdate, or only when the user submits the form
		//      or takes some other explicit action like closing the popup?
		// this.model.connect( this, { itemUpdate: 'updateURL' } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Initialize the filter and parameter states
	 */
	mw.rcfilters.Controller.prototype.initialize = function () {
		this.updateFromURL();
	};

	/**
	 * Update the model state based on the URL parameters.
	 */
	mw.rcfilters.Controller.prototype.updateFromURL = function () {
		var uri = new mw.Uri();
		this.model.updateFilters(
			// Translate the url params to filter select states
			this.model.getParametersToFilters( uri.query )
		);

		this.model.updateParameters( uri.query );
	};

	/**
	 * Update the state of a filter
	 *
	 * @param {string} filterName Filter name
	 * @param {boolean} isSelected Filter selected state
	 */
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
		window.history.pushState( { tag: 'mw-rcfilters' }, '', uri.toString() );
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

}( mediaWiki, jQuery ) );
