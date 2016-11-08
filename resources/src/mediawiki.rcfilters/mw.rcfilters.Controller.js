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
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Change the state of a group of filters
	 *
	 * @param {Object} filterObj Definintion of the updated filters by their name
	 * and state
	 */
	mw.rcfilters.Controller.prototype.updateFilters = function ( filterObj ) {
		// Update the model
		this.model.updateFilters( filterObj );

		// Update URL
		this.updateURL();
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

	mw.rcfilters.Controller.prototype.makeFiltersQuery = function () {
		var filter,
			uri = new mw.Uri(),
			currFilters = this.model.getFiltersState();

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
