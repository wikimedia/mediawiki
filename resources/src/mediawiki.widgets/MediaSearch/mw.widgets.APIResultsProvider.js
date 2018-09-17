/*!
 * MediaWiki Widgets - APIResultsProvider class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see http://ve.mit-license.org
 */
( function () {

	/**
	 * API Results Provider object.
	 *
	 * @class
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 * @param {string} apiurl The URL to the api
	 * @param {Object} [config] Configuration options
	 * @cfg {number} fetchLimit The default number of results to fetch
	 * @cfg {string} lang The language of the API
	 * @cfg {number} offset Initial offset, if relevant, to call results from
	 * @cfg {Object} ajaxSettings The settings for the ajax call
	 * @cfg {Object} staticParams The data parameters that are static and should
	 *  always be sent to the API request, as opposed to user parameters.
	 * @cfg {Object} userParams Initial user parameters to be sent as data to
	 *  the API request. These can change per request, like the search query term
	 *  or sizing parameters for images, etc.
	 */
	mw.widgets.APIResultsProvider = function MwWidgetsAPIResultsProvider( apiurl, config ) {
		config = config || {};

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
	 * Get results from the source
	 *
	 * @param {number} howMany Number of results to ask for
	 * @return {jQuery.Promise} Promise that is resolved into an array
	 * of available results, or is rejected if no results are available.
	 */
	mw.widgets.APIResultsProvider.prototype.getResults = function () {
		var xhr,
			deferred = $.Deferred(),
			allParams = $.extend( {}, this.getStaticParams(), this.getUserParams() );

		xhr = $.getJSON( this.getAPIurl(), allParams )
			.done( function ( data ) {
				if (
					$.type( data ) !== 'array' ||
					(
						$.type( data ) === 'array' &&
						data.length === 0
					)
				) {
					deferred.resolve();
				} else {
					deferred.resolve( data );
				}
			} );
		return deferred.promise( { abort: xhr.abort } );
	};

	/**
	 * Set API url
	 *
	 * @param {string} apiurl API url
	 */
	mw.widgets.APIResultsProvider.prototype.setAPIurl = function ( apiurl ) {
		this.apiurl = apiurl;
	};

	/**
	 * Set api url
	 *
	 * @return {string} API url
	 */
	mw.widgets.APIResultsProvider.prototype.getAPIurl = function () {
		return this.apiurl;
	};

	/**
	 * Get the static, non-changing data parameters sent to the API
	 *
	 * @return {Object} Data parameters
	 */
	mw.widgets.APIResultsProvider.prototype.getStaticParams = function () {
		return this.staticParams;
	};

	/**
	 * Get the user-inputted dynamic data parameters sent to the API
	 *
	 * @return {Object} Data parameters
	 */
	mw.widgets.APIResultsProvider.prototype.getUserParams = function () {
		return this.userParams;
	};

	/**
	 * Set the data parameters sent to the API
	 *
	 * @param {Object} params User defined data parameters
	 */
	mw.widgets.APIResultsProvider.prototype.setUserParams = function ( params ) {
		// Asymmetrically compare (params is subset of this.userParams)
		if ( !OO.compare( params, this.userParams, true ) ) {
			this.userParams = $.extend( {}, this.userParams, params );
			this.reset();
		}
	};

	/**
	 * Reset the provider
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
	 * Set limit
	 *
	 * @param {number} limit Default number of results to fetch from the API
	 */
	mw.widgets.APIResultsProvider.prototype.setDefaultFetchLimit = function ( limit ) {
		this.limit = limit;
	};

	/**
	 * Get provider API language
	 *
	 * @return {string} Provider API language
	 */
	mw.widgets.APIResultsProvider.prototype.getLang = function () {
		return this.lang;
	};

	/**
	 * Set provider API language
	 *
	 * @param {string} lang Provider API language
	 */
	mw.widgets.APIResultsProvider.prototype.setLang = function ( lang ) {
		this.lang = lang;
	};

	/**
	 * Get result offset
	 *
	 * @return {number} Offset Results offset for the upcoming request
	 */
	mw.widgets.APIResultsProvider.prototype.getOffset = function () {
		return this.offset;
	};

	/**
	 * Set result offset
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
	 * Toggle depleted state
	 *
	 * @param {boolean} isDepleted The provider is depleted
	 */
	mw.widgets.APIResultsProvider.prototype.toggleDepleted = function ( isDepleted ) {
		this.depleted = isDepleted !== undefined ? isDepleted : !this.depleted;
	};

	/**
	 * Get the default ajax settings
	 *
	 * @return {Object} Ajax settings
	 */
	mw.widgets.APIResultsProvider.prototype.getAjaxSettings = function () {
		return this.ajaxSettings;
	};

	/**
	 * Get the default ajax settings
	 *
	 * @param {Object} settings Ajax settings
	 */
	mw.widgets.APIResultsProvider.prototype.setAjaxSettings = function ( settings ) {
		this.ajaxSettings = settings;
	};
}() );
