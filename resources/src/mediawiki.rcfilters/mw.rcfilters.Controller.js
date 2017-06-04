( function ( mw, $ ) {
	/* eslint no-underscore-dangle: "off" */
	/**
	 * Controller for the filters in Recent Changes
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters view model
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel Changes list view model
	 * @param {mw.rcfilters.dm.SavedQueriesModel} savedQueriesModel Saved queries model
	 */
	mw.rcfilters.Controller = function MwRcfiltersController( filtersModel, changesListModel, savedQueriesModel ) {
		this.filtersModel = filtersModel;
		this.changesListModel = changesListModel;
		this.savedQueriesModel = savedQueriesModel;
		this.requestCounter = 0;
		this.baseFilterState = {};
		this.emptyParameterState = {};
		this.initializing = false;
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Initialize the filter and parameter states
	 *
	 * @param {Array} filterStructure Filter definition and structure for the model
	 */
	mw.rcfilters.Controller.prototype.initialize = function ( filterStructure ) {
		var parsedSavedQueries, validParameterNames, baseParams,
			useDataFromServer = false,
			uri = new mw.Uri(),
			$changesList = $( '.mw-changeslist' ).first().contents();

		// Initialize the model
		this.filtersModel.initializeFilters( filterStructure );

		this._buildBaseFilterState();
		this._buildEmptyParameterState();
		validParameterNames = Object.keys( this._getEmptyParameterState() )
			.filter( function ( param ) {
				// Remove 'highlight' parameter from this check;
				// if it's the only parameter in the URL we still
				// want to consider the URL 'empty' for defaults to load
				return param !== 'highlight';
			} );

		try {
			parsedSavedQueries = JSON.parse( mw.user.options.get( 'rcfilters-saved-queries' ) || '{}' );
		} catch ( err ) {
			parsedSavedQueries = {};
		}

		// The queries are saved in a minimized state, so we need
		// to send over the base state so the saved queries model
		// can normalize them per each query item
		this.savedQueriesModel.initialize(
			parsedSavedQueries,
			this._getBaseFilterState()
		);

		// Check whether we need to load defaults.
		// We do this by checking whether the current URI query
		// contains any parameters recognized by the system.
		// If it does, we load the given state.
		// If it doesn't, we have no values at all, and we assume
		// the user loads the base-page and we load defaults.
		// Defaults should only be applied on load (if necessary)
		// or on request
		this.initializing = true;
		if (
			Object.keys( uri.query ).some( function ( parameter ) {
				return validParameterNames.indexOf( parameter ) > -1;
			} )
		) {
			// There are parameters in the url, update model state
			this.updateStateBasedOnUrl();
			useDataFromServer = true;
		} else {
			// No valid parameters are given, load defaults
			if ( this.savedQueriesModel.getDefault() ) {
				// We have defaults from a saved query.
				// We will load them straight-forward (as if
				// they were clicked in the menu) so we trigger
				// a full ajax request and change of URL
				this.applySavedQuery( this.savedQueriesModel.getDefault() );
				useDataFromServer = false;
			} else {
				baseParams = this.filtersModel.getDefaultParams();
				if ( uri.query.urlversion === '2' ) {
					baseParams = {};
				}

				this._updateModelState(
					$.extend(
						true,
						baseParams,
						// We've ignored the highlight parameter for
						// the sake of seeing whether we need to apply defaults - but
						// while we do load the defaults, we still want to retain
						// the actual value given in the URL for it on top of the
						// defaults
						{
							highlight: String( Number( uri.query.highlight ) )
						}
					)
				);
				// In this case, there's no need to re-request the AJAX call
				// the initial data will be processed because we are getting
				// exactly what the server produced for us
				useDataFromServer = true;
			}
		}

		if ( useDataFromServer ) {
			// Update the changes list with the existing data
			// so it gets processed
			this.changesListModel.update(
				$changesList.length ? $changesList : 'NO_RESULTS',
				$( 'fieldset.rcoptions' ).first()
			);
		}
		this.initializing = false;
	};

	/**
	 * Reset to default filters
	 */
	mw.rcfilters.Controller.prototype.resetToDefaults = function () {
		this._updateModelState( $.extend( true, { highlight: '0' }, this._getDefaultParams() ) );
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
			this._trackHighlight( 'clearAll', highlightedFilterNames );
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
			this._trackHighlight( 'clear', filterName );
		}
	};

	/**
	 * Toggle the highlight feature on and off
	 */
	mw.rcfilters.Controller.prototype.toggleHighlight = function () {
		this.filtersModel.toggleHighlight();
		this._updateURL();

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
		this._updateURL();
		this._trackHighlight( 'set', { name: filterName, color: color } );
	};

	/**
	 * Clear highlight for a filter item
	 *
	 * @param {string} filterName Name of the filter item
	 */
	mw.rcfilters.Controller.prototype.clearHighlightColor = function ( filterName ) {
		this.filtersModel.clearHighlightColor( filterName );
		this._updateURL();
		this._trackHighlight( 'clear', filterName );
	};

	/**
	 * Save the current model state as a saved query
	 *
	 * @param {string} [label] Label of the saved query
	 */
	mw.rcfilters.Controller.prototype.saveCurrentQuery = function ( label ) {
		var highlightedItems = {},
			highlightEnabled = this.filtersModel.isHighlightEnabled();

		// Prepare highlights
		this.filtersModel.getHighlightedItems().forEach( function ( item ) {
			highlightedItems[ item.getName() ] = highlightEnabled ?
				item.getHighlightColor() : null;
		} );
		// These are filter states; highlight is stored as boolean
		highlightedItems.highlight = this.filtersModel.isHighlightEnabled();

		// Add item
		this.savedQueriesModel.addNewQuery(
			label || mw.msg( 'rcfilters-savedqueries-defaultlabel' ),
			{
				filters: this.filtersModel.getSelectedState(),
				highlights: highlightedItems
			}
		);

		// Save item
		this._saveSavedQueries();
	};

	/**
	 * Remove a saved query
	 *
	 * @param {string} queryID Query id
	 */
	mw.rcfilters.Controller.prototype.removeSavedQuery = function ( queryID ) {
		var query = this.savedQueriesModel.getItemByID( queryID );

		this.savedQueriesModel.removeItems( [ query ] );

		// Check if this item was the default
		if ( this.savedQueriesModel.getDefault() === queryID ) {
			// Nulify the default
			this.savedQueriesModel.setDefault( null );
		}
		this._saveSavedQueries();
	};

	/**
	 * Rename a saved query
	 *
	 * @param {string} queryID Query id
	 * @param {string} newLabel New label for the query
	 */
	mw.rcfilters.Controller.prototype.renameSavedQuery = function ( queryID, newLabel ) {
		var queryItem = this.savedQueriesModel.getItemByID( queryID );

		if ( queryItem ) {
			queryItem.updateLabel( newLabel );
		}
		this._saveSavedQueries();
	};

	/**
	 * Set a saved query as default
	 *
	 * @param {string} queryID Query Id. If null is given, default
	 *  query is reset.
	 */
	mw.rcfilters.Controller.prototype.setDefaultSavedQuery = function ( queryID ) {
		this.savedQueriesModel.setDefault( queryID );
		this._saveSavedQueries();
	};

	/**
	 * Load a saved query
	 *
	 * @param {string} queryID Query id
	 */
	mw.rcfilters.Controller.prototype.applySavedQuery = function ( queryID ) {
		var data, highlights,
			queryItem = this.savedQueriesModel.getItemByID( queryID );

		if ( queryItem ) {
			data = queryItem.getData();
			highlights = data.highlights;

			// Backwards compatibility; initial version mispelled 'highlight' with 'highlights'
			highlights.highlight = highlights.highlights || highlights.highlight;

			// Update model state from filters
			this.filtersModel.toggleFiltersSelected( data.filters );

			// Update highlight state
			this.filtersModel.toggleHighlight( !!Number( highlights.highlight ) );
			this.filtersModel.getItems().forEach( function ( filterItem ) {
				var color = highlights[ filterItem.getName() ];
				if ( color ) {
					filterItem.setHighlightColor( color );
				} else {
					filterItem.clearHighlightColor();
				}
			} );

			// Check all filter interactions
			this.filtersModel.reassessFilterInteractions();

			this.updateChangesList();
		}
	};

	/**
	 * Check whether the current filter and highlight state exists
	 * in the saved queries model.
	 *
	 * @return {boolean} Query exists
	 */
	mw.rcfilters.Controller.prototype.findQueryMatchingCurrentState = function () {
		var highlightedItems = {};

		// Prepare highlights of the current query
		this.filtersModel.getItemsSupportingHighlights().forEach( function ( item ) {
			highlightedItems[ item.getName() ] = item.getHighlightColor();
		} );
		highlightedItems.highlight = this.filtersModel.isHighlightEnabled();

		return this.savedQueriesModel.findMatchingQuery(
			{
				filters: this.filtersModel.getSelectedState(),
				highlights: highlightedItems
			}
		);
	};

	/**
	 * Get an object representing the base state of parameters
	 * and highlights.
	 *
	 * This is meant to make sure that the saved queries that are
	 * in memory are always the same structure as what we would get
	 * by calling the current model's "getSelectedState" and by checking
	 * highlight items.
	 *
	 * In cases where a user saved a query when the system had a certain
	 * set of filters, and then a filter was added to the system, we want
	 * to make sure that the stored queries can still be comparable to
	 * the current state, which means that we need the base state for
	 * two operations:
	 *
	 * - Saved queries are stored in "minimal" view (only changed filters
	 *   are stored); When we initialize the system, we merge each minimal
	 *   query with the base state (using 'getNormalizedFilters') so all
	 *   saved queries have the exact same structure as what we would get
	 *   by checking the getSelectedState of the filter.
	 * - When we save the queries, we minimize the object to only represent
	 *   whatever has actually changed, rather than store the entire
	 *   object. To check what actually is different so we can store it,
	 *   we need to obtain a base state to compare against, this is
	 *   what #_getMinimalFilterList does
	 */
	mw.rcfilters.Controller.prototype._buildBaseFilterState = function () {
		var defaultParams = this.filtersModel.getDefaultParams(),
			highlightedItems = {};

		// Prepare highlights
		this.filtersModel.getItemsSupportingHighlights().forEach( function ( item ) {
			highlightedItems[ item.getName() ] = null;
		} );
		highlightedItems.highlight = false;

		this.baseFilterState = {
			filters: this.filtersModel.getFiltersFromParameters( defaultParams ),
			highlights: highlightedItems
		};
	};

	/**
	 * Build an empty representation of the parameters, where all parameters
	 * are either set to '0' or '' depending on their type.
	 * This must run during initialization, before highlights are set.
	 */
	mw.rcfilters.Controller.prototype._buildEmptyParameterState = function () {
		var emptyParams = this.filtersModel.getParametersFromFilters( {} ),
			emptyHighlights = this.filtersModel.getHighlightParameters();

		this.emptyParameterState = $.extend(
			true,
			{},
			emptyParams,
			emptyHighlights,
			{ highlight: '0' }
		);
	};

	/**
	 * Get an object representing the base filter state of both
	 * filters and highlights. The structure is similar to what we use
	 * to store each query in the saved queries object:
	 * {
	 *    filters: {
	 *        filterName: (bool)
	 *    },
	 *    highlights: {
	 *        filterName: (string|null)
	 *    }
	 * }
	 *
	 * @return {Object} Object representing the base state of
	 *  parameters and highlights
	 */
	mw.rcfilters.Controller.prototype._getBaseFilterState = function () {
		return this.baseFilterState;
	};

	/**
	 * Get an object representing the base state of parameters
	 * and highlights. The structure is similar to what we use
	 * to store each query in the saved queries object:
	 * {
	 *    param1: "value",
	 *    param2: "value1|value2"
	 * }
	 *
	 * @return {Object} Object representing the base state of
	 *  parameters and highlights
	 */
	mw.rcfilters.Controller.prototype._getEmptyParameterState = function () {
		return this.emptyParameterState;
	};

	/**
	 * Get an object that holds only the parameters and highlights that have
	 * values different than the base default value.
	 *
	 * This is the reverse of the normalization we do initially on loading and
	 * initializing the saved queries model.
	 *
	 * @param {Object} valuesObject Object representing the state of both
	 *  filters and highlights in its normalized version, to be minimized.
	 * @return {Object} Minimal filters and highlights list
	 */
	mw.rcfilters.Controller.prototype._getMinimalFilterList = function ( valuesObject ) {
		var result = { filters: {}, highlights: {} },
			baseState = this._getBaseFilterState();

		// XOR results
		$.each( valuesObject.filters, function ( name, value ) {
			if ( baseState.filters !== undefined && baseState.filters[ name ] !== value ) {
				result.filters[ name ] = value;
			}
		} );

		$.each( valuesObject.highlights, function ( name, value ) {
			if ( baseState.highlights !== undefined && baseState.highlights[ name ] !== value ) {
				result.highlights[ name ] = value;
			}
		} );

		return result;
	};

	/**
	 * Save the current state of the saved queries model with all
	 * query item representation in the user settings.
	 */
	mw.rcfilters.Controller.prototype._saveSavedQueries = function () {
		var stringified,
			state = this.savedQueriesModel.getState(),
			controller = this;

		// Minimize before save
		$.each( state.queries, function ( queryID, info ) {
			state.queries[ queryID ].data = controller._getMinimalFilterList( info.data );
		} );

		// Stringify state
		stringified = JSON.stringify( state );

		if ( stringified.length > 65535 ) {
			// Sanity check, since the preference can only hold that.
			return;
		}

		// Save the preference
		new mw.Api().saveOption( 'rcfilters-saved-queries', stringified );
		// Update the preference for this session
		mw.user.options.set( 'rcfilters-saved-queries', stringified );
	};

	/**
	 * Synchronize the URL with the current state of the filters
	 * without adding an history entry.
	 */
	mw.rcfilters.Controller.prototype.replaceUrl = function () {
		window.history.replaceState(
			{ tag: 'rcfilters' },
			document.title,
			this._getUpdatedUri().toString()
		);
	};

	/**
	 * Update filter state (selection and highlighting) based
	 * on current URL values.
	 */
	mw.rcfilters.Controller.prototype.updateStateBasedOnUrl = function () {
		var uri = new mw.Uri(),
			base = this.filtersModel.getDefaultParams();

		// Check whether we are dealing with urlversion=2
		// If we are, we do not merge the initial request with
		// defaults. Not having urlversion=2 means we need to
		// reproduce the server-side request and merge the
		// requested parameters (or starting state) with the
		// wiki default.
		// Any subsequent change of the URL through the RCFilters
		// system will receive 'urlversion=2'
		if ( uri.query.urlversion === '2' ) {
			base = {};
		}

		this._updateModelState( $.extend( true, {}, base, uri.query ) );
		this.updateChangesList();
	};

	/**
	 * Update the list of changes and notify the model
	 *
	 * @param {Object} [params] Extra parameters to add to the API call
	 */
	mw.rcfilters.Controller.prototype.updateChangesList = function ( params ) {
		this._updateURL( params );
		this.changesListModel.invalidate();
		this._fetchChangesList()
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
	 * Update the model state from given the given parameters.
	 *
	 * This is an internal method, and should only be used from inside
	 * the controller.
	 *
	 * @param {Object} parameters Object representing the parameters for
	 *  filters and highlights
	 */
	mw.rcfilters.Controller.prototype._updateModelState = function ( parameters ) {
		// Update filter states
		this.filtersModel.toggleFiltersSelected(
			this.filtersModel.getFiltersFromParameters(
				parameters
			)
		);

		// Update highlight state
		this.filtersModel.toggleHighlight( !!Number( parameters.highlight ) );
		this.filtersModel.getItems().forEach( function ( filterItem ) {
			var color = parameters[ filterItem.getName() + '_color' ];
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
	 * Get an object representing the default parameter state, whether
	 * it is from the model defaults or from the saved queries.
	 *
	 * @return {Object} Default parameters
	 */
	mw.rcfilters.Controller.prototype._getDefaultParams = function () {
		var data, queryHighlights,
			savedParams = {},
			savedHighlights = {},
			defaultSavedQueryItem = this.savedQueriesModel.getItemByID( this.savedQueriesModel.getDefault() );

		if ( mw.config.get( 'wgStructuredChangeFiltersEnableSaving' ) &&
			defaultSavedQueryItem ) {

			data = defaultSavedQueryItem.getData();

			queryHighlights = data.highlights || {};
			savedParams = this.filtersModel.getParametersFromFilters( data.filters || {} );

			// Translate highlights to parameters
			savedHighlights.highlight = String( Number( queryHighlights.highlight ) );
			$.each( queryHighlights, function ( filterName, color ) {
				if ( filterName !== 'highlights' ) {
					savedHighlights[ filterName + '_color' ] = color;
				}
			} );

			return $.extend( true, {}, savedParams, savedHighlights );
		}

		return this.filtersModel.getDefaultParams();
	};

	/**
	 * Update the URL of the page to reflect current filters
	 *
	 * This should not be called directly from outside the controller.
	 * If an action requires changing the URL, it should either use the
	 * highlighting actions below, or call #updateChangesList which does
	 * the uri corrections already.
	 *
	 * @param {Object} [params] Extra parameters to add to the API call
	 */
	mw.rcfilters.Controller.prototype._updateURL = function ( params ) {
		var currentFilterState, updatedFilterState, updatedUri,
			uri = new mw.Uri(),
			notEquivalent = function ( obj1, obj2 ) {
				var keys = Object.keys( obj1 ).concat( Object.keys( obj2 ) );
				return keys.some( function ( key ) {
					return obj1[ key ] != obj2[ key ]; // eslint-disable-line eqeqeq
				} );
			};

		params = params || {};

		updatedUri = this._getUpdatedUri();
		updatedUri.extend( params );

		// Compare states instead of parameters
		// This will allow us to always have a proper check of whether
		// the requested new url is one to change or not, regardless of
		// actual parameter visibility/representation in the URL
		currentFilterState = this.filtersModel.getFiltersFromParameters( uri.query );
		updatedFilterState = this.filtersModel.getFiltersFromParameters( updatedUri.query );
		// Include highlight states
		$.extend( true,
			currentFilterState,
			this.filtersModel.extractHighlightValues( uri.query ),
			{ highlight: !!Number( uri.query.highlight ) }
		);
		$.extend( true,
			updatedFilterState,
			this.filtersModel.extractHighlightValues( updatedUri.query ),
			{ highlight: !!Number( updatedUri.query.highlight ) }
		);

		if (
			uri.query.urlversion !== '2' ||
			notEquivalent( currentFilterState, updatedFilterState )
		) {
			if ( this.initializing ) {
				// Initially, when we just build the first page load
				// out of defaults, we want to replace the history
				window.history.replaceState( { tag: 'rcfilters' }, document.title, updatedUri.toString() );
			} else {
				window.history.pushState( { tag: 'rcfilters' }, document.title, updatedUri.toString() );
			}
		}
	};

	/**
	 * Get an updated mw.Uri object based on the model state
	 *
	 * @return {mw.Uri} Updated Uri
	 */
	mw.rcfilters.Controller.prototype._getUpdatedUri = function () {
		var uri = new mw.Uri(),
			highlightParams = this.filtersModel.getHighlightParameters(),
			modelParameters = this.filtersModel.getParametersFromFilters(),
			baseParams = this._getEmptyParameterState();

		// Minimize values of the model parameters; show only the values that
		// are non-zero. We assume that all parameters that are not literally
		// showing in the URL are set to zero or empty
		$.each( modelParameters, function ( paramName, value ) {
			if ( baseParams[ paramName ] !== value ) {
				uri.query[ paramName ] = value;
			} else {
				// We need to remove this value from the url
				delete uri.query[ paramName ];
			}
		} );

		// highlight params
		$.each( highlightParams, function ( paramName, value ) {
			// Only output if it is different than the base parameters
			if ( baseParams[ paramName ] !== value ) {
				uri.query[ paramName ] = value;
			} else {
				delete uri.query[ paramName ];
			}
		} );

		if ( this.filtersModel.isHighlightEnabled() ) {
			uri.query.highlight = '1';
		} else {
			delete uri.query.highlight;
		}

		// Add the urlversion=2 param for all URLs made by the RCFilters system
		uri.query.urlversion = '2';

		return uri;
	};

	/**
	 * Fetch the list of changes from the server for the current filters
	 *
	 * @return {jQuery.Promise} Promise object that will resolve with the changes list
	 *  or with a string denoting no results.
	 */
	mw.rcfilters.Controller.prototype._fetchChangesList = function () {
		var uri = this._getUpdatedUri(),
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
	 * Track usage of highlight feature
	 *
	 * @param {string} action
	 * @param {array|object|string} filters
	 */
	mw.rcfilters.Controller.prototype._trackHighlight = function ( action, filters ) {
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
