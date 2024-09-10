/* eslint no-underscore-dangle: "off" */
/**
 * URI Processor for RCFilters.
 *
 * @class UriProcessor
 * @memberof mw.rcfilters
 * @ignore
 * @param {mw.rcfilters.dm.FiltersViewModel} filtersModel Filters view model
 * @param {Object} [config] Configuration object
 * @param {boolean} [config.normalizeTarget] Dictates whether or not to go through the
 *  title normalization to separate title subpage/parts into the target= url
 *  parameter
 */
const UriProcessor = function MwRcfiltersController( filtersModel, config ) {
	config = config || {};
	this.filtersModel = filtersModel;

	this.normalizeTarget = !!config.normalizeTarget;
};

/* Initialization */
OO.initClass( UriProcessor );

/* Static methods */

/**
 * Replace the url history through replaceState
 *
 * @param {mw.Uri} newUri New URI to replace
 */
UriProcessor.static.replaceState = function ( newUri ) {
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
UriProcessor.static.pushState = function ( newUri ) {
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
UriProcessor.prototype.getVersion = function ( uriQuery ) {
	uriQuery = uriQuery || new mw.Uri().query;

	return Number( uriQuery.urlversion || 1 );
};

/**
 * Get an updated mw.Uri object based on the model state
 *
 * @param {mw.Uri} [uri] An external URI to build the new uri
 *  with. This is mainly for tests, to be able to supply external query
 *  parameters and make sure they are retained.
 * @return {mw.Uri} Updated Uri
 */
UriProcessor.prototype.getUpdatedUri = function ( uri ) {
	const normalizedUri = this._normalizeTargetInUri( uri || new mw.Uri() ),
		unrecognizedParams = this.getUnrecognizedParams( normalizedUri.query );

	normalizedUri.query = this.filtersModel.getMinimizedParamRepresentation(
		$.extend(
			true,
			{},
			normalizedUri.query,
			// The representation must be expanded so it can
			// override the uri query params but we then output
			// a minimized version for the entire URI representation
			// for the method
			this.filtersModel.getExpandedParamRepresentation()
		)
	);

	// Reapply unrecognized params and url version
	normalizedUri.query = $.extend(
		true,
		{},
		normalizedUri.query,
		unrecognizedParams,
		{ urlversion: '2' }
	);

	return normalizedUri;
};

/**
 * Move the subpage to the target parameter
 *
 * @param {mw.Uri} uri
 * @return {mw.Uri}
 * @private
 */
UriProcessor.prototype._normalizeTargetInUri = function ( uri ) {
	// matches [/wiki/]SpecialNS:RCL/[Namespace:]Title/Subpage/Subsubpage/etc
	const re = /^((?:\/.+?\/)?.*?:.*?)\/(.*)$/;

	if ( !this.normalizeTarget ) {
		return uri;
	}

	// target in title param
	if ( uri.query.title ) {
		const titleParts = uri.query.title.match( re );
		if ( titleParts ) {
			uri.query.title = titleParts[ 1 ];
			uri.query.target = titleParts[ 2 ];
		}
	}

	// target in path
	const pathParts = mw.Uri.decode( uri.path ).match( re );
	if ( pathParts ) {
		uri.path = pathParts[ 1 ];
		uri.query.target = pathParts[ 2 ];
	}

	return uri;
};

/**
 * Get an object representing given parameters that are unrecognized by the model
 *
 * @param  {Object} params Full params object
 * @return {Object} Unrecognized params
 */
UriProcessor.prototype.getUnrecognizedParams = function ( params ) {
	// Start with full representation
	const givenParamNames = Object.keys( params ),
		unrecognizedParams = $.extend( true, {}, params );

	// Extract unrecognized parameters
	Object.keys( this.filtersModel.getEmptyParameterState() ).forEach( ( paramName ) => {
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
UriProcessor.prototype.updateURL = function ( params ) {
	const currentUri = new mw.Uri(),
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
 * This methods should only be called once on initialization.
 * After initialization, the model updates the URL, not the
 * other way around.
 *
 * @param {Object} [uriQuery] URI query
 */
UriProcessor.prototype.updateModelBasedOnQuery = function ( uriQuery ) {
	uriQuery = uriQuery || this._normalizeTargetInUri( new mw.Uri() ).query;
	this.filtersModel.updateStateFromParams(
		this._getNormalizedQueryParams( uriQuery )
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
UriProcessor.prototype.isNewState = function ( currentUriQuery, updatedUriQuery ) {
	const notEquivalent = function ( obj1, obj2 ) {
		const keys = Object.keys( obj1 ).concat( Object.keys( obj2 ) );
		return keys.some(
			( key ) => obj1[ key ] != obj2[ key ] // eslint-disable-line eqeqeq
		);
	};

	// Compare states instead of parameters
	// This will allow us to always have a proper check of whether
	// the requested new url is one to change or not, regardless of
	// actual parameter visibility/representation in the URL
	const currentParamState = $.extend(
		true,
		{},
		this.filtersModel.getMinimizedParamRepresentation( currentUriQuery ),
		this.getUnrecognizedParams( currentUriQuery )
	);
	const updatedParamState = $.extend(
		true,
		{},
		this.filtersModel.getMinimizedParamRepresentation( updatedUriQuery ),
		this.getUnrecognizedParams( updatedUriQuery )
	);

	return notEquivalent( currentParamState, updatedParamState );
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
UriProcessor.prototype._getNormalizedQueryParams = function ( uriQuery ) {
	// Check whether we are dealing with urlversion=2
	// If we are, we do not merge the initial request with
	// defaults. Not having urlversion=2 means we need to
	// reproduce the server-side request and merge the
	// requested parameters (or starting state) with the
	// wiki default.
	// Any subsequent change of the URL through the RCFilters
	// system will receive 'urlversion=2'
	const base = this.getVersion( uriQuery ) === 2 ?
		{} :
		this.filtersModel.getDefaultParams();

	return $.extend(
		true,
		{},
		this.filtersModel.getMinimizedParamRepresentation(
			$.extend( true, {}, base, uriQuery )
		),
		{ urlversion: '2' }
	);
};

module.exports = UriProcessor;
