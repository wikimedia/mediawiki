( function ( mw, $ ) {
	/* eslint no-underscore-dangle: "off" */
	/**
	 * URI Processor for RCFilters
	 *
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters view model
	 */
	mw.rcfilters.UriProcessor = function MwRcfiltersController( filtersModel ) {
		this.filtersModel = filtersModel;
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
	 * Get an updated mw.Uri object based on the model state
	 *
	 * @param {Object} [uriQuery] An external URI query to build the new uri
	 *  with. This is mainly for tests, to be able to supply external parameters
	 *  and make sure they are retained.
	 * @return {mw.Uri} Updated Uri
	 */
	mw.rcfilters.UriProcessor.prototype.getUpdatedUri = function ( uriQuery ) {
		var uri = new mw.Uri(),
			unrecognizedParams = this.getUnrecognizedParams( uriQuery || uri.query );

		if ( uriQuery ) {
			// This is mainly for tests, to be able to give the method
			// an initial URI Query and test that it retains parameters
			uri.query = uriQuery;
		}

		uri.query = this.filtersModel.getMinimizedParamRepresentation(
			$.extend(
				true,
				{},
				uri.query,
				// The representation must be expanded so it can
				// override the uri query params but we then output
				// a minimized version for the entire URI representation
				// for the method
				this.filtersModel.getExpandedParamRepresentation()
			)
		);

		// Remove excluded params from the url
		uri.query = this.filtersModel.removeExcludedParams( uri.query );

		// Reapply unrecognized params and url version
		uri.query = $.extend( true, {}, uri.query, unrecognizedParams, { urlversion: '2' } );

		return uri;
	};

	/**
	 * Get an object representing given parameters that are unrecognized by the model
	 *
	 * @param  {Object} params Full params object
	 * @return {Object} Unrecognized params
	 */
	mw.rcfilters.UriProcessor.prototype.getUnrecognizedParams = function ( params ) {
		// Start with full representation
		var givenParamNames = Object.keys( params ),
			unrecognizedParams = $.extend( true, {}, params );

		// Extract unrecognized parameters
		Object.keys( this.filtersModel.getEmptyParameterState() ).forEach( function ( paramName ) {
			// Remove recognized params
			if ( givenParamNames.indexOf( paramName ) > -1 ) {
				delete unrecognizedParams[ paramName ];
			}
		} );

		return unrecognizedParams;
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
	mw.rcfilters.UriProcessor.prototype.updateURL = function ( params ) {
		var currentUri = new mw.Uri(),
			updatedUri = this.getUpdatedUri();

		updatedUri.extend( params || {} );

		if (
			this.getVersion( currentUri.query ) !== 2 ||
			this.isNewState( currentUri.query, updatedUri.query )
		) {
			this.constructor.static.replaceState( updatedUri );
		}
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
		this.filtersModel.updateStateFromParams(
			this._getNormalizedQueryParams( uriQuery || new mw.Uri().query )
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
		currentParamState = $.extend(
			true,
			{},
			this.filtersModel.getMinimizedParamRepresentation( currentUriQuery ),
			this.getUnrecognizedParams( currentUriQuery )
		);
		updatedParamState = $.extend(
			true,
			{},
			this.filtersModel.getMinimizedParamRepresentation( updatedUriQuery ),
			this.getUnrecognizedParams( updatedUriQuery )
		);

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
			validParameterNames = Object.keys( this.filtersModel.getEmptyParameterState() );

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
	 * Always merge in the hidden parameter defaults.
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
		var hiddenParamDefaults = this.filtersModel.getDefaultHiddenParams(),
			base = this.getVersion( uriQuery ) === 2 ?
				{} :
				this.filtersModel.getDefaultParams();

		return $.extend(
			true,
			{},
			this.filtersModel.getMinimizedParamRepresentation(
				$.extend( true, {}, hiddenParamDefaults, base, uriQuery )
			),
			{ urlversion: '2' }
		);
	};
}( mediaWiki, jQuery ) );
