( function ( mw, $ ) {
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters view model
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel Changes list view model
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( filtersModel, changesListModel ) {
		this.filtersModel = filtersModel;
		this.changesListModel = changesListModel;
		// TODO: When we are ready, update the URL when a filter is updated
		// TODO should we do this on every itemUpdate, or only when the user submits the form
		//      or takes some other explicit action like closing the popup?
		this.filtersModel.connect( this, { itemUpdate: 'updateURL' } );
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

		this.filtersModel.updateFilters(
			// Translate the url params to filter select states
			this.filtersModel.getFiltersFromParameters( uri.query )
		);
	};

	/**
	 * Reset to default filters
	 */
	mw.rcfilters.Controller.prototype.resetToDefaults = function () {
		this.filtersModel.setFiltersToDefaults();
	};

	/**
	 * Empty all selected filters
	 */
	mw.rcfilters.Controller.prototype.emptyFilters = function () {
		this.filtersModel.emptyAllFilters();
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
		this.filtersModel.updateFilters( obj );
		this.updateChangesList();
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
		uri.extend( this.filtersModel.getParametersFromFilters() );

		// Update the URL itself
		window.history.pushState( { tag: 'rcfilters' }, document.title, uri.toString() );
	};

	/**
	 * Fetch the list of changes from the server for the current filters
	 *
	 * @returns {string} Changes list
	 */
	mw.rcfilters.Controller.prototype.fetchChangesList = function () {
		var uri = new mw.Uri();

		uri.extend( this.filtersModel.getParametersFromFilters() );
		return $.ajax( uri.toString(), { contentType: 'html' } )
			.then( function ( html ) {
				return $( $.parseHTML( html ) ).find( '.mw-changeslist' ).first().contents();
			} ).then( null, function () {
				// todo: use a message
				return $( '<span>' ).append( 'Sorry, no result' );
			} );
	};

	/**
	 * Update the list of changes and notify the model
	 */
	mw.rcfilters.Controller.prototype.updateChangesList = function () {
		var controller = this;

		if ( this.changesListPromise ) {
			// If a submission is already pending, ignore this one
			// Not sure about this.
			// return;
		}

		this.changesListModel.invalidate();
		this.changesListPromise = this.fetchChangesList();
		this.changesListPromise
			.always( function ( changesListContent ) {
				controller.changesListModel.update( changesListContent );
				controller.changesListPromise = null;
			} );
	};
}( mediaWiki, jQuery ) );
