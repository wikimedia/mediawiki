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
		this.requestCounter = 0;
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Initialize the filter and parameter states
	 *
	 * @param {Object} filterStructure Filter definition and structure for the model
	 */
	mw.rcfilters.Controller.prototype.initialize = function ( filterStructure ) {
		var uri = new mw.Uri();

		// Initialize the model
		this.filtersModel.initializeFilters( filterStructure );

		// Set filter states based on defaults and URL params
		this.filtersModel.updateFilters(
			this.filtersModel.getFiltersFromParameters(
				// Merge defaults with URL params for initialization
				$.extend(
					true,
					{},
					this.filtersModel.getDefaultParams(),
					// URI query overrides defaults
					uri.query
				)
			)
		);

		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();

		this.filtersModel.toggleHighlight( !!Number( uri.query.highlight ) );
	};

	/**
	 * Reset to default filters
	 */
	mw.rcfilters.Controller.prototype.resetToDefaults = function () {
		this.filtersModel.setFiltersToDefaults();
		this.updateURL();
		this.updateChangesList();
	};

	/**
	 * Empty all selected filters
	 */
	mw.rcfilters.Controller.prototype.emptyFilters = function () {
		this.filtersModel.emptyAllFilters();
		this.updateURL();
		this.updateChangesList();
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
		this.updateURL();
		this.updateChangesList();

		// Check filter interactions
		this.filtersModel.reassessFilterInteractions( this.filtersModel.getItemByName( filterName ) );
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

		uri.extend( { highlight: Number( this.filtersModel.isHighlightEnabled() ) } );

		// Update the URL itself
		window.history.pushState( { tag: 'rcfilters' }, document.title, uri.toString() );
	};

	/**
	 * Fetch the list of changes from the server for the current filters
	 *
	 * @returns {jQuery.Promise} Promise object that will resolve with the changes list
	 */
	mw.rcfilters.Controller.prototype.fetchChangesList = function () {
		var uri = new mw.Uri(),
			requestId = ++this.requestCounter,
			latestRequest = function () {
				return requestId === this.requestCounter;
			}.bind( this );
		uri.extend( this.filtersModel.getParametersFromFilters() );
		return $.ajax( uri.toString(), { contentType: 'html' } )
			.then( function ( html ) {
				return latestRequest() ?
					$( $.parseHTML( html ) ).find( '.mw-changeslist' ).first().contents() :
					null;
			} ).then( null, function () {
				return latestRequest() ? 'NO_RESULTS' : null;
			} );
	};

	/**
	 * Update the list of changes and notify the model
	 */
	mw.rcfilters.Controller.prototype.updateChangesList = function () {
		this.changesListModel.invalidate();
		this.fetchChangesList()
			.always( function ( changesListContent ) {
				if ( changesListContent ) {
					this.changesListModel.update( changesListContent );
				}
			}.bind( this ) );
	};

	/**
	 * Toggle highlight feature
	 */
	mw.rcfilters.Controller.prototype.toggleHighlight = function () {
		this.filtersModel.toggleHighlight();
		this.updateURL();
	};

	mw.rcfilters.Controller.prototype.chooseHighlightColor = function ( filterName, color ) {
		this.filtersModel.chooseHighlightColor( filterName, color );
	};

	mw.rcfilters.Controller.prototype.clearHighlight = function ( filterName ) {
		this.filtersModel.clearHighlight( filterName );
	};

}( mediaWiki, jQuery ) );
