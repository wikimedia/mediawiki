( function ( mw, $ ) {
	/* eslint no-underscore-dangle: "off" */
	/**
	 * URI Processor for RCFilters
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters view model
	 */
	mw.rcfilters.UriProcessor = function MwRcfiltersController( filtersModel ) {
		this.emptyParameterState = {};
		this.filtersModel = filtersModel;

		// Initialize
		this._buildEmptyParameterState();
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.UriProcessor );

	/* Static methods */

	/**
	 * Replace the url history through replaceState
	 *
	 * @param {mw.Uri} newUri New URI to replace
	 */
	mw.rcfilters.UriProcessor.static.replaceState = function ( newUri ) {
		window.history.replaceState(
			{ tag: 'rcfilters' },
			document.title,
			newUri.toString()
		);
	};

	/**
	 * Push the url to history through pushState
	 *
	 * @param {mw.Uri} newUri New URI to push
	 */
	mw.rcfilters.UriProcessor.static.pushState = function ( newUri ) {
		window.history.pushState(
			{ tag: 'rcfilters' },
			document.title,
			newUri.toString()
		);
	};

	/* Methods */

	/**
	 * Get the version that this URL query is tagged with.
	 *
	 * @param {Object} [uriQuery] URI query
	 * @return {number} URL version
	 */
	mw.rcfilters.UriProcessor.prototype.getVersion = function ( uriQuery ) {
		uriQuery = uriQuery || new mw.Uri().query;

		return Number( uriQuery.urlversion || 1 );
	};

	/**
	 * Update the filters model based on the URI query
	 * This happens on initialization, and from this moment on,
	 * we consider the system synchronized, and the model serves
	 * as the source of truth for the URL.
	 *
	 * This methods should only be called once on initialiation.
	 * After initialization, the model updates the URL, not the
	 * other way around.
	 *
	 * @param {Object} uriQuery URI query
	 */
	mw.rcfilters.UriProcessor.prototype.updateModelBasedOnQuery = function ( uriQuery ) {
		var parameters = this._getNormalizedQueryParams( uriQuery || new mw.Uri().query );

		// Update filter states
		this.filtersModel.toggleFiltersSelected(
			this.filtersModel.getFiltersFromParameters(
				parameters
			)
		);

		this.filtersModel.toggleInvertedNamespaces( !!Number( parameters.invert ) );

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
	 * Get an updated mw.Uri object based on the model state
	 *
	 * @param {Object} [currentUriQuery] Current Uri object to manipulate
	 * @return {Object} Updated Uri query
	 */
	mw.rcfilters.UriProcessor.prototype.getUpdatedUriQueryFromModel = function ( currentUriQuery ) {
		var paramsFromModel;

		currentUriQuery = currentUriQuery || new mw.Uri().query;

		paramsFromModel = this._getNormalizedQueryParams(
			$.extend(
				true,
				// We need to decide whether we are merging defaults,
				// but since the model is ALWAYS THE SOURCE OF TRUTH,
				// the defaults must be merged into the parameters
				// from the model, not to the URI query given,
				// because at the end of the method, we override
				// truthy values from the model -- and if those do
				// not include defaults, we null them all (which
				// is not the correct thing to do)
				// So in order to make the correct decision, we
				// need to send the urlversion parameter from the
				// URL but normalize the model parameters
				{ urlversion: currentUriQuery.urlversion },
				this._minimizeQuery( this.getUriParametersFromModel() )
			)
		);

		// Since the model is the source of truth, it should be
		// completely represented (not just its truthy values)
		// Expand it back into full representation with empty state
		paramsFromModel = $.extend( true, {}, this.getEmptyParameterState(), paramsFromModel );

		// We want to still redo the minimization when we merge model params with the
		// initial query, because there may have been a case
		// where model parameters overrode the original (default) parameters with a
		// state that is now part of the base state.
		return this._minimizeQuery(
			$.extend(
				true,
				{},
				currentUriQuery,
				paramsFromModel
			)
		);
	};

	/**
	 * Get parameters representing the current state of the model
	 *
	 * @return {Object} Uri query parameters
	 */
	mw.rcfilters.UriProcessor.prototype.getUriParametersFromModel = function () {
		return $.extend(
			true,
			{},
			this.filtersModel.getParametersFromFilters(),
			this.filtersModel.getHighlightParameters(),
			{
				highlight: String( Number( this.filtersModel.isHighlightEnabled() ) ),
				invert: String( Number( this.filtersModel.areNamespacesInverted() ) )
			}
		);
	};

	/**
	 * Remove all parameters that have the same value as the base state
	 *
	 * @private
	 * @param {Object} uriQuery Current uri query
	 * @return {Object} Minimized query
	 */
	mw.rcfilters.UriProcessor.prototype._minimizeQuery = function ( uriQuery ) {
		var baseParams = this.getEmptyParameterState();

		$.each( uriQuery, function ( paramName, paramValue ) {
			if ( baseParams[ paramName ] === paramValue ) {
				// Remove parameter from query
				delete uriQuery[ paramName ];
			}
		} );

		return uriQuery;
	};
	/**
	 * Compare two URI queries to decide whether they are different
	 * enough to represent a new state.
	 *
	 * @param {Object} currentUriQuery Current Uri query
	 * @param {Object} updatedUriQuery Updated Uri query
	 * @return {boolean} This is a new state
	 */
	mw.rcfilters.UriProcessor.prototype.isNewState = function ( currentUriQuery, updatedUriQuery ) {
		var currentFilterState, updatedFilterState,
			notEquivalent = function ( obj1, obj2 ) {
				var keys = Object.keys( obj1 ).concat( Object.keys( obj2 ) );
				return keys.some( function ( key ) {
					return obj1[ key ] != obj2[ key ]; // eslint-disable-line eqeqeq
				} );
			};

		// Compare states instead of parameters
		// This will allow us to always have a proper check of whether
		// the requested new url is one to change or not, regardless of
		// actual parameter visibility/representation in the URL
		currentFilterState = this._buildFullFilterState( currentUriQuery );
		updatedFilterState = this._buildFullFilterState( updatedUriQuery );

		return notEquivalent( currentFilterState, updatedFilterState );
	};

	/**
	 * Check whether the given query has parameters that are
	 * recognized as parameters we should load the system with
	 *
	 * @param {mw.Uri} [uriQuery] Given URI query
	 * @return {boolean} Query contains valid recognized parameters
	 */
	mw.rcfilters.UriProcessor.prototype.isQueryValidForLoad = function ( uriQuery ) {
		var anyValidInUrl,
			validParameterNames = Object.keys( this.getEmptyParameterState() )
				.filter( function ( param ) {
					// Remove 'highlight' parameter from this check;
					// if it's the only parameter in the URL we still
					// want to consider the URL 'empty' for defaults to load
					return param !== 'highlight';
				} );

		uriQuery = uriQuery || new mw.Uri().query;

		anyValidInUrl = Object.keys( uriQuery ).some( function ( parameter ) {
			return validParameterNames.indexOf( parameter ) > -1;
		} );

		// URL version 2 is allowed to be empty or within nonrecognized params
		return anyValidInUrl || this.getVersion( uriQuery ) === 2;
	};

	/**
	 * Get the adjusted URI params based on the url version
	 * If the urlversion is not 2, the parameters are merged with
	 * the model's defaults.
	 *
	 * @private
	 * @param {Object} uriQuery Current URI query
	 * @return {Object} Normalized parameters
	 */
	mw.rcfilters.UriProcessor.prototype._getNormalizedQueryParams = function ( uriQuery ) {
		// Check whether we are dealing with urlversion=2
		// If we are, we do not merge the initial request with
		// defaults. Not having urlversion=2 means we need to
		// reproduce the server-side request and merge the
		// requested parameters (or starting state) with the
		// wiki default.
		// Any subsequent change of the URL through the RCFilters
		// system will receive 'urlversion=2'
		var base = this.getVersion( uriQuery ) === 2 ?
			{} :
			this.filtersModel.getDefaultParams();

		return this._minimizeQuery(
			$.extend( true, { urlversion: '2' }, base, uriQuery )
		);
	};

	/**
	 * Get the representation of an empty parameter state
	 *
	 * @return {Object} Empty parameter state
	 */
	mw.rcfilters.UriProcessor.prototype.getEmptyParameterState = function () {
		return this.emptyParameterState;
	};

	/**
	 * Build the full filter state based on parameters
	 *
	 * @private
	 * @param {Object} uriQuery Given URI query
	 * @return {Object} Full filter state representing the URI query
	 */
	mw.rcfilters.UriProcessor.prototype._buildFullFilterState = function ( uriQuery ) {
		return $.extend( true,
			{},
			this.filtersModel.getFiltersFromParameters( uriQuery ),
			this.filtersModel.extractHighlightValues( uriQuery ),
			{
				highlight: !!Number( uriQuery.highlight ),
				invert: !!Number( uriQuery.invert )
			}
		);
	};

	/**
	 * Build an empty representation of the parameters, where all parameters
	 * are either set to '0' or '' depending on their type.
	 * This must run during initialization, before highlights are set.
	 *
	 * @private
	 */
	mw.rcfilters.UriProcessor.prototype._buildEmptyParameterState = function () {
		var emptyParams = this.filtersModel.getParametersFromFilters( {} ),
			emptyHighlights = this.filtersModel.getHighlightParameters();

		this.emptyParameterState = $.extend(
			true,
			{},
			emptyParams,
			emptyHighlights,
			{ highlight: '0', invert: '0' }
		);
	};
}( mediaWiki, jQuery ) );
