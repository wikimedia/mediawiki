( function ( mw ) {
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( model ) {
		this.model = model;

		// TODO: When we are ready, update the URL when a filter is updated
		// this.model.connect( this, { itemUpdate: 'updateURL' } );
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
			this.model.getFiltersFromParameters( uri.query )
		);
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

		// Add to existing queries in URL
		// TODO: Clean up the list of filters; perhaps 'falsy' filters
		// shouldn't appear at all? Or compare to existing query string
		// and see if current state of a specific filter is needed?
		uri.extend( this.model.getParametersFromFilters() );

		// Update the URL itself
		window.history.pushState( { tag: 'rcfilters' }, document.title, uri.toString() );
	};
}( mediaWiki ) );
