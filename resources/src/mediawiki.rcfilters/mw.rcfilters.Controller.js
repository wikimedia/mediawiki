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

		// Initialize highlights
		this.filtersModel.toggleHighlight( !!uri.query.highlight );
		this.filtersModel.getItems().forEach( function ( filterItem ) {
			var color = uri.query[ filterItem.getName() + '_color' ];
			if ( !color ) {
				return;
			}

			filterItem.setHighlightColor( color );
		} );

		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();
	};

	/**
	 * Reset to default filters
	 */
	mw.rcfilters.Controller.prototype.resetToDefaults = function () {
		this.filtersModel.setFiltersToDefaults();
		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();

		this.updateURL();
		this.updateChangesList();
	};

	/**
	 * Empty all selected filters
	 */
	mw.rcfilters.Controller.prototype.emptyFilters = function () {
		this.filtersModel.emptyAllFilters();
		this.filtersModel.clearAllHighlightColors();
		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();

		this.updateURL();
		this.updateChangesList();
	};

	/**
	 * Update the selected state of a filter
	 *
	 * @param {string} filterName Filter name
	 * @param {boolean} [isSelected] Filter selected state
	 */
	mw.rcfilters.Controller.prototype.updateFilter = function ( filterName, isSelected ) {
		var obj = {},
			filterItem = this.filtersModel.getItemByName( filterName );

		isSelected = isSelected === undefined ? !filterItem.isSelected() : isSelected;

		if ( filterItem.isSelected() !== isSelected ) {
			obj[ filterName ] = isSelected;
			this.filtersModel.updateFilters( obj );

			this.updateURL();
			this.updateChangesList();

			// Check filter interactions
			this.filtersModel.reassessFilterInteractions( this.filtersModel.getItemByName( filterName ) );
		}
	};

	/**
	 * Update the URL of the page to reflect current filters
	 */
	mw.rcfilters.Controller.prototype.updateURL = function () {
		var uri = this.getUpdatedUri();
		window.history.pushState( { tag: 'rcfilters' }, document.title, uri.toString() );
	};

	/**
	 * Get an updated mw.Uri object based on the model state
	 *
	 * @return {mw.Uri} Updated Uri
	 */
	mw.rcfilters.Controller.prototype.getUpdatedUri = function () {
		var uri = new mw.Uri(),
			highlightParams = this.filtersModel.getHighlightParameters();

		// Add to existing queries in URL
		// TODO: Clean up the list of filters; perhaps 'falsy' filters
		// shouldn't appear at all? Or compare to existing query string
		// and see if current state of a specific filter is needed?
		uri.extend( this.filtersModel.getParametersFromFilters() );

		// highlight params
		Object.keys( highlightParams ).forEach( function ( paramName ) {
			if ( highlightParams[ paramName ] ) {
				uri.query[ paramName ] = highlightParams[ paramName ];
			} else {
				delete uri.query[ paramName ];
			}
		} );

		return uri;
	};

	/**
	 * Fetch the list of changes from the server for the current filters
	 *
	 * @return {jQuery.Promise} Promise object that will resolve with the changes list
	 */
	mw.rcfilters.Controller.prototype.fetchChangesList = function () {
		var uri = this.getUpdatedUri(),
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
	 * Toggle the highlight feature on and off
	 */
	mw.rcfilters.Controller.prototype.toggleHighlight = function () {
		this.filtersModel.toggleHighlight();
		this.updateURL();
	};

	/**
	 * Set the highlight color for a filter item
	 *
	 * @param {string} filterName Name of the filter item
	 * @param {string} color Selected color
	 */
	mw.rcfilters.Controller.prototype.setHighlightColor = function ( filterName, color ) {
		this.filtersModel.setHighlightColor( filterName, color );
		this.updateURL();
	};

	/**
	 * Clear highlight for a filter item
	 *
	 * @param {string} filterName Name of the filter item
	 */
	mw.rcfilters.Controller.prototype.clearHighlightColor = function ( filterName ) {
		this.filtersModel.clearHighlightColor( filterName );
		this.updateURL();
	};
}( mediaWiki, jQuery ) );
