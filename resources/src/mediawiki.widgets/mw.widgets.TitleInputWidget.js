/*!
 * MediaWiki Widgets - TitleInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Creates an mw.widgets.TitleInputWidget object.
	 *
	 * @class
	 * @extends OO.ui.TextInputWidget
	 * @mixins OO.ui.mixin.LookupElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} [limit=10] Number of results to show
	 * @cfg {number} [namespace] Namespace to prepend to queries
	 * @cfg {boolean} [relative=true] If a namespace is set, return a title relative to it
	 * @cfg {boolean} [suggestions=true] Display search suggestions
	 * @cfg {boolean} [showRedirectTargets=true] Show the targets of redirects
	 * @cfg {boolean} [showRedlink] Show red link to exact match if it doesn't exist
	 * @cfg {boolean} [showImages] Show page images
	 * @cfg {boolean} [showDescriptions] Show page descriptions
	 * @cfg {Object} [cache] Result cache which implements a 'set' method, taking keyed values as an argument
	 */
	mw.widgets.TitleInputWidget = function MwWidgetsTitleInputWidget( config ) {
		var widget = this;

		// Config initialization
		config = $.extend( {
			maxLength: 255,
			limit: 10
		}, config );

		// Parent constructor
		mw.widgets.TitleInputWidget.parent.call( this, $.extend( {}, config, { autocomplete: false } ) );

		// Mixin constructors
		OO.ui.mixin.LookupElement.call( this, config );

		// Properties
		this.limit = config.limit;
		this.maxLength = config.maxLength;
		this.namespace = config.namespace !== undefined ? config.namespace : null;
		this.relative = config.relative !== undefined ? config.relative : true;
		this.suggestions = config.suggestions !== undefined ? config.suggestions : true;
		this.showRedirectTargets = config.showRedirectTargets !== false;
		this.showRedlink = !!config.showRedlink;
		this.showImages = !!config.showImages;
		this.showDescriptions = !!config.showDescriptions;
		this.cache = config.cache;

		// Initialization
		this.$element.addClass( 'mw-widget-titleInputWidget' );
		this.lookupMenu.$element.addClass( 'mw-widget-titleInputWidget-menu' );
		if ( this.showImages ) {
			this.lookupMenu.$element.addClass( 'mw-widget-titleInputWidget-menu-withImages' );
		}
		if ( this.showDescriptions ) {
			this.lookupMenu.$element.addClass( 'mw-widget-titleInputWidget-menu-withDescriptions' );
		}
		this.setLookupsDisabled( !this.suggestions );

		this.interwikiPrefixes = [];
		this.interwikiPrefixesPromise = new mw.Api().get( {
			action: 'query',
			meta: 'siteinfo',
			siprop: 'interwikimap'
		} ).done( function ( data ) {
			$.each( data.query.interwikimap, function ( index, interwiki ) {
				widget.interwikiPrefixes.push( interwiki.prefix );
			} );
		} );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.TitleInputWidget, OO.ui.TextInputWidget );
	OO.mixinClass( mw.widgets.TitleInputWidget, OO.ui.mixin.LookupElement );

	/* Methods */

	/**
	 * Get the namespace to prepend to titles in suggestions, if any.
	 *
	 * @return {number|null} Namespace number
	 */
	mw.widgets.TitleInputWidget.prototype.getNamespace = function () {
		return this.namespace;
	};

	/**
	 * Set the namespace to prepend to titles in suggestions, if any.
	 *
	 * @param {number|null} namespace Namespace number
	 */
	mw.widgets.TitleInputWidget.prototype.setNamespace = function ( namespace ) {
		this.namespace = namespace;
		this.lookupCache = {};
		this.closeLookupMenu();
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.onLookupMenuItemChoose = function ( item ) {
		this.closeLookupMenu();
		this.setLookupsDisabled( true );
		this.setValue( item.getData() );
		this.setLookupsDisabled( !this.suggestions );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.focus = function () {
		var retval;

		// Prevent programmatic focus from opening the menu
		this.setLookupsDisabled( true );

		// Parent method
		retval = mw.widgets.TitleInputWidget.parent.prototype.focus.apply( this, arguments );

		this.setLookupsDisabled( !this.suggestions );

		return retval;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupRequest = function () {
		var req,
			widget = this,
			promiseAbortObject = { abort: function () {
				// Do nothing. This is just so OOUI doesn't break due to abort being undefined.
			} };

		if ( mw.Title.newFromText( this.value ) ) {
			return this.interwikiPrefixesPromise.then( function () {
				var params, props,
					interwiki = widget.value.substring( 0, widget.value.indexOf( ':' ) );
				if (
					interwiki && interwiki !== '' &&
					widget.interwikiPrefixes.indexOf( interwiki ) !== -1
				) {
					return $.Deferred().resolve( { query: {
						pages: [ {
							title: widget.value
						} ]
					} } ).promise( promiseAbortObject );
				} else {
					params = {
						action: 'query',
						generator: 'prefixsearch',
						gpssearch: widget.value,
						gpsnamespace: widget.namespace !== null ? widget.namespace : undefined,
						gpslimit: widget.limit,
						ppprop: 'disambiguation'
					};
					props = [ 'info', 'pageprops' ];
					if ( widget.showRedirectTargets ) {
						params.redirects = '1';
					}
					if ( widget.showImages ) {
						props.push( 'pageimages' );
						params.pithumbsize = 80;
						params.pilimit = widget.limit;
					}
					if ( widget.showDescriptions ) {
						props.push( 'pageterms' );
						params.wbptterms = 'description';
					}
					params.prop = props.join( '|' );
					req = new mw.Api().get( params );
					promiseAbortObject.abort = req.abort.bind( req ); // todo: ew
					return req;
				}
			} ).promise( promiseAbortObject );
		} else {
			// Don't send invalid titles to the API.
			// Just pretend it returned nothing so we can show the 'invalid title' section
			return $.Deferred().resolve( {} ).promise( promiseAbortObject );
		}
	};

	/**
	 * Get lookup cache item from server response data.
	 *
	 * @method
	 * @param {Mixed} response Response from server
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupCacheDataFromResponse = function ( response ) {
		return response.query || {};
	};

	/**
	 * Get list of menu items from a server response.
	 *
	 * @param {Object} data Query result
	 * @returns {OO.ui.MenuOptionWidget[]} Menu items
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupMenuOptionsFromData = function ( data ) {
		var i, len, index, pageExists, pageExistsExact, suggestionPage, page, redirect, redirects,
			items = [],
			titles = [],
			titleObj = mw.Title.newFromText( this.value ),
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
			pageData[ suggestionPage.title ] = {
				missing: suggestionPage.missing !== undefined,
				redirect: suggestionPage.redirect !== undefined,
				disambiguation: OO.getProp( suggestionPage, 'pageprops', 'disambiguation' ) !== undefined,
				imageUrl: OO.getProp( suggestionPage, 'thumbnail', 'source' ),
				description: OO.getProp( suggestionPage, 'terms', 'description' )
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
					redirect: true,
					disambiguation: false,
					description: mw.msg( 'mw-widgets-titleinput-description-redirect', suggestionPage.title )
				};
				titles.push( redirects[ i ] );
			}
		}

		// If not found, run value through mw.Title to avoid treating a match as a
		// mismatch where normalisation would make them matching (bug 48476)

		pageExistsExact = titles.indexOf( this.value ) !== -1;
		pageExists = pageExistsExact || (
			titleObj && titles.indexOf( titleObj.getPrefixedText() ) !== -1
		);

		if ( !pageExists ) {
			pageData[ this.value ] = {
				missing: true, redirect: false, disambiguation: false,
				description: mw.msg( 'mw-widgets-titleinput-description-new-page' )
			};
		}

		if ( this.cache ) {
			this.cache.set( pageData );
		}

		// Offer the exact text as a suggestion if the page exists
		if ( pageExists && !pageExistsExact ) {
			titles.unshift( this.value );
		}
		// Offer the exact text as a new page if the title is valid
		if ( this.showRedlink && !pageExists && titleObj ) {
			titles.push( this.value );
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
	 * @param {mw.Title} title Title object
	 * @param {Object} data Page data
	 * @return {Object} Data for option widget
	 */
	mw.widgets.TitleInputWidget.prototype.getOptionWidgetData = function ( title, data ) {
		var mwTitle = new mw.Title( title );
		return {
			data: this.namespace !== null && this.relative
				? mwTitle.getRelativeText( this.namespace )
				: title,
			title: mwTitle,
			imageUrl: this.showImages ? data.imageUrl : null,
			description: this.showDescriptions ? data.description : null,
			missing: data.missing,
			redirect: data.redirect,
			disambiguation: data.disambiguation,
			query: this.value
		};
	};

	/**
	 * Get title object corresponding to given value, or #getValue if not given.
	 *
	 * @param {string} [value] Value to get a title for
	 * @returns {mw.Title|null} Title object, or null if value is invalid
	 */
	mw.widgets.TitleInputWidget.prototype.getTitle = function ( value ) {
		var title = value !== undefined ? value : this.getValue(),
			// mw.Title doesn't handle null well
			titleObj = mw.Title.newFromText( title, this.namespace !== null ? this.namespace : undefined );

		return titleObj;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.cleanUpValue = function ( value ) {
		var widget = this;
		value = mw.widgets.TitleInputWidget.parent.prototype.cleanUpValue.call( this, value );
		return $.trimByteLength( this.value, value, this.maxLength, function ( value ) {
			var title = widget.getTitle( value );
			return title ? title.getMain() : value;
		} ).newVal;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.isValid = function () {
		return $.Deferred().resolve( !!this.getTitle() ).promise();
	};

}( jQuery, mediaWiki ) );
