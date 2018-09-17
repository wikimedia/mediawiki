/*!
 * MediaWiki Widgets - MediaResourceProvider class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * MediaWiki media resource provider.
	 *
	 * @class
	 * @extends mw.widgets.APIResultsProvider
	 *
	 * @constructor
	 * @param {string} apiurl The API url
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [scriptDirUrl] The url of the API script
	 */
	mw.widgets.MediaResourceProvider = function MwWidgetsMediaResourceProvider( apiurl, config ) {
		config = config || {};

		// Parent constructor
		mw.widgets.MediaResourceProvider.super.call( this, apiurl, config );

		// Fetching configuration
		this.scriptDirUrl = config.scriptDirUrl;
		this.isLocal = config.local !== undefined;

		if ( this.isLocal ) {
			this.setAPIurl( mw.util.wikiScript( 'api' ) );
		} else {
			// If 'apiurl' is set, use that. Otherwise, build the url
			// from scriptDirUrl and /api.php suffix
			this.setAPIurl( this.getAPIurl() || ( this.scriptDirUrl + '/api.php' ) );
		}

		this.siteInfoPromise = null;
		this.thumbSizes = [];
		this.imageSizes = [];
	};

	/* Inheritance */
	OO.inheritClass( mw.widgets.MediaResourceProvider, mw.widgets.APIResultsProvider );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.MediaResourceProvider.prototype.getStaticParams = function () {
		return $.extend(
			{},
			// Parent method
			mw.widgets.MediaResourceProvider.super.prototype.getStaticParams.call( this ),
			{
				action: 'query',
				iiprop: 'dimensions|url|mediatype|extmetadata|timestamp|user',
				iiextmetadatalanguage: this.getLang(),
				prop: 'imageinfo'
			}
		);
	};

	/**
	 * Initialize the source and get the site info.
	 *
	 * Connect to the api url and retrieve the siteinfo parameters
	 * that are required for fetching results.
	 *
	 * @return {jQuery.Promise} Promise that resolves when the class
	 * properties are set.
	 */
	mw.widgets.MediaResourceProvider.prototype.loadSiteInfo = function () {
		var provider = this;

		if ( !this.siteInfoPromise ) {
			this.siteInfoPromise = new mw.Api().get( {
				action: 'query',
				meta: 'siteinfo'
			} )
				.then( function ( data ) {
					provider.setImageSizes( data.query.general.imagelimits || [] );
					provider.setThumbSizes( data.query.general.thumblimits || [] );
					provider.setUserParams( {
						// Standard width per resource
						iiurlwidth: provider.getStandardWidth()
					} );
				} );
		}
		return this.siteInfoPromise;
	};

	/**
	 * Override parent method and get results from the source
	 *
	 * @param {number} [howMany] The number of items to pull from the API
	 * @return {jQuery.Promise} Promise that is resolved into an array
	 * of available results, or is rejected if no results are available.
	 */
	mw.widgets.MediaResourceProvider.prototype.getResults = function ( howMany ) {
		var xhr,
			aborted = false,
			provider = this;

		return this.loadSiteInfo()
			.then( function () {
				if ( aborted ) {
					return $.Deferred().reject();
				}
				xhr = provider.fetchAPIresults( howMany );
				return xhr;
			} )
			.then(
				function ( results ) {
					if ( !results || results.length === 0 ) {
						provider.toggleDepleted( true );
						return [];
					}
					return results;
				},
				// Process failed, return an empty promise
				function () {
					provider.toggleDepleted( true );
					return $.Deferred().resolve( [] );
				}
			)
			.promise( { abort: function () {
				aborted = true;
				if ( xhr ) {
					xhr.abort();
				}
			} } );
	};

	/**
	 * Get continuation API data
	 *
	 * @param {number} howMany The number of results to retrieve
	 * @return {Object} API request data
	 */
	mw.widgets.MediaResourceProvider.prototype.getContinueData = function () {
		return {};
	};

	/**
	 * Set continuation data for the next page
	 *
	 * @param {Object} continueData Continuation data
	 */
	mw.widgets.MediaResourceProvider.prototype.setContinue = function () {
	};

	/**
	 * Sort the results
	 *
	 * @param {Object[]} results API results
	 * @return {Object[]} Sorted results
	 */
	mw.widgets.MediaResourceProvider.prototype.sort = function ( results ) {
		return results;
	};

	/**
	 * Call the API for search results.
	 *
	 * @param {number} howMany The number of results to retrieve
	 * @return {jQuery.Promise} Promise that resolves with an array of objects that contain
	 *  the fetched data.
	 */
	mw.widgets.MediaResourceProvider.prototype.fetchAPIresults = function ( howMany ) {
		var xhr, api,
			provider = this;

		if ( !this.isValid() ) {
			return $.Deferred().reject().promise( { abort: $.noop } );
		}

		api = this.isLocal ? new mw.Api() : new mw.ForeignApi( this.getAPIurl(), { anonymous: true } );
		xhr = api.get( $.extend( {}, this.getStaticParams(), this.getUserParams(), this.getContinueData( howMany ) ) );
		return xhr
			.then( function ( data ) {
				var page, newObj, raw,
					results = [];

				if ( data.error ) {
					provider.toggleDepleted( true );
					return [];
				}

				if ( data.continue ) {
					// Update the offset for next time
					provider.setContinue( data.continue );
				} else {
					// This is the last available set of results. Mark as depleted!
					provider.toggleDepleted( true );
				}

				// If the source returned no results, it will not have a
				// query property
				if ( data.query ) {
					raw = data.query.pages;
					if ( raw ) {
						// Strip away the page ids
						for ( page in raw ) {
							if ( !raw[ page ].imageinfo ) {
								// The search may give us pages that belong to the File:
								// namespace but have no files in them, either because
								// they were deleted or imported wrongly, or just started
								// as pages. In that case, the response will not include
								// imageinfo. Skip those files.
								continue;
							}
							newObj = raw[ page ].imageinfo[ 0 ];
							newObj.title = raw[ page ].title;
							newObj.index = raw[ page ].index;
							results.push( newObj );
						}
					}
				}
				return provider.sort( results );
			} )
			.promise( { abort: xhr.abort } );
	};

	/**
	 * Set name
	 *
	 * @param {string} name
	 */
	mw.widgets.MediaResourceProvider.prototype.setName = function ( name ) {
		this.name = name;
	};

	/**
	 * Get name
	 *
	 * @return {string} name
	 */
	mw.widgets.MediaResourceProvider.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Get standard width, based on the provider source's thumb sizes.
	 *
	 * @return {number|undefined} fetchWidth
	 */
	mw.widgets.MediaResourceProvider.prototype.getStandardWidth = function () {
		return ( this.thumbSizes && this.thumbSizes[ this.thumbSizes.length - 1 ] ) ||
			( this.imageSizes && this.imageSizes[ 0 ] ) ||
			// Fall back on a number
			300;
	};

	/**
	 * Get prop
	 *
	 * @return {string} prop
	 */
	mw.widgets.MediaResourceProvider.prototype.getFetchProp = function () {
		return this.fetchProp;
	};

	/**
	 * Set prop
	 *
	 * @param {string} prop
	 */
	mw.widgets.MediaResourceProvider.prototype.setFetchProp = function ( prop ) {
		this.fetchProp = prop;
	};

	/**
	 * Set thumb sizes
	 *
	 * @param {number[]} sizes Available thumbnail sizes
	 */
	mw.widgets.MediaResourceProvider.prototype.setThumbSizes = function ( sizes ) {
		this.thumbSizes = sizes;
	};

	/**
	 * Set image sizes
	 *
	 * @param {number[]} sizes Available image sizes
	 */
	mw.widgets.MediaResourceProvider.prototype.setImageSizes = function ( sizes ) {
		this.imageSizes = sizes;
	};

	/**
	 * Get thumb sizes
	 *
	 * @return {number[]} sizes Available thumbnail sizes
	 */
	mw.widgets.MediaResourceProvider.prototype.getThumbSizes = function () {
		return this.thumbSizes;
	};

	/**
	 * Get image sizes
	 *
	 * @return {number[]} sizes Available image sizes
	 */
	mw.widgets.MediaResourceProvider.prototype.getImageSizes = function () {
		return this.imageSizes;
	};

	/**
	 * Check if this source is valid.
	 *
	 * @return {boolean} Source is valid
	 */
	mw.widgets.MediaResourceProvider.prototype.isValid = function () {
		return this.isLocal ||
			// If we don't have either 'apiurl' or 'scriptDirUrl'
			// the source is invalid, and we will skip it
			this.apiurl !== undefined ||
			this.scriptDirUrl !== undefined;
	};
}() );
