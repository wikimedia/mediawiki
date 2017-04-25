( function ( mw, $ ) {
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
		this.baseState = {};
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.Controller );

	/**
	 * Initialize the filter and parameter states
	 *
	 * @param {Array} filterStructure Filter definition and structure for the model
	 */
	mw.rcfilters.Controller.prototype.initialize = function ( filterStructure ) {
		var parsedSavedQueries,
			$changesList = $( '.mw-changeslist' ).first().contents();
		// Initialize the model
		this.filtersModel.initializeFilters( filterStructure );

		this._calculateBaseState();

		try {
			parsedSavedQueries = JSON.parse( mw.user.options.get( 'rcfilters-saved-queries' ) || '{}' );
		} catch ( err ) {
			parsedSavedQueries = {};
		}

		// The queries are saved in a minimized state, but we want to
		// load them in a normalized state. We send it the normalization function
		this.savedQueriesModel.initialize(
			parsedSavedQueries,
			this.getNormalizedFilterList.bind( this )
		);
		this._updateStateBasedOnUrl();

		// Update the changes list with the existing data
		// so it gets processed
		this.changesListModel.update(
			$changesList.length ? $changesList : 'NO_RESULTS',
			$( 'fieldset.rcoptions' ).first()
		);
	};

	/**
	 * Get an object representing the base state of parameters
	 * and highlights. For structure, see #getNormalizedFilterList
	 *
	 * @return {Object} Object representing the base state of
	 *  parameters and highlights
	 */
	mw.rcfilters.Controller.prototype._calculateBaseState = function () {
		var defaultParams = this.filtersModel.getDefaultParams(),
			highlightedItems = {};

		// Prepare highlights
		this.filtersModel.getHighlightedItems().forEach( function ( item ) {
			highlightedItems[ item.getName() ] = '';
		} );
		highlightedItems.highlights = false;

		this.baseState = {
			filters: this.filtersModel.getFiltersFromParameters( defaultParams ),
			highlights: highlightedItems
		};
	};

	/**
	 * Get an object representing the base state of parameters
	 * and highlights. For structure, see #getNormalizedFilterList
	 *
	 * @return {Object} Object representing the base state of
	 *  parameters and highlights
	 */
	mw.rcfilters.Controller.prototype.getBaseState = function () {
		return this.baseState;
	};

	/**
	 * Produce a list of all available parameters, all set to their
	 * base states, and merge them with the given minimal values given.
	 * This produces a normalized list of parameter values that can
	 * be compared with other parameter states across the system.
	 *
	 * @param {Object} [minimalValues] Representation of only filters
	 *  and highlights whose values are different than the base value.
	 *  The expected object must be keyed by 'params' and 'highlights':
	 *  {
	 *     filters: { key: value, key2: value2, ... },
	 *     highlights: { key_color: value, key2_color: value, ... }
	 *  }
	 *  The returned object has the same structure
	 * @return {Object} Normalized object with all parameters and values
	 */
	mw.rcfilters.Controller.prototype.getNormalizedFilterList = function ( minimalValues ) {
		var baseState = this.getBaseState();

		minimalValues = minimalValues || {};

		return $.extend( true, {}, baseState, minimalValues );
	};

	/**
	 * Get an object that holds only the parameters and highlights that have
	 * values different than the base default value.
	 *
	 * This is the reverse of #getNormalizedFilterList, and the object structure
	 * See #getNormalizedFilterList for structure of the parameter and result.
	 *
	 * @param {Object} valuesObject [description]
	 * @return {[type]} [description]
	 */
	mw.rcfilters.Controller.prototype.getMinimalFilterList = function ( valuesObject ) {
		var result = { filters: {}, highlights: {} },
			baseState = this.getBaseState();

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
	 * Reset to default filters
	 */
	mw.rcfilters.Controller.prototype.resetToDefaults = function () {
		this._updateModelState( this._getDefaultParams() );
		this._updateChangesList();
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

		this._updateChangesList();

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

			this._updateChangesList();

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
			this._updateChangesList();
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
		var randomID = ( new Date() ).getTime(),
			highlightedItems = {};

		label = label || mw.msg( 'rcfilters-savedqueries-defaultname' );

		// Prepare highlights
		if ( this.filtersModel.isHighlightEnabled() ) {
			this.filtersModel.getHighlightedItems().forEach( function ( item ) {
				highlightedItems[ item.getName() ] = item.getHighlightColor();
			} );
		}
		highlightedItems.highlights = this.filtersModel.isHighlightEnabled();

		// Add item
		this.savedQueriesModel.addItems( [
			new mw.rcfilters.dm.SavedQueryItemModel(
				randomID,
				label,
				// Data
				this.getMinimalFilterList(
					{
						filters: this.filtersModel.getSelectedState(),
						highlights: highlightedItems,
					}
				)
			)
		] );

		// Save item
		this._saveSavedQuery();
	};

	/**
	 * Remove a saved query
	 *
	 * @param {string} queryID Query id
	 */
	mw.rcfilters.Controller.prototype.removeSavedQuery = function ( queryID ) {
		var query = this.savedQueriesModel.getItemByID( queryID );

		this.savedQueriesModel.removeItems( [ query ] );
		this._saveSavedQuery();
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
		this._saveSavedQuery();
	};

	/**
	 * Set a saved query as default
	 *
	 * @param {string} queryID Query Id. If null is given, default
	 *  query is reset.
	 */
	mw.rcfilters.Controller.prototype.setDefaultSavedQuery = function ( queryID ) {
		this.savedQueriesModel.setDefault( queryID );
		this._saveSavedQuery();
	};

	/**
	 * Load a saved query
	 *
	 * @param {string} queryID Query id
	 */
	mw.rcfilters.Controller.prototype.loadSavedQuery = function ( queryID ) {
		var queryItem = this.savedQueriesModel.getItemByID( queryID ),
			highlights = queryItem.getHighlights();

		// Update model state from filters
		this.filtersModel.toggleFiltersSelected(
			queryItem.getFilters()
		);

		// Update highlight state
		this.filtersModel.toggleHighlight( !!highlights.highlights );
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

		this._updateChangesList();
	};

	mw.rcfilters.Controller.prototype.doesCurrentQueryExist = function () {
		var highlightedItems = {};

		// Prepare highlights of the current query
		if ( this.filtersModel.isHighlightEnabled() ) {
			this.filtersModel.getHighlightedItems().forEach( function ( item ) {
				highlightedItems[ item.getName() ] = item.getHighlightColor();
			} );
		}
		highlightedItems.highlights = this.filtersModel.isHighlightEnabled();

		return this.doesQueryExist(
			{
				filters: this.filtersModel.getSelectedState(),
				highlights: highlightedItems,
			}
		);
	};
	/**
	 * Check whether a given full query (mix of highlights and filters) already exists
	 * as a saved query.
	 *
	 * @param {Object} fullQueryComparison Object representing all filters and highlights to compare
	 * @return {boolean} Query exists
	 */
	mw.rcfilters.Controller.prototype.doesQueryExist = function ( fullQueryComparison ) {
		var controller = this;

console.log( 'doesQueryExist' );
console.log( '> fullQueryComparison', fullQueryComparison );
		return !!this.savedQueriesModel.getItems().some( function ( item ) {
			var state = item.getState(),
				equals = OO.compare(
					item.getData(),
					fullQueryComparison
				);

console.log( '>    ', equals, state.data );
			return equals;
		} );
	};

	/**
	 * Save the state in the user settings
	 */
	mw.rcfilters.Controller.prototype._saveSavedQuery = function () {
		var stringified,
			state = this.savedQueriesModel.getState(),
			controller = this;

		// Minimize before save
		$.each( state.queries, function ( queryID, info ) {
			state.queries[ queryID ].data = controller.getMinimalFilterList( info.data );
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
	 * Update the model state from given the given parameters.
	 *
	 * This is an internal method, and should only be used from inside
	 * the controller.
	 *
	 * @private
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
		this.filtersModel.toggleHighlight( !!parameters.highlight );
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
	 * Update filter state (selection and highlighting) based
	 * on current URL and default values.
	 *
	 * @private
	 */
	mw.rcfilters.Controller.prototype._updateStateBasedOnUrl = function () {
		var defaultSavedQuery,
			uri = new mw.Uri(),
			defaultParams = this._getDefaultParams();

		this._updateModelState( $.extend( {}, defaultParams, uri.query ) );
		this._updateChangesList();
	};

	/**
	 * Get an object representing the default parameter state, whether
	 * it is from the model defaults or from the saved queries.
	 *
	 * @return {Object} Default parameters
	 */
	mw.rcfilters.Controller.prototype._getDefaultParams = function () {
		var defaultParams = {},
			highlightData = {},
			defaultSavedQueryItem = this.savedQueriesModel.getItemByID( this.savedQueriesModel.getDefault() );

		return defaultSavedQueryItem ?
			$.extend( {}, defaultSavedQueryItem.getParams(), defaultSavedQueryItem.getHighlights() ) :
			this.filtersModel.getDefaultParams();
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
	mw.rcfilters.Controller.prototype._updateURL = function ( params ) {
		var updatedUri,
			notEquivalent = function ( obj1, obj2 ) {
				var keys = Object.keys( obj1 ).concat( Object.keys( obj2 ) );
				return keys.some( function ( key ) {
					return obj1[ key ] != obj2[ key ]; // eslint-disable-line eqeqeq
				} );
			};

		params = params || {};

		updatedUri = this._getUpdatedUri();
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
	mw.rcfilters.Controller.prototype._getUpdatedUri = function () {
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
	 * @private
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
	 * Update the list of changes and notify the model
	 *
	 * @param {Object} [params] Extra parameters to add to the API call
	 */
	mw.rcfilters.Controller.prototype._updateChangesList = function ( params ) {
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
