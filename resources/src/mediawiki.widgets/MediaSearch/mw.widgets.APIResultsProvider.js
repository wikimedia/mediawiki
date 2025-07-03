/*!
 * MediaWiki Widgets - APIResultsProvider class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see http://ve.mit-license.org
 */
( function () {

	/**
	 * @classdesc API results provider.
	 *
	 * @class
	 * @mixes OO.EventEmitter
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.APIResultsProvider`.
	 * @param {string} apiurl The URL to the api
	 * @param {Object} [config] Configuration options
	 * @param {number} config.fetchLimit The default number of results to fetch
	 * @param {string} config.lang The language of the API
	 * @param {number} config.offset Initial offset, if relevant, to call results from
	 * @param {Object} config.ajaxSettings The settings for the ajax call
	 * @param {Object} config.staticParams The data parameters that are static and should
	 *  always be sent to the API request, as opposed to user parameters.
	 * @param {Object} config.userParams Initial user parameters to be sent as data to
	 *  the API request. These can change per request, like the search query term
	 *  or sizing parameters for images, etc.
	 */
	mw.widgets.APIResultsProvider = function MwWidgetsAPIResultsProvider( apiurl, config = {} ) {
		this.setAPIurl( apiurl );
		this.setDefaultFetchLimit( config.fetchLimit || 30 );
		this.setLang( config.lang );
		this.setOffset( config.offset || 0 );
		this.setAjaxSettings( config.ajaxSettings || {} );

		this.staticParams = config.staticParams || {};
		this.userParams = config.userParams || {};

		this.toggleDepleted( false );

		// Mixin constructors
		OO.EventEmitter.call( this );
	};

	/* Setup */
	OO.mixinClass( mw.widgets.APIResultsProvider, OO.EventEmitter );

	/* Methods */

	/**
	 * Get results from the source.
	 *
	 * @param {number} howMany Number of results to ask for
	 * @return {jQuery.Promise} Promise that is resolved into an array
	 * of available results, or is rejected if no results are available.
	 */
	mw.widgets.APIResultsProvider.prototype.getResults = function () {
		const deferred = $.Deferred(),
			allParams = Object.assign( {}, this.getStaticParams(), this.getUserParams() );

		const xhr = $.getJSON( this.getAPIurl(), allParams )
			.done( ( data ) => {
				if ( Array.isArray( data ) && data.length ) {
					deferred.resolve( data );
				} else {
					deferred.resolve();
				}
			} );
		return deferred.promise( { abort: xhr.abort } );
	};

	/**
	 * Set API URL.
	 *
	 * @param {string} apiurl API URL
	 */
	mw.widgets.APIResultsProvider.prototype.setAPIurl = function ( apiurl ) {
		this.apiurl = apiurl;
	};

	/**
	 * Get API URL.
	 *
	 * @return {string} API URL
	 */
	mw.widgets.APIResultsProvider.prototype.getAPIurl = function () {
		return this.apiurl;
	};

	/**
	 * Get the static, non-changing data parameters sent to the API.
	 *
	 * @return {Object} Data parameters
	 */
	mw.widgets.APIResultsProvider.prototype.getStaticParams = function () {
		return this.staticParams;
	};

	/**
	 * Get the user-inputted dynamic data parameters sent to the API.
	 *
	 * @return {Object} Data parameters
	 */
	mw.widgets.APIResultsProvider.prototype.getUserParams = function () {
		return this.userParams;
	};

	/**
	 * Set the data parameters sent to the API.
	 *
	 * @param {Object} params User defined data parameters
	 */
	mw.widgets.APIResultsProvider.prototype.setUserParams = function ( params ) {
		// Asymmetrically compare (params is subset of this.userParams)
		if ( !OO.compare( params, this.userParams, true ) ) {
			this.userParams = Object.assign( {}, this.userParams, params );
			this.reset();
		}
	};

	/**
	 * Reset the provider.
	 */
	mw.widgets.APIResultsProvider.prototype.reset = function () {
		// Reset offset
		this.setOffset( 0 );
		// Reset depleted status
		this.toggleDepleted( false );
	};

	/**
	 * Get fetch limit or 'page' size. This is the number
	 * of results per request.
	 *
	 * @return {number} limit
	 */
	mw.widgets.APIResultsProvider.prototype.getDefaultFetchLimit = function () {
		return this.limit;
	};

	/**
	 * Set limit.
	 *
	 * @param {number} limit Default number of results to fetch from the API
	 */
	mw.widgets.APIResultsProvider.prototype.setDefaultFetchLimit = function ( limit ) {
		this.limit = limit;
	};

	/**
	 * Get provider API language.
	 *
	 * @return {string} Provider API language
	 */
	mw.widgets.APIResultsProvider.prototype.getLang = function () {
		return this.lang;
	};

	/**
	 * Set provider API language.
	 *
	 * @param {string} lang Provider API language
	 */
	mw.widgets.APIResultsProvider.prototype.setLang = function ( lang ) {
		this.lang = lang;
	};

	/**
	 * Get result offset.
	 *
	 * @return {number} Offset Results offset for the upcoming request
	 */
	mw.widgets.APIResultsProvider.prototype.getOffset = function () {
		return this.offset;
	};

	/**
	 * Set result offset.
	 *
	 * @param {number} offset Results offset for the upcoming request
	 */
	mw.widgets.APIResultsProvider.prototype.setOffset = function ( offset ) {
		this.offset = offset;
	};

	/**
	 * Check whether the provider is depleted and has no more results
	 * to hand off.
	 *
	 * @return {boolean} The provider is depleted
	 */
	mw.widgets.APIResultsProvider.prototype.isDepleted = function () {
		return this.depleted;
	};

	/**
	 * Toggle depleted state.
	 *
	 * @param {boolean} isDepleted The provider is depleted
	 */
	mw.widgets.APIResultsProvider.prototype.toggleDepleted = function ( isDepleted ) {
		this.depleted = isDepleted !== undefined ? isDepleted : !this.depleted;
	};

	/**
	 * Get the default ajax settings.
	 *
	 * @return {Object} Ajax settings
	 */
	mw.widgets.APIResultsProvider.prototype.getAjaxSettings = function () {
		return this.ajaxSettings;
	};

	/**
	 * Set the default ajax settings.
	 *
	 * @param {Object} settings Ajax settings
	 */
	mw.widgets.APIResultsProvider.prototype.setAjaxSettings = function ( settings ) {
		this.ajaxSettings = settings;
	};
}() );
