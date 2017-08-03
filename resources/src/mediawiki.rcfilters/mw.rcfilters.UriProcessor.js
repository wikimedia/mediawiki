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
	 * @param {Object} [uriQuery] URI query
	 */
	mw.rcfilters.UriProcessor.prototype.updateModelBasedOnQuery = function ( uriQuery ) {
		var parameters;

		uriQuery = uriQuery || new mw.Uri().query;

		// For arbitrary numeric single_option values, check the uri and see if it's beyond the limit
		$.each( this.filtersModel.getFilterGroups(), function ( groupName, groupModel ) {
			if (
				groupModel.getType() === 'single_option' &&
				groupModel.isAllowArbitrary()
			) {
				if (
					groupModel.getMaxValue() !== null &&
					uriQuery[ groupName ] > groupModel.getMaxValue()
				) {
					// Change the value to the actual max value
					uriQuery[ groupName ] = String( groupModel.getMaxValue() );
				} else if (
					groupModel.getMinValue() !== null &&
					uriQuery[ groupName ] < groupModel.getMinValue()
				) {
					// Change the value to the actual min value
					uriQuery[ groupName ] = String( groupModel.getMinValue() );
				}
			}
		} );

		// Normalize
		parameters = this._getNormalizedQueryParams( uriQuery );

		// Update filter states
		this.filtersModel.toggleFiltersSelected(
			this.filtersModel.getFiltersFromParameters(
				parameters
			)
		);

		this.filtersModel.toggleInvertedNamespaces( !!Number( parameters.invert ) );

		// Update highlight state
		this.filtersModel.getItems().forEach( function ( filterItem ) {
			var color = parameters[ filterItem.getName() + '_color' ];
			if ( color ) {
				filterItem.setHighlightColor( color );
			} else {
				filterItem.clearHighlightColor();
			}
		} );
		this.filtersModel.toggleHighlight( !!Number( parameters.highlight ) );

		// Check all filter interactions
		this.filtersModel.reassessFilterInteractions();
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
	 * Build the full parameter representation based on given query parameters
	 *
	 * @private
	 * @param {Object} uriQuery Given URI query
	 * @return {Object} Full parameter state representing the URI query
	 */
	mw.rcfilters.UriProcessor.prototype._expandModelParameters = function ( uriQuery ) {
		var filterRepresentation = this.filtersModel.getFiltersFromParameters( uriQuery );

		return $.extend( true,
			{},
			uriQuery,
			this.filtersModel.getParametersFromFilters( filterRepresentation ),
			this.filtersModel.extractHighlightValues( uriQuery ),
			{
				highlight: String( Number( uriQuery.highlight ) ),
				invert: String( Number( uriQuery.invert ) )
			}
		);
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
		var currentParamState, updatedParamState,
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
		currentParamState = this._expandModelParameters( currentUriQuery );
		updatedParamState = this._expandModelParameters( updatedUriQuery );

		return notEquivalent( currentParamState, updatedParamState );
	};

	/**
	 * Check whether the given query has parameters that are
	 * recognized as parameters we should load the system with
	 *
	 * @param {mw.Uri} [uriQuery] Given URI query
	 * @return {boolean} Query contains valid recognized parameters
	 */
	mw.rcfilters.UriProcessor.prototype.doesQueryContainRecognizedParams = function ( uriQuery ) {
		var anyValidInUrl,
			validParameterNames = Object.keys( this._getEmptyParameterState() )
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
	 * Remove all parameters that have the same value as the base state
	 * This method expects uri queries of the urlversion=2 format
	 *
	 * @private
	 * @param {Object} uriQuery Current uri query
	 * @return {Object} Minimized query
	 */
	mw.rcfilters.UriProcessor.prototype.minimizeQuery = function ( uriQuery ) {
		var baseParams = this._getEmptyParameterState(),
			uriResult = $.extend( true, {}, uriQuery );

		$.each( uriResult, function ( paramName, paramValue ) {
			if (
				baseParams[ paramName ] !== undefined &&
				baseParams[ paramName ] === paramValue
			) {
				// Remove parameter from query
				delete uriResult[ paramName ];
			}
		} );

		return uriResult;
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
		var hiddenParamDefaults = {},
			base = this.getVersion( uriQuery ) === 2 ?
				{} :
				this.filtersModel.getDefaultParams();

		// Go over the model and get all hidden parameters' defaults
		// These defaults should be applied regardless of the urlversion
		// but be overridden by the URL params if they exist
		$.each( this.filtersModel.getFilterGroups(), function ( groupName, groupModel ) {
			if ( groupModel.isHidden() ) {
				$.extend( true, hiddenParamDefaults, groupModel.getDefaultParams() );
			}
		} );

		return this.minimizeQuery(
			$.extend( true, {}, hiddenParamDefaults, base, uriQuery, { urlversion: '2' } )
		);
	};

	/**
	 * Get the representation of an empty parameter state
	 *
	 * @private
	 * @return {Object} Empty parameter state
	 */
	mw.rcfilters.UriProcessor.prototype._getEmptyParameterState = function () {
		// Override empty parameter state with the sticky parameter values
		return $.extend( true, {}, this.emptyParameterState, this.filtersModel.getStickyParams() );
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
