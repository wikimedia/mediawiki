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
	 * @param {Array} filterStructure Filter definition and structure for the model
	 */
	mw.rcfilters.Controller.prototype.initialize = function ( filterStructure ) {
		var $changesList = $( '.mw-changeslist' ).first().contents();
		// Initialize the model
		this.filtersModel.initializeFilters( filterStructure );
		this.updateStateBasedOnUrl();

		// Update the changes list with the existing data
		// so it gets processed
		this.changesListModel.update(
			$changesList.length ? $changesList : 'NO_RESULTS',
			$( 'fieldset.rcoptions' ).first()
		);

	};

	/**
	 * Update filter state (selection and highlighting) based
	 * on current URL and default values.
	 */
	mw.rcfilters.Controller.prototype.updateStateBasedOnUrl = function () {
		var uri = new mw.Uri();

		// Set filter states based on defaults and URL params
		this.filtersModel.toggleFiltersSelected(
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
			if ( color ) {
				filterItem.setHighlightColor( color );
			} else {
				filterItem.clearHighlightColor();
			}
		} );

		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();
	};

	/**
	 * Reset to default filters
	 */
	mw.rcfilters.Controller.prototype.resetToDefaults = function () {
		this.filtersModel.setFiltersToDefaults();
		this.filtersModel.clearAllHighlightColors();
		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();

		this.updateChangesList();
	};

	/**
	 * Empty all selected filters
	 */
	mw.rcfilters.Controller.prototype.emptyFilters = function () {
		var highlightedFilterNames = this.filtersModel
			.getHighlightedItems()
			.map( function ( filterItem ) { return { name: filterItem.getName() }; } );

		this.filtersModel.emptyAllFilters();
		this.filtersModel.clearAllHighlightColors();
		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();

		this.updateChangesList();

		if ( highlightedFilterNames ) {
			this.trackHighlight( 'clearAll', highlightedFilterNames );
		}
	};

	/**
	 * Update the selected state of a filter
	 *
	 * @param {string} filterName Filter name
	 * @param {boolean} [isSelected] Filter selected state
	 */
	mw.rcfilters.Controller.prototype.toggleFilterSelect = function ( filterName, isSelected ) {
		var filterItem = this.filtersModel.getItemByName( filterName );

		if ( !filterItem ) {
			// If no filter was found, break
			return;
		}

		isSelected = isSelected === undefined ? !filterItem.isSelected() : isSelected;

		if ( filterItem.isSelected() !== isSelected ) {
			this.filtersModel.toggleFilterSelected( filterName, isSelected );

			this.updateChangesList();

			// Check filter interactions
			this.filtersModel.reassessFilterInteractions( filterItem );
		}
	};

	/**
	 * Update the URL of the page to reflect current filters
	 *
	 * This should not be called directly from outside the controller.
	 * If an action requires changing the URL, it should either use the
	 * highlighting actions below, or call #updateChangesList which does
	 * the uri corrections already.
	 *
	 * @private
	 * @param {Object} [params] Extra parameters to add to the API call
	 */
	mw.rcfilters.Controller.prototype.updateURL = function ( params ) {
		var updatedUri,
			notEquivalent = function ( obj1, obj2 ) {
				var keys = Object.keys( obj1 ).concat( Object.keys( obj2 ) );
				return keys.some( function ( key ) {
					return obj1[ key ] != obj2[ key ]; // eslint-disable-line eqeqeq
				} );
			};

		params = params || {};

		updatedUri = this.getUpdatedUri();
		updatedUri.extend( params );

		if ( notEquivalent( updatedUri.query, new mw.Uri().query ) ) {
			window.history.pushState( { tag: 'rcfilters' }, document.title, updatedUri.toString() );
		}
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
	 *  or with a string denoting no results.
	 */
	mw.rcfilters.Controller.prototype.fetchChangesList = function () {
		var uri = this.getUpdatedUri(),
			requestId = ++this.requestCounter,
			latestRequest = function () {
				return requestId === this.requestCounter;
			}.bind( this );

		return $.ajax( uri.toString(), { contentType: 'html' } )
			.then(
				// Success
				function ( html ) {
					var $parsed;
					if ( !latestRequest() ) {
						return $.Deferred().reject();
					}

					$parsed = $( $.parseHTML( html ) );

					return {
						// Changes list
						changes: $parsed.find( '.mw-changeslist' ).first().contents(),
						// Fieldset
						fieldset: $parsed.find( 'fieldset.rcoptions' ).first()
					};
				},
				// Failure
				function ( responseObj ) {
					var $parsed;

					if ( !latestRequest() ) {
						return $.Deferred().reject();
					}

					$parsed = $( $.parseHTML( responseObj.responseText ) );

					// Force a resolve state to this promise
					return $.Deferred().resolve( {
						changes: 'NO_RESULTS',
						fieldset: $parsed.find( 'fieldset.rcoptions' ).first()
					} ).promise();
				}
			);
	};

	/**
	 * Update the list of changes and notify the model
	 *
	 * @param {Object} [params] Extra parameters to add to the API call
	 */
	mw.rcfilters.Controller.prototype.updateChangesList = function ( params ) {
		this.updateURL( params );
		this.changesListModel.invalidate();
		this.fetchChangesList()
			.then(
				// Success
				function ( pieces ) {
					var $changesListContent = pieces.changes,
						$fieldset = pieces.fieldset;
					this.changesListModel.update( $changesListContent, $fieldset );
				}.bind( this )
				// Do nothing for failure
			);
	};

	/**
	 * Toggle the highlight feature on and off
	 */
	mw.rcfilters.Controller.prototype.toggleHighlight = function () {
		this.filtersModel.toggleHighlight();
		this.updateURL();

		if ( this.filtersModel.isHighlightEnabled() ) {
			mw.hook( 'RcFilters.highlight.enable' ).fire();
		}
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
		this.trackHighlight( 'set', { name: filterName, color: color } );
	};

	/**
	 * Clear highlight for a filter item
	 *
	 * @param {string} filterName Name of the filter item
	 */
	mw.rcfilters.Controller.prototype.clearHighlightColor = function ( filterName ) {
		this.filtersModel.clearHighlightColor( filterName );
		this.updateURL();
		this.trackHighlight( 'clear', filterName );
	};

	/**
	 * Clear both highlight and selection of a filter
	 *
	 * @param {string} filterName Name of the filter item
	 */
	mw.rcfilters.Controller.prototype.clearFilter = function ( filterName ) {
		var filterItem = this.filtersModel.getItemByName( filterName ),
			isHighlighted = filterItem.isHighlighted();

		if ( filterItem.isSelected() || isHighlighted ) {
			this.filtersModel.clearHighlightColor( filterName );
			this.filtersModel.toggleFilterSelected( filterName, false );
			this.updateChangesList();
			this.filtersModel.reassessFilterInteractions( filterItem );
		}

		if ( isHighlighted ) {
			this.trackHighlight( 'clear', filterName );
		}
	};

	/**
	 * Synchronize the URL with the current state of the filters
	 * without adding an history entry.
	 */
	mw.rcfilters.Controller.prototype.replaceUrl = function () {
		window.history.replaceState(
			{ tag: 'rcfilters' },
			document.title,
			this.getUpdatedUri().toString()
		);
	};

	/**
	 * Track usage of highlight feature
	 *
	 * @param {string} action
	 * @param {array|object|string} filters
	 */
	mw.rcfilters.Controller.prototype.trackHighlight = function ( action, filters ) {
		filters = typeof filters === 'string' ? { name: filters } : filters;
		filters = !Array.isArray( filters ) ? [ filters ] : filters;
		mw.track(
			'event.ChangesListHighlights',
			{
				action: action,
				filters: filters,
				userId: mw.user.getId()
			}
		);
	};
}( mediaWiki, jQuery ) );
