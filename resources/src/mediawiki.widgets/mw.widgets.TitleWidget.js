/*!
 * MediaWiki Widgets - TitleWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	var interwikiPrefixesPromise = new mw.Api().get( {
			action: 'query',
			meta: 'siteinfo',
			siprop: 'interwikimap'
		} ).then( function ( data ) {
			return $.map( data.query.interwikimap, function ( interwiki ) {
				return interwiki.prefix;
			} );
		} );

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
	 * @cfg {boolean} [showImages] Show page images
	 * @cfg {boolean} [showDescriptions] Show page descriptions
	 * @cfg {boolean} [excludeCurrentPage] Exclude the current page from suggestions
	 * @cfg {boolean} [validateTitle=true] Whether the input must be a valid title (if set to true,
	 *  the widget will marks itself red for invalid inputs, including an empty query).
	 * @cfg {Object} [cache] Result cache which implements a 'set' method, taking keyed values as an argument
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
		this.excludeCurrentPage = !!config.excludeCurrentPage;
		this.validateTitle = config.validateTitle !== undefined ? config.validateTitle : true;
		this.cache = config.cache;

		// Initialization
		this.$element.addClass( 'mw-widget-titleWidget' );
	};

	/* Setup */

	OO.initClass( mw.widgets.TitleWidget );

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

	/**
	 * Get a promise which resolves with an API repsonse for suggested
	 * links for the current query.
	 *
	 * @return {jQuery.Promise} Suggestions promise
	 */
	mw.widgets.TitleWidget.prototype.getSuggestionsPromise = function () {
		var req,
			query = this.getQueryValue(),
			widget = this,
			promiseAbortObject = { abort: function () {
				// Do nothing. This is just so OOUI doesn't break due to abort being undefined.
			} };

		if ( mw.Title.newFromText( query ) ) {
			return interwikiPrefixesPromise.then( function ( interwikiPrefixes ) {
				var params,
					interwiki = query.substring( 0, query.indexOf( ':' ) );
				if (
					interwiki && interwiki !== '' &&
					interwikiPrefixes.indexOf( interwiki ) !== -1
				) {
					return $.Deferred().resolve( { query: {
						pages: [ {
							title: query
						} ]
					} } ).promise( promiseAbortObject );
				} else {
					params = {
						action: 'query',
						prop: [ 'info', 'pageprops' ],
						generator: 'prefixsearch',
						gpssearch: query,
						gpsnamespace: widget.namespace !== null ? widget.namespace : undefined,
						gpslimit: widget.limit,
						ppprop: 'disambiguation'
					};
					if ( widget.showRedirectTargets ) {
						params.redirects = true;
					}
					if ( widget.showImages ) {
						params.prop.push( 'pageimages' );
						params.pithumbsize = 80;
						params.pilimit = widget.limit;
					}
					if ( widget.showDescriptions ) {
						params.prop.push( 'pageterms' );
						params.wbptterms = 'description';
					}
					req = new mw.Api().get( params );
					promiseAbortObject.abort = req.abort.bind( req ); // TODO ew
					return req.then( function ( ret ) {
						if ( ret.query === undefined ) {
							ret = new mw.Api().get( { action: 'query', titles: query } );
							promiseAbortObject.abort = ret.abort.bind( ret );
						}
						return ret;
					} );
				}
			} ).promise( promiseAbortObject );
		} else {
			// Don't send invalid titles to the API.
			// Just pretend it returned nothing so we can show the 'invalid title' section
			return $.Deferred().resolve( {} ).promise( promiseAbortObject );
		}
	};

	/**
	 * Get option widgets from the server response
	 *
	 * @param {Object} data Query result
	 * @return {OO.ui.OptionWidget[]} Menu items
	 */
	mw.widgets.TitleWidget.prototype.getOptionsFromData = function ( data ) {
		var i, len, index, pageExists, pageExistsExact, suggestionPage, page, redirect, redirects,
			currentPageName = new mw.Title( mw.config.get( 'wgRelevantPageName' ) ).getPrefixedText(),
			items = [],
			titles = [],
			titleObj = mw.Title.newFromText( this.getQueryValue() ),
			redirectsTo = {},
			pageData = {};

		if ( data.redirects ) {
			for ( i = 0, len = data.redirects.length; i < len; i++ ) {
				redirect = data.redirects[ i ];
				redirectsTo[ redirect.to ] = redirectsTo[ redirect.to ] || [];
				redirectsTo[ redirect.to ].push( redirect.from );
			}
		}

		for ( index in data.pages ) {
			suggestionPage = data.pages[ index ];
			// When excludeCurrentPage is set, don't list the current page unless the user has type the full title
			if ( this.excludeCurrentPage && suggestionPage.title === currentPageName && suggestionPage.title !== titleObj.getPrefixedText() ) {
				continue;
			}
			pageData[ suggestionPage.title ] = {
				known: suggestionPage.known !== undefined,
				missing: suggestionPage.missing !== undefined,
				redirect: suggestionPage.redirect !== undefined,
				disambiguation: OO.getProp( suggestionPage, 'pageprops', 'disambiguation' ) !== undefined,
				imageUrl: OO.getProp( suggestionPage, 'thumbnail', 'source' ),
				description: OO.getProp( suggestionPage, 'terms', 'description' ),
				// Sort index
				index: suggestionPage.index
			};

			// Throw away pages from wrong namespaces. This can happen when 'showRedirectTargets' is true
			// and we encounter a cross-namespace redirect.
			if ( this.namespace === null || this.namespace === suggestionPage.ns ) {
				titles.push( suggestionPage.title );
			}

			redirects = redirectsTo[ suggestionPage.title ] || [];
			for ( i = 0, len = redirects.length; i < len; i++ ) {
				pageData[ redirects[ i ] ] = {
					missing: false,
					known: true,
					redirect: true,
					disambiguation: false,
					description: mw.msg( 'mw-widgets-titleinput-description-redirect', suggestionPage.title ),
					// Sort index, just below its target
					index: suggestionPage.index + 0.5
				};
				titles.push( redirects[ i ] );
			}
		}

		titles.sort( function ( a, b ) {
			return pageData[ a ].index - pageData[ b ].index;
		} );

		// If not found, run value through mw.Title to avoid treating a match as a
		// mismatch where normalisation would make them matching (bug 48476)

		pageExistsExact = (
			Object.prototype.hasOwnProperty.call( pageData, this.getQueryValue() ) &&
			(
				!pageData[ this.getQueryValue() ].missing ||
				pageData[ this.getQueryValue() ].known
			)
		);
		pageExists = pageExistsExact || (
			titleObj &&
			Object.prototype.hasOwnProperty.call( pageData, titleObj.getPrefixedText() ) &&
			(
				!pageData[ titleObj.getPrefixedText() ].missing ||
				pageData[ titleObj.getPrefixedText() ].known
			)
		);

		if ( this.cache ) {
			this.cache.set( pageData );
		}

		// Offer the exact text as a suggestion if the page exists
		if ( pageExists && !pageExistsExact ) {
			titles.unshift( this.getQueryValue() );
		}

		for ( i = 0, len = titles.length; i < len; i++ ) {
			page = pageData[ titles[ i ] ] || {};
			items.push( new mw.widgets.TitleOptionWidget( this.getOptionWidgetData( titles[ i ], page ) ) );
		}

		return items;
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
		if ( data.missing && !description ) {
			description = mw.msg( 'mw-widgets-titleinput-description-new-page' );
		}
		return {
			data: this.namespace !== null && this.relative
				? mwTitle.getRelativeText( this.namespace )
				: title,
			url: mwTitle.getUrl(),
			imageUrl: this.showImages ? data.imageUrl : null,
			description: this.showDescriptions ? description : null,
			missing: data.missing,
			redirect: data.redirect,
			disambiguation: data.disambiguation,
			query: this.getQueryValue()
		};
	};

	/**
	 * Get title object corresponding to given value, or #getQueryValue if not given.
	 *
	 * @param {string} [value] Value to get a title for
	 * @return {mw.Title|null} Title object, or null if value is invalid
	 */
	mw.widgets.TitleWidget.prototype.getTitle = function ( value ) {
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
		return this.validateTitle ? !!this.getTitle() : true;
	};

}( jQuery, mediaWiki ) );
