/*!
 * MediaWiki Widgets - APIResultsQueue class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see http://ve.mit-license.org
 */
( function () {

	/**
	 * @classdesc API results queue.
	 *
	 * @class
	 * @mixes OO.EventEmitter
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @param {number} config.limit The default number of results to fetch
	 * @param {number} config.threshold The default number of extra results
	 *  that the queue should always strive to have on top of the
	 *  individual requests for items.
	 */
	mw.widgets.APIResultsQueue = function MwWidgetsAPIResultsQueue( config ) {
		config = config || {};

		this.fileRepoPromise = null;
		this.providers = [];
		this.providerPromises = [];
		this.queue = [];

		this.params = {};

		this.limit = config.limit || 20;
		this.setThreshold( config.threshold || 10 );

		// Mixin constructors
		OO.EventEmitter.call( this );
	};

	/* Setup */
	OO.mixinClass( mw.widgets.APIResultsQueue, OO.EventEmitter );

	/* Methods */

	/**
	 * Set up the queue and its resources.
	 * This should be overridden if there are any setup steps to perform.
	 *
	 * @return {jQuery.Promise} Promise that resolves when the resources
	 *  are set up. Note: The promise must have an .abort() functionality.
	 */
	mw.widgets.APIResultsQueue.prototype.setup = function () {
		return $.Deferred().resolve().promise( { abort: function () {} } );
	};

	/**
	 * Get items from the queue.
	 *
	 * @param {number} [howMany] How many items to retrieve. Defaults to the
	 *  default limit supplied on initialization.
	 * @return {jQuery.Promise} Promise that resolves into an array of items.
	 */
	mw.widgets.APIResultsQueue.prototype.get = function ( howMany ) {
		let fetchingPromise = null;

		howMany = howMany || this.limit;

		// Check if the queue has enough items
		if ( this.queue.length < howMany + this.threshold ) {
			// Call for more results
			fetchingPromise = this.queryProviders( howMany + this.threshold )
				.then( ( items ) => {
					// Add to the queue
					this.queue = this.queue.concat( ...items );
				} );
		}

		return $.when( fetchingPromise )
			.then( () => this.queue.splice( 0, howMany ) );

	};

	/**
	 * Get results from all providers.
	 *
	 * @param {number} [howMany] How many items to retrieve. Defaults to the
	 *  default limit supplied on initialization.
	 * @return {jQuery.Promise} Promise that is resolved into an array
	 *  of fetched items. Note: The promise must have an .abort() functionality.
	 */
	mw.widgets.APIResultsQueue.prototype.queryProviders = function ( howMany ) {
		// Make sure there are resources set up
		return this.setup()
			.then( () => {
				// Abort previous requests
				for ( let i = 0, iLen = this.providerPromises.length; i < iLen; i++ ) {
					this.providerPromises[ i ].abort();
				}
				this.providerPromises = [];
				// Set up the query to all providers
				for ( let j = 0, jLen = this.providers.length; j < jLen; j++ ) {
					if ( !this.providers[ j ].isDepleted() ) {
						this.providerPromises.push(
							this.providers[ j ].getResults( howMany )
						);
					}
				}

				return $.when( ...this.providerPromises )
					.then( Array.prototype.concat.bind( [] ) );
			} );
	};

	/**
	 * Set the search query for all the providers.
	 *
	 * This also makes sure to abort any previous promises.
	 *
	 * @param {Object} params API search parameters
	 */
	mw.widgets.APIResultsQueue.prototype.setParams = function ( params ) {
		if ( !OO.compare( params, this.params, true ) ) {
			this.reset();
			this.params = Object.assign( this.params, params );
			// Reset queue
			this.queue = [];
			// Reset promises
			for ( let i = 0, iLen = this.providerPromises.length; i < iLen; i++ ) {
				this.providerPromises[ i ].abort();
			}
			// Change queries
			for ( let j = 0, jLen = this.providers.length; j < jLen; j++ ) {
				this.providers[ j ].setUserParams( this.params );
			}
		}
	};

	/**
	 * Reset the queue and all its providers.
	 */
	mw.widgets.APIResultsQueue.prototype.reset = function () {
		// Reset queue
		this.queue = [];
		// Reset promises
		for ( let i = 0, iLen = this.providerPromises.length; i < iLen; i++ ) {
			this.providerPromises[ i ].abort();
		}
		// Reset options
		for ( let j = 0, jLen = this.providers.length; j < jLen; j++ ) {
			this.providers[ j ].reset();
		}
	};

	/**
	 * Get the data parameters sent to the API.
	 *
	 * @return {Object} params API search parameters
	 */
	mw.widgets.APIResultsQueue.prototype.getParams = function () {
		return this.params;
	};

	/**
	 * Set the providers.
	 *
	 * @param {mw.widgets.APIResultsProvider[]} providers An array of providers
	 */
	mw.widgets.APIResultsQueue.prototype.setProviders = function ( providers ) {
		this.providers = providers;
		for ( let i = 0, len = this.providers.length; i < len; i++ ) {
			this.providers[ i ].setUserParams( this.params );
			this.providers[ i ].setLang( this.lang );
		}
	};

	/**
	 * Add a provider to the group.
	 *
	 * @param {mw.widgets.APIResultsProvider} provider A provider object
	 */
	mw.widgets.APIResultsQueue.prototype.addProvider = function ( provider ) {
		this.providers.push( provider );
		provider.setUserParams( this.params );
		provider.setLang( this.lang );
	};

	/**
	 * Set the providers.
	 *
	 * @return {mw.widgets.APIResultsProvider[]} providers An array of providers
	 */
	mw.widgets.APIResultsQueue.prototype.getProviders = function () {
		return this.providers;
	};

	/**
	 * Get the queue size.
	 *
	 * @return {number} Queue size
	 */
	mw.widgets.APIResultsQueue.prototype.getQueueSize = function () {
		return this.queue.length;
	};

	/**
	 * Set queue threshold.
	 *
	 * @param {number} threshold Queue threshold, below which we will
	 *  request more items
	 */
	mw.widgets.APIResultsQueue.prototype.setThreshold = function ( threshold ) {
		this.threshold = threshold;
	};

	/**
	 * Get queue threshold.
	 *
	 * @return {number} threshold Queue threshold, below which we will
	 *  request more items
	 */
	mw.widgets.APIResultsQueue.prototype.getThreshold = function () {
		return this.threshold;
	};

	/**
	 * Set language for the query results.
	 *
	 * @param {string|undefined} lang Language
	 */
	mw.widgets.APIResultsQueue.prototype.setLang = function ( lang ) {
		this.lang = lang;
		for ( let i = 0, len = this.providers.length; i < len; i++ ) {
			this.providers[ i ].setLang( this.lang );
		}
	};

	/**
	 * Get language for the query results.
	 *
	 * @return {string|undefined} lang Language
	 */
	mw.widgets.APIResultsQueue.prototype.getLang = function () {
		return this.lang;
	};
}() );
