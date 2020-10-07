/*!
 * MediaWiki Widgets - MediaSearchWidget class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * Creates an mw.widgets.MediaSearchWidget object.
	 *
	 * @class
	 * @extends OO.ui.SearchWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @param {number} [size] Vertical size of thumbnails
	 */
	mw.widgets.MediaSearchWidget = function MwWidgetsMediaSearchWidget( config ) {
		var queueConfig;

		// Configuration initialization
		config = $.extend( {
			placeholder: mw.msg( 'mw-widgets-mediasearch-input-placeholder' )
		}, config );

		// Parent constructor
		mw.widgets.MediaSearchWidget.super.call( this, config );

		// Properties
		this.providers = {};
		this.lastQueryValue = '';

		queueConfig = {
			limit: this.constructor.static.limit,
			threshold: this.constructor.static.threshold
		};
		this.searchQueue = new mw.widgets.MediaSearchQueue( queueConfig );
		this.userUploadsQueue = new mw.widgets.MediaUserUploadsQueue( queueConfig );
		this.currentQueue = null;

		this.queryTimeout = null;
		this.itemCache = {};
		this.promises = [];
		this.$panels = config.$panels;

		this.externalLinkUrlProtocolsRegExp = new RegExp(
			'^(' + mw.config.get( 'wgUrlProtocols' ) + ')',
			'i'
		);

		// Masonry fit properties
		this.rows = [];
		this.rowHeight = config.rowHeight || 200;
		this.layoutQueue = [];
		this.numItems = 0;
		this.currentItemCache = [];

		this.resultsSize = {};

		this.selected = null;

		this.recentUploadsMessage = new OO.ui.LabelWidget( {
			label: mw.msg( 'mw-widgets-mediasearch-recent-uploads', mw.user ),
			classes: [ 'mw-widget-mediaSearchWidget-recentUploads' ]
		} );
		this.recentUploadsMessage.toggle( false );
		this.noItemsMessage = new OO.ui.LabelWidget( {
			label: mw.msg( 'mw-widgets-mediasearch-noresults' ),
			classes: [ 'mw-widget-mediaSearchWidget-noResults' ]
		} );
		this.noItemsMessage.toggle( false );

		// Events
		this.$results.on( 'scroll', this.onResultsScroll.bind( this ) );
		this.results.connect( this, {
			change: 'onResultsChange',
			remove: 'onResultsRemove'
		} );

		this.resizeHandler = OO.ui.debounce( this.afterResultsResize.bind( this ), 500 );

		// Initialization
		this.setLang( config.lang || 'en' );
		this.$results.prepend( this.recentUploadsMessage.$element, this.noItemsMessage.$element );
		this.$element.addClass( 'mw-widget-mediaSearchWidget' );

		this.query.$input.attr( 'aria-label', mw.msg( 'mw-widgets-mediasearch-input-placeholder' ) );
		this.results.$element.attr( 'aria-label', mw.msg( 'mw-widgets-mediasearch-results-aria-label' ) );
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.MediaSearchWidget, OO.ui.SearchWidget );

	/* Static properties */

	mw.widgets.MediaSearchWidget.static.limit = 10;

	mw.widgets.MediaSearchWidget.static.threshold = 5;

	/* Methods */

	/**
	 * Respond to window resize and check if the result display should
	 * be updated.
	 */
	mw.widgets.MediaSearchWidget.prototype.afterResultsResize = function () {
		var items = this.currentItemCache;

		if (
			items.length > 0 &&
			(
				this.resultsSize.width !== this.$results.width() ||
				this.resultsSize.height !== this.$results.height()
			)
		) {
			this.resetRows();
			this.itemCache = {};
			this.processQueueResults( items );
			if ( this.results.getItems().length > 0 ) {
				this.lazyLoadResults();
			}

			// Cache the size
			this.resultsSize = {
				width: this.$results.width(),
				height: this.$results.height()
			};
		}
	};

	/**
	 * Teardown the widget; disconnect the window resize event.
	 */
	mw.widgets.MediaSearchWidget.prototype.teardown = function () {
		$( window ).off( 'resize', this.resizeHandler );
	};

	/**
	 * Setup the widget; activate the resize event.
	 */
	mw.widgets.MediaSearchWidget.prototype.setup = function () {
		$( window ).on( 'resize', this.resizeHandler );
	};

	/**
	 * Query all sources for media.
	 *
	 * @method
	 */
	mw.widgets.MediaSearchWidget.prototype.queryMediaQueue = function () {
		var search = this,
			value = this.getQueryValue();

		if ( value === '' ) {
			if ( mw.user.isAnon() ) {
				return;
			} else {
				if ( this.currentQueue !== this.userUploadsQueue ) {
					this.userUploadsQueue.reset();
				}
				this.currentQueue = this.userUploadsQueue;
				// TODO: use cached results?
			}
		} else {
			this.currentQueue = this.searchQueue;
			this.currentQueue.setSearchQuery( value );
		}

		this.recentUploadsMessage.toggle( this.currentQueue === this.userUploadsQueue );

		this.query.pushPending();
		search.noItemsMessage.toggle( false );

		this.currentQueue.get( this.constructor.static.limit )
			.then( function ( items ) {
				if ( items.length > 0 ) {
					search.processQueueResults( items );
					search.currentItemCache = search.currentItemCache.concat( items );
				}

				search.query.popPending();
				search.noItemsMessage.toggle( search.results.getItems().length === 0 );
				if ( search.results.getItems().length > 0 ) {
					search.lazyLoadResults();
				}

			} );
	};

	/**
	 * Process the media queue giving more items
	 *
	 * @method
	 * @param {Object[]} items Given items by the media queue
	 */
	mw.widgets.MediaSearchWidget.prototype.processQueueResults = function ( items ) {
		var i, len, title,
			resultWidgets = [],
			inputSearchQuery = this.getQueryValue(),
			queueSearchQuery = this.searchQueue.getSearchQuery();

		if (
			this.currentQueue === this.searchQueue &&
			( inputSearchQuery === '' || queueSearchQuery !== inputSearchQuery )
		) {
			return;
		}

		for ( i = 0, len = items.length; i < len; i++ ) {
			title = new mw.Title( items[ i ].title ).getMainText();
			// Do not insert duplicates
			if ( !Object.prototype.hasOwnProperty.call( this.itemCache, title ) ) {
				this.itemCache[ title ] = true;
				resultWidgets.push(
					new mw.widgets.MediaResultWidget( {
						data: items[ i ],
						rowHeight: this.rowHeight,
						maxWidth: this.results.$element.width() / 3,
						minWidth: 30,
						rowWidth: this.results.$element.width()
					} )
				);
			}
		}
		this.results.addItems( resultWidgets );

	};

	/**
	 * Get the sanitized query value from the input
	 *
	 * @return {string} Query value
	 */
	mw.widgets.MediaSearchWidget.prototype.getQueryValue = function () {
		var queryValue = this.query.getValue().trim();

		if ( queryValue.match( this.externalLinkUrlProtocolsRegExp ) ) {
			queryValue = queryValue.match( /.+\/([^/]+)/ )[ 1 ];
		}
		return queryValue;
	};

	/**
	 * Handle search value change
	 *
	 * @param {string} value New value
	 */
	mw.widgets.MediaSearchWidget.prototype.onQueryChange = function () {
		// Get the sanitized query value
		var queryValue = this.getQueryValue();

		if ( queryValue === this.lastQueryValue ) {
			return;
		}

		// Parent method
		mw.widgets.MediaSearchWidget.super.prototype.onQueryChange.apply( this, arguments );

		// Reset
		this.itemCache = {};
		this.currentItemCache = [];
		this.resetRows();
		this.recentUploadsMessage.toggle( false );

		// Empty the results queue
		this.layoutQueue = [];

		// Change resource queue query
		this.searchQueue.setSearchQuery( queryValue );
		this.lastQueryValue = queryValue;

		// Queue
		clearTimeout( this.queryTimeout );
		this.queryTimeout = setTimeout( this.queryMediaQueue.bind( this ), 350 );
	};

	/**
	 * Handle results scroll events.
	 *
	 * @param {jQuery.Event} e Scroll event
	 */
	mw.widgets.MediaSearchWidget.prototype.onResultsScroll = function () {
		var position = this.$results.scrollTop() + this.$results.outerHeight(),
			threshold = this.results.$element.outerHeight() - this.rowHeight * 3;

		// Check if we need to ask for more results
		if ( !this.query.isPending() && position > threshold ) {
			this.queryMediaQueue();
		}

		this.lazyLoadResults();
	};

	/**
	 * Lazy-load the images that are visible.
	 */
	mw.widgets.MediaSearchWidget.prototype.lazyLoadResults = function () {
		var i, elementTop,
			items = this.results.getItems(),
			resultsScrollTop = this.$results.scrollTop(),
			position = resultsScrollTop + this.$results.outerHeight();

		// Lazy-load results
		for ( i = 0; i < items.length; i++ ) {
			elementTop = items[ i ].$element.position().top;
			if ( elementTop <= position && !items[ i ].hasSrc() ) {
				// Load the image
				items[ i ].lazyLoad();
			}
		}
	};

	/**
	 * Reset all the rows; destroy the jQuery elements and reset
	 * the rows array.
	 */
	mw.widgets.MediaSearchWidget.prototype.resetRows = function () {
		var i, len;

		for ( i = 0, len = this.rows.length; i < len; i++ ) {
			this.rows[ i ].$element.remove();
		}

		this.rows = [];
		this.itemCache = {};
	};

	/**
	 * Find an available row at the end. Either we will need to create a new
	 * row or use the last available row if it isn't full.
	 *
	 * @return {number} Row index
	 */
	mw.widgets.MediaSearchWidget.prototype.getAvailableRow = function () {
		var row;

		if ( this.rows.length === 0 ) {
			row = 0;
		} else {
			row = this.rows.length - 1;
		}

		if ( !this.rows[ row ] ) {
			// Create new row
			this.rows[ row ] = {
				isFull: false,
				width: 0,
				items: [],
				$element: $( '<div>' )
					.addClass( 'mw-widget-mediaResultWidget-row' )
					.css( {
						overflow: 'hidden'
					} )
					.data( 'row', row )
					.attr( 'data-full', false )
			};
			// Append to results
			this.results.$element.append( this.rows[ row ].$element );
		} else if ( this.rows[ row ].isFull ) {
			row++;
			// Create new row
			this.rows[ row ] = {
				isFull: false,
				width: 0,
				items: [],
				$element: $( '<div>' )
					.addClass( 'mw-widget-mediaResultWidget-row' )
					.css( {
						overflow: 'hidden'
					} )
					.data( 'row', row )
					.attr( 'data-full', false )
			};
			// Append to results
			this.results.$element.append( this.rows[ row ].$element );
		}

		return row;
	};

	/**
	 * Respond to change results event in the results widget.
	 * Override the way SelectWidget and GroupElement append the items
	 * into the group so we can append them in groups of rows.
	 *
	 * @param {mw.widgets.MediaResultWidget[]} items An array of item elements
	 */
	mw.widgets.MediaSearchWidget.prototype.onResultsChange = function ( items ) {
		var search = this;

		if ( !items.length ) {
			return;
		}

		// Add method to a queue; this queue will only run when the widget
		// is visible
		this.layoutQueue.push( function () {
			var i, j, ilen, jlen, itemWidth, row, effectiveWidth,
				resizeFactor,
				maxRowWidth = search.results.$element.width() - 15;

			// Go over the added items
			row = search.getAvailableRow();
			for ( i = 0, ilen = items.length; i < ilen; i++ ) {

				// Check item has just been added
				if ( items[ i ].row !== null ) {
					continue;
				}

				itemWidth = items[ i ].$element.outerWidth( true );

				// Add items to row until it is full
				if ( search.rows[ row ].width + itemWidth >= maxRowWidth ) {
					// Mark this row as full
					search.rows[ row ].isFull = true;
					search.rows[ row ].$element.attr( 'data-full', true );

					// Find the resize factor
					effectiveWidth = search.rows[ row ].width;
					resizeFactor = maxRowWidth / effectiveWidth;

					search.rows[ row ].$element.attr( 'data-effectiveWidth', effectiveWidth );
					search.rows[ row ].$element.attr( 'data-resizeFactor', resizeFactor );
					search.rows[ row ].$element.attr( 'data-row', row );

					// Resize all images in the row to fit the width
					for ( j = 0, jlen = search.rows[ row ].items.length; j < jlen; j++ ) {
						search.rows[ row ].items[ j ].resizeThumb( resizeFactor );
					}

					// find another row
					row = search.getAvailableRow();
				}

				// Add the cumulative
				search.rows[ row ].width += itemWidth;

				// Store reference to the item and to the row
				search.rows[ row ].items.push( items[ i ] );
				items[ i ].setRow( row );

				// Append the item
				search.rows[ row ].$element.append( items[ i ].$element );

			}

			// If we have less than 4 rows, call for more images
			if ( search.rows.length < 4 ) {
				search.queryMediaQueue();
			}
		} );
		this.runLayoutQueue();
	};

	/**
	 * Run layout methods from the queue only if the element is visible.
	 */
	mw.widgets.MediaSearchWidget.prototype.runLayoutQueue = function () {
		var i, len;

		// eslint-disable-next-line no-jquery/no-sizzle
		if ( this.$element.is( ':visible' ) ) {
			for ( i = 0, len = this.layoutQueue.length; i < len; i++ ) {
				this.layoutQueue.pop()();
			}
		}
	};

	/**
	 * Respond to removing results event in the results widget.
	 * Clear the relevant rows.
	 *
	 * @param {OO.ui.OptionWidget[]} items Removed items
	 */
	mw.widgets.MediaSearchWidget.prototype.onResultsRemove = function ( items ) {
		if ( items.length > 0 ) {
			// In the case of the media search widget, if any items are removed
			// all are removed (new search)
			this.resetRows();
			this.currentItemCache = [];
		}
	};

	/**
	 * Set language for the search results.
	 *
	 * @param {string} lang Language
	 */
	mw.widgets.MediaSearchWidget.prototype.setLang = function ( lang ) {
		this.lang = lang;
		this.searchQueue.setLang( lang );
	};

	/**
	 * Get language for the search results.
	 *
	 * @return {string} lang Language
	 */
	mw.widgets.MediaSearchWidget.prototype.getLang = function () {
		return this.lang;
	};
}() );
