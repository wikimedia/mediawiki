/*!
 * MediaWiki Widgets - TitleWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {
	const hasOwn = Object.prototype.hasOwnProperty;

	/**
	 * @classdesc Mixin for title widgets.
	 *
	 * @class
	 * @abstract
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.TitleWidget`.
	 * @param {Object} [config] Configuration options
	 * @param {number} [config.limit=10] Number of results to show
	 * @param {number} [config.namespace] Namespace to prepend to queries
	 * @param {number} [config.maxLength=255] Maximum query length
	 * @param {boolean} [config.relative=true] If a namespace is set, display titles relative to it
	 * @param {boolean} [config.suggestions=true] Display search suggestions
	 * @param {boolean} [config.showRedirectTargets=true] Show the targets of redirects
	 * @param {boolean} [config.showImages=false] Show page images
	 * @param {boolean} [config.showDescriptions=false] Show page descriptions
	 * @param {boolean} [config.showDisambigsLast=false] Show disambiguation pages as the last results
	 * @param {boolean} [config.showMissing] Show the user's input as a missing page when a page with this
	 *  exact name doesn't exist. Disabled by default when the namespace option is used, otherwise
	 *  enabled by default.
	 * @param {boolean} [config.showInterwikis=false] Show pages with a valid interwiki prefix
	 * @param {boolean} [config.searchFragments=false] Search for hash fragments on a specific page when typed
	 * @param {boolean} [config.addQueryInput=true] Add exact user's input query to results
	 * @param {boolean} [config.excludeCurrentPage=false] Exclude the current page from suggestions
	 * @param {boolean} [config.creatable=false] Show only pages that can be created (not special pages)
	 * @param {boolean} [config.validateTitle=true] Whether the input must be a valid title
	 * @param {boolean} [config.required=false] Whether the input must not be empty
	 * @param {boolean} [config.highlightSearchQuery=true] Highlight the partial query the user used for this title
	 * @param {Object} [config.cache] Result cache which implements a 'set' method, taking keyed values as an argument
	 * @param {mw.Api} [config.api] API object to use, creates a default mw.Api instance if not specified
	 */
	mw.widgets.TitleWidget = function MwWidgetsTitleWidget( config ) {
		// Config initialization
		config = config || {};

		// Properties
		this.limit = config.limit || 10;
		this.maxLength = config.maxLength || 255;
		this.namespace = config.namespace !== undefined ? config.namespace : null;
		this.relative = config.relative !== false;
		this.suggestions = config.suggestions !== false;
		this.showRedirectTargets = config.showRedirectTargets !== false;
		this.showImages = !!config.showImages;
		this.showDescriptions = !!config.showDescriptions;
		this.showDisambigsLast = !!config.showDisambigsLast;
		this.showMissing = config.showMissing !== undefined ? !!config.showMissing : this.namespace === null;
		this.showInterwikis = !!config.showInterwikis;
		this.searchFragments = !!config.searchFragments;
		this.addQueryInput = config.addQueryInput !== false;
		this.excludeCurrentPage = !!config.excludeCurrentPage;
		this.creatable = !!config.creatable;
		this.validateTitle = config.validateTitle !== false;
		this.highlightSearchQuery = config.highlightSearchQuery !== false;
		this.cache = config.cache;
		this.api = config.api || new mw.Api();
		this.compare = new Intl.Collator(
			mw.language.bcp47( mw.config.get( 'wgContentLanguage' ) ),
			{ sensitivity: 'base' }
		).compare;
		this.sectionsCache = {};

		// Initialization
		this.$element.addClass( 'mw-widget-titleWidget' );
	};

	/* Setup */

	OO.initClass( mw.widgets.TitleWidget );

	/* Static properties */

	mw.widgets.TitleWidget.static.interwikiMapPromiseCache = {};

	/* Methods */

	/**
	 * Get the current value of the search query.
	 *
	 * @abstract
	 * @method
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

	mw.widgets.TitleWidget.prototype.getInterwikiMapPromise = function () {
		if ( !this.showInterwikis ) {
			return $.Deferred().resolve( [] ).promise();
		}

		const api = this.getApi();
		const cache = this.constructor.static.interwikiMapPromiseCache;
		const key = api.defaults.ajax.url;

		if ( !hasOwn.call( cache, key ) ) {
			// Cache client-side for a day since this info is mostly static
			const oneDay = 60 * 60 * 24;
			cache[ key ] = api.get( {
				action: 'query',
				meta: 'siteinfo',
				siprop: 'interwikimap',
				maxage: oneDay,
				smaxage: oneDay,
				// Workaround T97096 by setting uselang=content
				uselang: 'content'
			} ).then( ( data ) => data.query.interwikimap );
			// Do not cache errors
			cache[ key ].catch( () => {
				delete cache[ key ];
			} );
		}
		return cache[ key ];
	};

	/**
	 * Suggest link fragments from the sections API.
	 *
	 * @param {mw.Api} api API to use for the request
	 * @param {string} title Title, extracted form the user input
	 * @param {string} fragmentQuery Partial link fragment, from the user input
	 * @param {Object} ajaxOptions Ajax options to use for the request, including abort signal
	 * @param {Object} [wiki] Interwiki map entry for the wiki to search, or undefined for the current wiki
	 * @return {jQuery.Promise} Suggestions promise
	 */
	mw.widgets.TitleWidget.prototype.getSectionSuggestions = function ( api, title, fragmentQuery, ajaxOptions, wiki ) {
		const normalizedTitle = mw.Title.newFromText( title || mw.config.get( 'wgRelevantPageName' ) );
		if ( !normalizedTitle ) {
			return $.Deferred().resolve( [] ).promise();
		}
		const normalizedTitleText = normalizedTitle.getPrefixedText();
		const cacheKey = api.defaults.ajax.url + '|' + normalizedTitleText;
		this.sectionsCache[ cacheKey ] = this.sectionsCache[ cacheKey ] || api.get( {
			action: 'parse',
			page: normalizedTitleText,
			prop: 'sections'
		}, ajaxOptions );

		function normalizeFragment( fragment ) {
			return fragment.toLowerCase().replace( /_/g, ' ' );
		}

		return this.sectionsCache[ cacheKey ].then( ( response ) => {
			const sections = OO.getProp( response, 'parse', 'sections' ) || [];
			const normalizedFragmentQuery = normalizeFragment( fragmentQuery );
			const results = sections.filter( ( section ) => normalizeFragment( section.line ).includes( normalizedFragmentQuery ) ).map( ( section ) => {
				const fragment = section.linkAnchor.replace( /_/g, ' ' );
				return {
					title: title + '#' + fragment,
					// `title`` could be empty for a relative fragment, so store the normalized
					// title if that is needed later.
					normalizedTitle: normalizedTitle + '#' + fragment,
					ns: normalizedTitle.getNamespaceId(),
					// Sort prefix matches to the top
					index: normalizeFragment( section.line ).startsWith( normalizedFragmentQuery ) ? 0 : 1
				};
			} );
			// Sorting also happens later, but we need to do it now before we truncate
			results.sort( ( a, b ) => a.index - b.index );
			const pages = results.slice( 0, this.limit );
			if ( wiki ) {
				this.processInterwikiResults( pages, wiki );
			}
			// Fake query result
			return { query: { pages } };
		} ).promise( { abort: function () {} } );
	};

	const knownNamespaces = new Set( Object.keys( mw.config.get( 'wgNamespaceIds' ) ) );

	/**
	 * Get a promise which resolves with an API response for suggested
	 * links for the current query.
	 *
	 * @return {jQuery.Promise} Suggestions promise
	 */
	mw.widgets.TitleWidget.prototype.getSuggestionsPromise = function () {
		const api = this.getApi();
		const query = this.getQueryValue();
		const ajaxOptions = {};
		const abortable = api.makeAbortablePromise( ajaxOptions );

		if ( !mw.Title.newFromText( query ) ) {
			// Don't send invalid titles to the API.
			// Just pretend it returned nothing so we can show the 'invalid title' section
			return $.Deferred().resolve( {} ).promise( abortable );
		}

		return this.getInterwikiMapPromise().then( ( interwikiMap ) => {
			// Optimization: check we have any prefixes.
			if ( interwikiMap.length ) {
				const splitIndex = Math.max( 0, query.indexOf( ':' ) );
				const interwiki = query.slice( 0, splitIndex ).toLowerCase();
				if (
					interwiki !== '' &&
					// Check prefix is not a valid namespace (T420288)
					!knownNamespaces.has( interwiki.replace( / /g, '_' ) )
				) {
					const wiki = interwikiMap.find( ( iw ) => iw.prefix === interwiki );
					if ( wiki ) {
						// If iw_api is not set, try to guess from the URL.
						if ( wiki.api || wiki.url.endsWith( '/wiki/$1' ) ) {
							// Make a foreign API request
							const interwikiApi = new mw.ForeignApi( wiki.api || wiki.url.replace( '/wiki/$1', '/w/api.php' ), { anonymous: true, parameters: { origin: '*' } } );
							const normalizedQuery = query.slice( splitIndex + 1 ).trim();
							return this.getPrefixSearchRequest( interwikiApi, normalizedQuery, ajaxOptions, wiki );
						}
						// Interwiki prefix is valid but API not found: return the original query as a valid title
						return { query: {
							pages: [ {
								title: query
							} ]
						} };
					}
				}
			}

			// Not a interwiki: do a prefix-search API lookup of the query.
			return this.getPrefixSearchRequest( api, query, ajaxOptions );
		} ).promise( abortable );
	};

	/**
	 * Get a promise which resolves with an API response for a prefix search of the query,
	 * or section suggestions if the query contains a hash fragment and searchFragments is enabled.
	 *
	 * @param {mw.Api} api API to use for the request
	 * @param {string} query User query
	 * @param {Object} ajaxOptions Ajax options to use for the request, including abort signal
	 * @param {Object} [wiki] Interwiki map entry for the wiki to search, or undefined for the current wiki
	 * @return {jQuery.Promise} Suggestions promise
	 */
	mw.widgets.TitleWidget.prototype.getPrefixSearchRequest = function ( api, query, ajaxOptions, wiki ) {
		if ( this.searchFragments ) {
			const hashIndex = query.indexOf( '#' );
			if ( hashIndex !== -1 ) {
				return this.getSectionSuggestions( api, query.slice( 0, hashIndex ), query.slice( hashIndex + 1 ), ajaxOptions, wiki );
			}
		}

		const prefixSearchRequest = api.get( this.getApiParams( query ), ajaxOptions );
		return prefixSearchRequest.then( ( prefixSearchResponse ) => {
			if ( !this.showMissing ) {
				return prefixSearchResponse;
			}
			const title = this.namespace && this.getMWTitle( query );
			// Add the query title as the first result, after looking up its details.
			const queryTitleRequest = api.get( {
				action: 'query',
				titles: title ? title.getPrefixedDb() : query
			}, ajaxOptions );
			return queryTitleRequest.then( ( queryTitleResponse ) => {
				// By default, return the prefix-search result.
				const result = prefixSearchResponse;
				if ( prefixSearchResponse.query === undefined ) {
					// There are no prefix-search results, so make the only result the query title.
					// The API response structures are identical because both API calls are action=query.
					result.query = queryTitleResponse.query;
				} else if ( queryTitleResponse.query.pages && queryTitleResponse.query.pages[ -1 ] !== undefined &&
					!this.responseContainsNonExistingTitle( prefixSearchResponse, queryTitleResponse.query.pages[ -1 ].title )
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
				if ( wiki && result.query.pages ) {
					this.processInterwikiResults( result.query.pages, wiki );
				}
				return result;
			} );
		} );
	};

	/**
	 * Process API results from an interwiki prefix search, adding URL and normalized title for each result.
	 *
	 * @param {Object} pages API response query.pages object to process
	 * @param {Object} wiki Interwiki map entry for the wiki searched
	 */
	mw.widgets.TitleWidget.prototype.processInterwikiResults = function ( pages, wiki ) {
		for ( const pageId in pages ) {
			if ( hasOwn.call( pages, pageId ) ) {
				pages[ pageId ].url = wiki.url.replace( '$1', encodeURIComponent( pages[ pageId ].title ) );
				pages[ pageId ].normalizedTitle = wiki.prefix + ':' + pages[ pageId ].title;
				pages[ pageId ].isInterwiki = true;
			}
		}
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
		for ( const redirect in apiResponse.query.redirects ) {
			if ( apiResponse.query.redirects[ redirect ].to === title ) {
				return true;
			}
		}
		// The title wasn't found.
		return false;
	};

	/**
	 * Get API params for a given query.
	 *
	 * @param {string} query User query
	 * @return {Object} API params
	 */
	mw.widgets.TitleWidget.prototype.getApiParams = function ( query ) {
		const params = {
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
	 * Get the API object for title requests.
	 *
	 * @return {mw.Api} MediaWiki API
	 */
	mw.widgets.TitleWidget.prototype.getApi = function () {
		return this.api;
	};

	/**
	 * Get option widgets from the server response.
	 *
	 * @param {Object} data Query result
	 * @return {OO.ui.OptionWidget[]} Menu items
	 */
	mw.widgets.TitleWidget.prototype.getOptionsFromData = function ( data ) {
		const currentPageName = new mw.Title( mw.config.get( 'wgRelevantPageName' ) ).getPrefixedText(),
			items = [],
			titles = [],
			disambigs = [],
			titleObj = mw.Title.newFromText( this.getQueryValue() ),
			redirectsTo = {},
			redirectIndices = {},
			pageData = {};

		if ( data.redirects ) {
			for ( let r = 0, rLen = data.redirects.length; r < rLen; r++ ) {
				const redirect = data.redirects[ r ];
				redirectsTo[ redirect.to ] = redirectsTo[ redirect.to ] || [];
				redirectsTo[ redirect.to ].push( redirect.from );
				// Save the lowest index for this redirect target.
				redirectIndices[ redirect.to ] = Math.min( redirectIndices[ redirect.to ] || redirect.index, redirect.index );
			}
		}

		for ( const index in data.pages ) {
			const suggestionPage = data.pages[ index ];

			// When excludeCurrentPage is set, don't list the current page unless the user has type the full title
			if ( this.excludeCurrentPage && suggestionPage.title === currentPageName && suggestionPage.title !== titleObj.getPrefixedText() ) {
				continue;
			}

			// If requested, ignore all pages that cannot be created (pages in negative namespace)
			if ( this.creatable && suggestionPage.ns < 0 ) {
				continue;
			}
			pageData[ suggestionPage.title ] = this.getPageData(
				suggestionPage,
				redirectIndices[ suggestionPage.title ]
			);

			// Throw away pages from wrong namespaces. This can happen when 'showRedirectTargets' is true
			// and we encounter a cross-namespace redirect.
			if ( this.namespace === null || this.namespace === suggestionPage.ns ) {
				titles.push( suggestionPage.title );
			}

			const redirects = hasOwn.call( redirectsTo, suggestionPage.title ) ? redirectsTo[ suggestionPage.title ] : [];
			for ( let i = 0, iLen = redirects.length; i < iLen; i++ ) {
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

		titles.sort( ( a, b ) => pageData[ a ].index - pageData[ b ].index );

		// If not found, run value through mw.Title to avoid treating a match as a
		// mismatch where normalisation would make them matching (T50476)

		const pageExistsExact = (
			hasOwn.call( pageData, this.getQueryValue() ) &&
			(
				!pageData[ this.getQueryValue() ].missing ||
				pageData[ this.getQueryValue() ].known
			)
		);
		const pageExists = pageExistsExact || (
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

		for ( let t = 0, tLen = titles.length; t < tLen; t++ ) {
			const page = hasOwn.call( pageData, titles[ t ] ) ? pageData[ titles[ t ] ] : {};
			const option = this.createOptionWidget( this.getOptionWidgetData( titles[ t ], page ) );

			if ( this.showDisambigsLast && page.disambiguation ) {
				disambigs.push( option );
			} else {
				items.push( option );
			}
		}

		return items.concat( disambigs );
	};

	/**
	 * Get the data to pass to createOptionWidget from the relevant part of the API response
	 *
	 * @param {Object} page The page properties from the API response.pages[i]
	 * @param {number|undefined} redirectIndex The index of the redirect, or
	 *   undefined if there is no redirect to this page
	 * @return {Object}
	 */
	mw.widgets.TitleWidget.prototype.getPageData = function ( page, redirectIndex ) {
		return {
			known: page.known !== undefined,
			missing: page.missing !== undefined,
			redirect: page.redirect !== undefined,
			disambiguation: OO.getProp( page, 'pageprops', 'disambiguation' ) !== undefined,
			imageUrl: OO.getProp( page, 'thumbnail', 'source' ),
			description: page.description,
			// Sort index
			index: page.index !== undefined ? page.index : redirectIndex,
			originalData: page
		};
	};

	/**
	 * Create a menu option widget with specified data.
	 *
	 * @param {Object} data Data for option widget
	 * @return {OO.ui.MenuOptionWidget} Data for option widget
	 */
	mw.widgets.TitleWidget.prototype.createOptionWidget = function ( data ) {
		return new mw.widgets.TitleOptionWidget( data );
	};

	/**
	 * Get menu option widget data from the title and page data.
	 *
	 * @param {string} title Title object
	 * @param {Object} data Page data
	 * @return {Object} Data for option widget
	 */
	mw.widgets.TitleWidget.prototype.getOptionWidgetData = function ( title, data ) {
		let description = data.description;
		if ( !description && ( data.missing && !data.known ) ) {
			description = mw.msg( 'mw-widgets-titleinput-description-new-page' );
		}
		const originalData = data.originalData || {};
		let optionData, label, url;
		if ( originalData.isInterwiki ) {
			// Title with interwiki prefix
			optionData = originalData.normalizedTitle;
			label = title;
			url = originalData.url;
		} else {
			const mwTitle = new mw.Title( title );
			optionData = this.namespace !== null && this.relative ?
				mwTitle.getRelativeText( this.namespace ) :
				title;
			label = optionData;
			url = mwTitle.getUrl();
		}
		return {
			data: optionData,
			label,
			url,
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
		const title = value !== undefined ? value : this.getQueryValue(),
			// mw.Title doesn't handle null well
			titleObj = mw.Title.newFromText( title, this.namespace !== null ? this.namespace : undefined );

		return titleObj;
	};

	/**
	 * Check if the query is valid.
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
