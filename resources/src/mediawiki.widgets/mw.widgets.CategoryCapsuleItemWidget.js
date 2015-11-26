/*!
 * MediaWiki Widgets - CategoryCapsuleItemWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * @class mw.widgets.PageExistenceCache
	 * @private
	 * @param {mw.Api} [api]
	 */
	function PageExistenceCache( api ) {
		this.api = api || new mw.Api();
		this.processExistenceCheckQueueDebounced = OO.ui.debounce( this.processExistenceCheckQueue );
		this.currentRequest = null;
		this.existenceCache = {};
		this.existenceCheckQueue = {};
	}

	/**
	 * Check for existence of pages in the queue.
	 *
	 * @private
	 */
	PageExistenceCache.prototype.processExistenceCheckQueue = function () {
		var queue, titles;
		if ( this.currentRequest ) {
			// Don't fire off a million requests at the same time
			this.currentRequest.always( function () {
				this.currentRequest = null;
				this.processExistenceCheckQueueDebounced();
			}.bind( this ) );
			return;
		}
		queue = this.existenceCheckQueue;
		this.existenceCheckQueue = {};
		titles = Object.keys( queue ).filter( function ( title ) {
			if ( this.existenceCache.hasOwnProperty( title ) ) {
				queue[ title ].resolve( this.existenceCache[ title ] );
			}
			return !this.existenceCache.hasOwnProperty( title );
		}.bind( this ) );
		if ( !titles.length ) {
			return;
		}
		this.currentRequest = this.api.get( {
			action: 'query',
			prop: [ 'info' ],
			titles: titles
		} ).done( function ( response ) {
			var index, curr, title;
			for ( index in response.query.pages ) {
				curr = response.query.pages[ index ];
				title = new ForeignTitle( curr.title ).getPrefixedText();
				this.existenceCache[ title ] = curr.missing === undefined;
				queue[ title ].resolve( this.existenceCache[ title ] );
			}
		}.bind( this ) );
	};

	/**
	 * Register a request to check whether a page exists.
	 *
	 * @private
	 * @param {mw.Title} title
	 * @return {jQuery.Promise} Promise resolved with true if the page exists or false otherwise
	 */
	PageExistenceCache.prototype.checkPageExistence = function ( title ) {
		var key = title.getPrefixedText();
		if ( !this.existenceCheckQueue[ key ] ) {
			this.existenceCheckQueue[ key ] = $.Deferred();
		}
		this.processExistenceCheckQueueDebounced();
		return this.existenceCheckQueue[ key ].promise();
	};

	/**
	 * @class mw.widgets.ForeignTitle
	 * @private
	 * @extends mw.Title
	 *
	 * @constructor
	 * @inheritdoc
	 */
	function ForeignTitle() {
		ForeignTitle.parent.apply( this, arguments );
	}
	OO.inheritClass( ForeignTitle, mw.Title );
	ForeignTitle.prototype.getNamespacePrefix = function () {
		// We only need to handle categories here...
		return 'Category:'; // HACK
	};

	/**
	 * @class mw.widgets.CategoryCapsuleItemWidget
	 *
	 * Category selector capsule item widget. Extends OO.ui.CapsuleItemWidget with the ability to link
	 * to the given page, and to show its existence status (i.e., whether it is a redlink).
	 *
	 * @uses mw.Api
	 * @extends OO.ui.CapsuleItemWidget
	 *
	 * @constructor
	 * @param {Object} config Configuration options
	 * @cfg {mw.Title} title Page title to use (required)
	 * @cfg {string} [apiUrl] API URL, if not the current wiki's API
	 */
	mw.widgets.CategoryCapsuleItemWidget = function MWWCategoryCapsuleItemWidget( config ) {
		// Parent constructor
		mw.widgets.CategoryCapsuleItemWidget.parent.call( this, $.extend( {
			data: config.title.getMainText(),
			label: config.title.getMainText()
		}, config ) );

		// Properties
		this.title = config.title;
		this.apiUrl = config.apiUrl || '';
		this.$link = $( '<a>' )
			.text( this.label )
			.attr( 'target', '_blank' )
			.on( 'click', function ( e ) {
				// CapsuleMultiSelectWidget really wants to prevent you from clicking the link, don't let it
				e.stopPropagation();
			} );

		// Initialize
		this.setMissing( false );
		this.$label.replaceWith( this.$link );
		this.setLabelElement( this.$link );

		/*jshint -W024*/
		if ( !this.constructor.static.pageExistenceCaches[ this.apiUrl ] ) {
			this.constructor.static.pageExistenceCaches[ this.apiUrl ] =
				new PageExistenceCache( new mw.ForeignApi( this.apiUrl ) );
		}
		this.constructor.static.pageExistenceCaches[ this.apiUrl ]
			.checkPageExistence( new ForeignTitle( this.title.getPrefixedText() ) )
			.done( function ( exists ) {
				this.setMissing( !exists );
			}.bind( this ) );
		/*jshint +W024*/
	};

	/* Setup */

	OO.inheritClass( mw.widgets.CategoryCapsuleItemWidget, OO.ui.CapsuleItemWidget );

	/* Static Properties */

	/*jshint -W024*/
	/**
	 * Map of API URLs to PageExistenceCache objects.
	 *
	 * @static
	 * @inheritable
	 * @property {Object}
	 */
	mw.widgets.CategoryCapsuleItemWidget.static.pageExistenceCaches = {
		'': new PageExistenceCache()
	};
	/*jshint +W024*/

	/* Methods */

	/**
	 * Update label link href and CSS classes to reflect page existence status.
	 *
	 * @private
	 * @param {boolean} missing Whether the page is missing (does not exist)
	 */
	mw.widgets.CategoryCapsuleItemWidget.prototype.setMissing = function ( missing ) {
		var
			title = new ForeignTitle( this.title.getPrefixedText() ), // HACK
			prefix = this.apiUrl.replace( '/w/api.php', '' ); // HACK

		if ( !missing ) {
			this.$link
				.attr( 'href', prefix + title.getUrl() )
				.removeClass( 'new' );
		} else {
			this.$link
				.attr( 'href', prefix + title.getUrl( { action: 'edit', redlink: 1 } ) )
				.addClass( 'new' );
		}
	};

}( jQuery, mediaWiki ) );
