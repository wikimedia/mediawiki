/*!
 * MediaWiki Widgets - TitleWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {
	var hasOwn = Object.prototype.hasOwnProperty;

	/**
	 * Mixin for title widgets
	 *
	 * @class
	 * @abstract
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} [limit=10] Number of results to show
	 * @cfg {number} [namespace] Namespace to prepend to queries
	 * @cfg {number} [maxLength=255] Maximum query length
	 * @cfg {boolean} [relative=true] If a namespace is set, display titles relative to it
	 * @cfg {boolean} [suggestions=true] Display search suggestions
	 * @cfg {boolean} [showRedirectTargets=true] Show the targets of redirects
	 * @cfg {boolean} [showImages=false] Show page images
	 * @cfg {boolean} [showDescriptions=false] Show page descriptions
	 * @cfg {boolean} [showDisambigsLast=false] Show disambiguation pages as the last results
	 * @cfg {boolean} [showMissing] Show the user's input as a missing page when a page with this
	 *  exact name doesn't exist. Disabled by default when the namespace option is used, otherwise
	 *  enabled by default.
	 * @cfg {boolean} [showInterwikis=false] Show pages with a valid interwiki prefix
	 * @cfg {boolean} [addQueryInput=true] Add exact user's input query to results
	 * @cfg {boolean} [excludeCurrentPage=false] Exclude the current page from suggestions
	 * @cfg {boolean} [excludeDynamicNamespaces=false] Exclude pages whose namespace is negative
	 * @cfg {boolean} [validateTitle=true] Whether the input must be a valid title
	 * @cfg {boolean} [required=false] Whether the input must not be empty
	 * @cfg {boolean} [highlightSearchQuery=true] Highlight the partial query the user used for this title
	 * @cfg {Object} [cache] Result cache which implements a 'set' method, taking keyed values as an argument
	 * @cfg {mw.Api} [api] API object to use, creates a default mw.Api instance if not specified
	 */
	mw.widgets.TitleWidget = function MwWidgetsTitleWidget( config ) {
		// Config initialization
		config = $.extend( {
			maxLength: 255,
			limit: 10
		}, config );

		// Properties
		this.limit = config.limit;
		this.maxLength = config.maxLength;
		this.namespace = config.namespace !== undefined ? config.namespace : null;
		this.relative = config.relative !== undefined ? config.relative : true;
		this.suggestions = config.suggestions !== undefined ? config.suggestions : true;
		this.showRedirectTargets = config.showRedirectTargets !== false;
		this.showImages = !!config.showImages;
		this.showDescriptions = !!config.showDescriptions;
		this.showDisambigsLast = !!config.showDisambigsLast;
		this.showMissing = config.showMissing !== undefined ? !!config.showMissing : this.namespace === null;
		this.showInterwikis = !!config.showInterwikis;
		this.addQueryInput = config.addQueryInput !== false;
		this.excludeCurrentPage = !!config.excludeCurrentPage;
		this.excludeDynamicNamespaces = !!config.excludeDynamicNamespaces;
		this.validateTitle = config.validateTitle !== undefined ? config.validateTitle : true;
		this.highlightSearchQuery = config.highlightSearchQuery === undefined ? true : !!config.highlightSearchQuery;
		this.cache = config.cache;
		this.api = config.api || new mw.Api();
		// Supports: IE10, FF28, Chrome23
		// eslint-disable-next-line compat/compat
		this.compare = window.Intl && Intl.Collator ?
			// eslint-disable-next-line compat/compat
			new Intl.Collator(
				mw.language.bcp47( mw.config.get( 'wgContentLanguage' ) ),
				{ sensitivity: 'base' }
			).compare :
			null;

		// Initialization
		this.$element.addClass( 'mw-widget-titleWidget' );
	};

	/* Setup */

	OO.initClass( mw.widgets.TitleWidget );

	/* Static properties */

	mw.widgets.TitleWidget.static.interwikiPrefixesPromiseCache = {};

	/* Methods */

	/**
	 * Get the current value of the search query
	 *
	 * @abstract
	 * @return {string} Search query
	 */
	mw.widgets.TitleWidget.prototype.getQueryValue = null;

	/**
	 * Get the namespace to prepend to titles in suggestions, if any.
	 *
	 * @return {number|null} Namespace number
	 */
	mw.widgets.TitleWidget.prototype.getNamespace = function () {
		return this.namespace;
	};

	/**
	 * Set the namespace to prepend to titles in suggestions, if any.
	 *
	 * @param {number|null} namespace Namespace number
	 */
	mw.widgets.TitleWidget.prototype.setNamespace = function ( namespace ) {
		this.namespace = namespace;
	};

	mw.widgets.TitleWidget.prototype.getInterwikiPrefixesPromise = function () {
		var api, cache, key;

		if ( !this.showInterwikis ) {
			return $.Deferred().resolve( [] ).promise();
		}

		api = this.getApi();
		cache = this.constructor.static.interwikiPrefixesPromiseCache;
		key = api.defaults.ajax.url;

		if ( !Object.prototype.hasOwnProperty.call( cache, key ) ) {
			cache[ key ] = api.get( {
				action: 'query',
				meta: 'siteinfo',
				siprop: 'interwikimap',
				// Cache client-side for a day since this info is mostly static
				maxage: 60 * 60 * 24,
				smaxage: 60 * 60 * 24,
				// Workaround T97096 by setting uselang=content
				uselang: 'content'
			} ).then( function ( data ) {
				return data.query.interwikimap.map( function ( interwiki ) {
					return interwiki.prefix;
				} );
			} );
		}
		return cache[ key ];
	};

	/**
	 * Get a promise which resolves with an API response for suggested
	 * links for the current query.
	 *
	 * @return {jQuery.Promise} Suggestions promise
	 */
	mw.widgets.TitleWidget.prototype.getSuggestionsPromise = function () {
		var api = this.getApi(),
			query = this.getQueryValue(),
			widget = this,
			promiseAbortObject = { abort: function () {
				// Do nothing. This is just so OOUI doesn't break due to abort being undefined.
			} };

		if ( !mw.Title.newFromText( query ) ) {
			// Don't send invalid titles to the API.
			// Just pretend it returned nothing so we can show the 'invalid title' section
			return $.Deferred().resolve( {} ).promise( promiseAbortObject );
		}

		return this.getInterwikiPrefixesPromise().then( function ( interwikiPrefixes ) {
			// Optimization: check we have any prefixes.
			if ( interwikiPrefixes.length ) {
				var interwiki = query.slice( 0, Math.max( 0, query.indexOf( ':' ) ) );
				if (
					interwiki !== '' &&
					interwikiPrefixes.indexOf( interwiki ) !== -1
				) {
					// Interwiki prefix is valid: return the original query as a valid title
					// NB: This doesn't check if the title actually exists on the other wiki
					return $.Deferred().resolve( { query: {
						pages: [ {
							title: query
						} ]
					} } ).promise( promiseAbortObject );
				}
			}
			// Not a interwiki: do a prefix-search API lookup of the query.
			var prefixSearchRequest = api.get( widget.getApiParams( query ) );
			promiseAbortObject.abort = prefixSearchRequest.abort.bind( prefixSearchRequest ); // TODO ew
			return prefixSearchRequest.then( function ( prefixSearchResponse ) {
				if ( !widget.showMissing ) {
					return prefixSearchResponse;
				}
				var title = widget.namespace && widget.getMWTitle( query );
				// Add the query title as the first result, after looking up its details.
				var queryTitleRequest = api.get( {
					action: 'query',
					titles: title ? title.getPrefixedDb() : query
				} );
				promiseAbortObject.abort = queryTitleRequest.abort.bind( queryTitleRequest );
				return queryTitleRequest.then( function ( queryTitleResponse ) {
					// By default, return the prefix-search result.
					var result = prefixSearchResponse;
					if ( prefixSearchResponse.query === undefined ) {
						// There are no prefix-search results, so make the only result the query title.
						// The API response structures are identical because both API calls are action=query.
						result.query = queryTitleResponse.query;
					} else if ( queryTitleResponse.query.pages[ -1 ] !== undefined &&
						!widget.responseContainsNonExistingTitle( prefixSearchResponse, queryTitleResponse.query.pages[ -1 ].title )
					) {
						// There are prefix-search results, but the query title isn't in them,
						// so add it as a new result. It's under the new key 'queryTitle', because
						// all other results will be under their page ID or a negative integer ID,
						// and the keys aren't actually used for anything.
						result.query.pages.queryTitle = queryTitleResponse.query.pages[ -1 ];
						// Give it the lowest possible sort-index (the API only returns index > 0)
						// to make this result be sorted at the top.
						result.query.pages.queryTitle.index = 0;
					}
					return result;
				} );
			} );
		} ).promise( promiseAbortObject );
	};

	/**
	 * Check for the existence of a given title in an API result set.
	 *
	 * As the title is known not to exist, this doesn't check in apiResponse.query.pages,
	 * but only in the redirect targets in apiResponse.query.redirects.
	 *
	 * @private
	 * @param {Object} apiResponse The API result set to search in.
	 * @param {string} title The page title to search for.
	 * @return {boolean}
	 */
	mw.widgets.TitleWidget.prototype.responseContainsNonExistingTitle = function ( apiResponse, title ) {
		// Make sure there are redirects in the data.
		if ( apiResponse.query.redirects === undefined ) {
			return false;
		}
		// Check the targets against the given title.
		for ( var redirect in apiResponse.query.redirects ) {
			if ( apiResponse.query.redirects[ redirect ].to === title ) {
				return true;
			}
		}
		// The title wasn't found.
		return false;
	};

	/**
	 * Get API params for a given query
	 *
	 * @param {string} query User query
	 * @return {Object} API params
	 */
	mw.widgets.TitleWidget.prototype.getApiParams = function ( query ) {
		var params = {
			action: 'query',
			prop: [ 'info', 'pageprops' ],
			generator: 'prefixsearch',
			gpssearch: query,
			gpsnamespace: this.namespace !== null ? this.namespace : undefined,
			gpslimit: this.limit,
			ppprop: 'disambiguation'
		};
		if ( this.showRedirectTargets ) {
			params.redirects = true;
		}
		if ( this.showImages ) {
			params.prop.push( 'pageimages' );
			params.pithumbsize = 80;
			params.pilimit = this.limit;
		}
		if ( this.showDescriptions ) {
			params.prop.push( 'description' );
		}
		return params;
	};

	/**
	 * Get the API object for title requests
	 *
	 * @return {mw.Api} MediaWiki API
	 */
	mw.widgets.TitleWidget.prototype.getApi = function () {
		return this.api;
	};

	/**
	 * Get option widgets from the server response
	 *
	 * @param {Object} data Query result
	 * @return {OO.ui.OptionWidget[]} Menu items
	 */
	mw.widgets.TitleWidget.prototype.getOptionsFromData = function ( data ) {
		var i, len, index, option, pageExists, pageExistsExact, suggestionPage, page, redirect, redirects,
			currentPageName = new mw.Title( mw.config.get( 'wgRelevantPageName' ) ).getPrefixedText(),
			items = [],
			titles = [],
			disambigs = [],
			titleObj = mw.Title.newFromText( this.getQueryValue() ),
			redirectsTo = {},
			redirectIndices = {},
			pageData = {};

		if ( data.redirects ) {
			for ( i = 0, len = data.redirects.length; i < len; i++ ) {
				redirect = data.redirects[ i ];
				redirectsTo[ redirect.to ] = redirectsTo[ redirect.to ] || [];
				redirectsTo[ redirect.to ].push( redirect.from );
				// Save the lowest index for this redirect target.
				redirectIndices[ redirect.to ] = Math.min( redirectIndices[ redirect.to ] || redirect.index, redirect.index );
			}
		}

		for ( index in data.pages ) {
			suggestionPage = data.pages[ index ];

			// When excludeCurrentPage is set, don't list the current page unless the user has type the full title
			if ( this.excludeCurrentPage && suggestionPage.title === currentPageName && suggestionPage.title !== titleObj.getPrefixedText() ) {
				continue;
			}

			// When excludeDynamicNamespaces is set, ignore all pages with negative namespace
			if ( this.excludeDynamicNamespaces && suggestionPage.ns < 0 ) {
				continue;
			}
			pageData[ suggestionPage.title ] = {
				known: suggestionPage.known !== undefined,
				missing: suggestionPage.missing !== undefined,
				redirect: suggestionPage.redirect !== undefined,
				disambiguation: OO.getProp( suggestionPage, 'pageprops', 'disambiguation' ) !== undefined,
				imageUrl: OO.getProp( suggestionPage, 'thumbnail', 'source' ),
				description: suggestionPage.description,
				// Sort index
				index: suggestionPage.index !== undefined ? suggestionPage.index : redirectIndices[ suggestionPage.title ],
				originalData: suggestionPage
			};

			// Throw away pages from wrong namespaces. This can happen when 'showRedirectTargets' is true
			// and we encounter a cross-namespace redirect.
			if ( this.namespace === null || this.namespace === suggestionPage.ns ) {
				titles.push( suggestionPage.title );
			}

			redirects = hasOwn.call( redirectsTo, suggestionPage.title ) ? redirectsTo[ suggestionPage.title ] : [];
			for ( i = 0, len = redirects.length; i < len; i++ ) {
				pageData[ redirects[ i ] ] = {
					missing: false,
					known: true,
					redirect: true,
					disambiguation: false,
					description: mw.msg( 'mw-widgets-titleinput-description-redirect', suggestionPage.title ),
					// Sort index, just below its target
					index: pageData[ suggestionPage.title ].index + 0.5,
					originalData: suggestionPage
				};
				titles.push( redirects[ i ] );
			}
		}

		titles.sort( function ( a, b ) {
			return pageData[ a ].index - pageData[ b ].index;
		} );

		// If not found, run value through mw.Title to avoid treating a match as a
		// mismatch where normalisation would make them matching (T50476)

		pageExistsExact = (
			hasOwn.call( pageData, this.getQueryValue() ) &&
			(
				!pageData[ this.getQueryValue() ].missing ||
				pageData[ this.getQueryValue() ].known
			)
		);
		pageExists = pageExistsExact || (
			titleObj &&
			hasOwn.call( pageData, titleObj.getPrefixedText() ) &&
			(
				!pageData[ titleObj.getPrefixedText() ].missing ||
				pageData[ titleObj.getPrefixedText() ].known
			)
		);

		// Offer the exact text as a suggestion if the page exists
		if ( this.addQueryInput && pageExists && !pageExistsExact ) {
			titles.unshift( this.getQueryValue() );
			// Ensure correct page metadata gets used
			pageData[ this.getQueryValue() ] = pageData[ titleObj.getPrefixedText() ];
		}

		if ( this.cache ) {
			this.cache.set( pageData );
		}

		for ( i = 0, len = titles.length; i < len; i++ ) {
			page = hasOwn.call( pageData, titles[ i ] ) ? pageData[ titles[ i ] ] : {};
			option = this.createOptionWidget( this.getOptionWidgetData( titles[ i ], page ) );

			if ( this.showDisambigsLast && page.disambiguation ) {
				disambigs.push( option );
			} else {
				items.push( option );
			}
		}

		return items.concat( disambigs );
	};

	/**
	 * Create a menu option widget with specified data
	 *
	 * @param {Object} data Data for option widget
	 * @return {OO.ui.MenuOptionWidget} Data for option widget
	 */
	mw.widgets.TitleWidget.prototype.createOptionWidget = function ( data ) {
		return new mw.widgets.TitleOptionWidget( data );
	};

	/**
	 * Get menu option widget data from the title and page data
	 *
	 * @param {string} title Title object
	 * @param {Object} data Page data
	 * @return {Object} Data for option widget
	 */
	mw.widgets.TitleWidget.prototype.getOptionWidgetData = function ( title, data ) {
		var mwTitle = new mw.Title( title ),
			description = data.description;
		if ( !description && ( data.missing && !data.known ) ) {
			description = mw.msg( 'mw-widgets-titleinput-description-new-page' );
		}
		return {
			data: this.namespace !== null && this.relative ?
				mwTitle.getRelativeText( this.namespace ) :
				title,
			url: mwTitle.getUrl(),
			showImages: this.showImages,
			imageUrl: this.showImages ? data.imageUrl : null,
			description: this.showDescriptions ? description : null,
			missing: data.missing && !data.known,
			redirect: data.redirect,
			disambiguation: data.disambiguation,
			query: this.highlightSearchQuery ? this.getQueryValue() : null,
			compare: this.compare
		};
	};

	/**
	 * Get title object corresponding to given value, or #getQueryValue if not given.
	 *
	 * @param {string} [value] Value to get a title for
	 * @return {mw.Title|null} Title object, or null if value is invalid
	 */
	mw.widgets.TitleWidget.prototype.getMWTitle = function ( value ) {
		var title = value !== undefined ? value : this.getQueryValue(),
			// mw.Title doesn't handle null well
			titleObj = mw.Title.newFromText( title, this.namespace !== null ? this.namespace : undefined );

		return titleObj;
	};

	/**
	 * Check if the query is valid
	 *
	 * @return {boolean} The query is valid
	 */
	mw.widgets.TitleWidget.prototype.isQueryValid = function () {
		if ( !this.validateTitle ) {
			return true;
		}
		if ( !this.required && this.getQueryValue() === '' ) {
			return true;
		}
		return !!this.getMWTitle();
	};

}() );
