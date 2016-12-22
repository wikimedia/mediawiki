( function ( mw ) {
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
		this.model.connect( this, { itemUpdate: 'updateURL' } );
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
			this.model.getFiltersFromParameters( uri.query )
		);
	};

	/**
	 * Reset to default filters
	 */
	mw.rcfilters.Controller.prototype.resetToDefaults = function () {
		this.model.setFiltersToDefaults();
	};

	/**
	 * Empty all selected filters
	 */
	mw.rcfilters.Controller.prototype.emptyFilters = function () {
		this.model.emptyAllFilters();
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
