// @ts-check
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
 * @param {URL} newUri New URI to replace
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
 * @param {URL} newUri New URI to push
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
 * @param {URLSearchParams} [uriQuery] URI query
 * @return {number} URL version
 */
UriProcessor.prototype.getVersion = function ( uriQuery ) {
	uriQuery = uriQuery || new URL( location.href ).searchParams;

	return Number( uriQuery.get( 'urlversion' ) || 1 );
};

/**
 * Get an updated URL object based on the model state
 *
 * @param {URL} [url] An external URI to build the new uri
 *  with. This is mainly for tests, to be able to supply external query
 *  parameters and make sure they are retained.
 * @return {URL} Updated Uri
 */
UriProcessor.prototype.getUpdatedUri = function ( url ) {
	const normalizedURL = this._normalizeTargetInUri( url || new URL( location.href ) ),
		unrecognizedParams = this.getUnrecognizedParams( normalizedURL.searchParams );

	let query = this.filtersModel.getMinimizedParamRepresentation(
		$.extend(
			true,
			{},
			this._urlSearchParamsToObject( normalizedURL.searchParams ),
			// The representation must be expanded so it can
			// override the uri query params but we then output
			// a minimized version for the entire URI representation
			// for the method
			this.filtersModel.getExpandedParamRepresentation()
		)
	);
	normalizedURL.search = new URLSearchParams( query ).toString();

	// Reapply unrecognized params and url version
	query = $.extend(
		true,
		{},
		this._urlSearchParamsToObject( normalizedURL.searchParams ),
		this._urlSearchParamsToObject( unrecognizedParams ),
		{ urlversion: '2' }
	);
	normalizedURL.search = new URLSearchParams( query ).toString();

	return normalizedURL;
};

/**
 * Move the subpage to the target parameter
 *
 * @param {URL} url
 * @return {URL}
 * @private
 */
UriProcessor.prototype._normalizeTargetInUri = function ( url ) {
	// matches [/wiki/]SpecialNS:RCL/[Namespace:]Title/Subpage/Subsubpage/etc
	const re = /^((?:\/.+?\/)?.*?:.*?)\/(.*)$/;

	if ( !this.normalizeTarget ) {
		return url;
	}

	// target in title param
	const title = url.searchParams.get( 'title' );
	if ( title ) {
		const titleParts = title.match( re );
		if ( titleParts ) {
			url.searchParams.set( 'title', titleParts[ 1 ] );
			url.searchParams.set( 'target', titleParts[ 2 ] );
		}
	}

	// target in path
	const pathParts = decodeURIComponent( url.pathname.replace( /\+/g, '%20' ) ).match( re );
	if ( pathParts ) {
		url.pathname = pathParts[ 1 ];
		url.searchParams.set( 'target', pathParts[ 2 ] );
	}

	return url;
};

/**
 * Get an object representing given parameters that are unrecognized by the model
 *
 * @param  {URLSearchParams} params Full params object
 * @return {URLSearchParams} Unrecognized params
 */
UriProcessor.prototype.getUnrecognizedParams = function ( params ) {
	// Start with full representation
	const unrecognizedParams = new URLSearchParams( params );

	// Extract unrecognized parameters
	for ( const paramName in this.filtersModel.getEmptyParameterState() ) {
		// Remove recognized params
		if ( params.has( paramName ) ) {
			unrecognizedParams.delete( paramName );
		}
	}

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
	const currentURL = new URL( location.href ),
		updatedURL = this.getUpdatedUri();

	Object.keys( params || {} ).forEach( ( k ) => {
		updatedURL.searchParams.set( k, params[ k ] );
	} );

	if (
		this.getVersion( currentURL.searchParams ) !== 2 ||
		this.isNewState( currentURL.searchParams, updatedURL.searchParams )
	) {
		this.constructor.static.replaceState( updatedURL );
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
 * @param {URLSearchParams} [uriQuery] URI query
 */
UriProcessor.prototype.updateModelBasedOnQuery = function ( uriQuery ) {
	uriQuery = uriQuery || this._normalizeTargetInUri( new URL( location.href ) ).searchParams;
	this.filtersModel.updateStateFromParams(
		this._urlSearchParamsToObject( this._getNormalizedQueryParams( uriQuery ) )
	);
};

/**
 * Compare two URI queries to decide whether they are different
 * enough to represent a new state.
 *
 * @param {URLSearchParams} currentUriQuery Current Uri query
 * @param {URLSearchParams} updatedUriQuery Updated Uri query
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
		this.filtersModel.getMinimizedParamRepresentation( this._urlSearchParamsToObject( currentUriQuery ) ),
		this._urlSearchParamsToObject( this.getUnrecognizedParams( currentUriQuery ) )
	);
	const updatedParamState = $.extend(
		true,
		{},
		this.filtersModel.getMinimizedParamRepresentation( this._urlSearchParamsToObject( updatedUriQuery ) ),
		this._urlSearchParamsToObject( this.getUnrecognizedParams( updatedUriQuery ) )
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
 * @param {URLSearchParams} uriQuery Current URI query
 * @return {URLSearchParams} Normalized parameters
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

	const query = $.extend(
		true,
		{},
		this.filtersModel.getMinimizedParamRepresentation(
			$.extend( true, {}, base, this._urlSearchParamsToObject( uriQuery ) )
		),
		{ urlversion: '2' }
	);
	return new URLSearchParams( query );
};

/**
 * Converts URLSearchParams to object.
 *
 * @param {URLSearchParams} params
 * @return {Object}
 */
UriProcessor.prototype._urlSearchParamsToObject = function ( params ) {
	// ES2019 'Object.fromEntries' method is shorter
	const object = {};
	for ( const [ k, v ] of params.entries() ) {
		object[ k ] = v;
	}
	return object;
};

module.exports = UriProcessor;
