/*!
 * MediaWiki Widgets - CategoryCapsuleItemWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * @class mw.widgets.CategoryCapsuleItemWidget
	 */

	var processExistenceCheckQueueDebounced,
		api = new mw.Api(),
		currentRequest = null,
		existenceCache = {},
		existenceCheckQueue = {};

	// The existence checking code really could be refactored into a separate class.

	/**
	 * @private
	 */
	function processExistenceCheckQueue() {
		var queue, titles;
		if ( currentRequest ) {
			// Don't fire off a million requests at the same time
			currentRequest.always( function () {
				currentRequest = null;
				processExistenceCheckQueueDebounced();
			} );
			return;
		}
		queue = existenceCheckQueue;
		existenceCheckQueue = {};
		titles = Object.keys( queue ).filter( function ( title ) {
			if ( existenceCache.hasOwnProperty( title ) ) {
				queue[ title ].resolve( existenceCache[ title ] );
			}
			return !existenceCache.hasOwnProperty( title );
		} );
		if ( !titles.length ) {
			return;
		}
		currentRequest = api.get( {
			action: 'query',
			prop: [ 'info' ],
			titles: titles
		} ).done( function ( response ) {
			var index, curr, title;
			for ( index in response.query.pages ) {
				curr = response.query.pages[ index ];
				title = mw.Title.newFromText( curr.title ).getPrefixedText();
				existenceCache[ title ] = curr.missing === undefined;
				queue[ title ].resolve( existenceCache[ title ] );
			}
		} );
	}

	processExistenceCheckQueueDebounced = OO.ui.debounce( processExistenceCheckQueue );

	/**
	 * Register a request to check whether a page exists.
	 *
	 * @private
	 * @param {mw.Title} title
	 * @return {jQuery.Promise} Promise resolved with true if the page exists or false otherwise
	 */
	function checkPageExistence( title ) {
		var key = title.getPrefixedText();
		if ( !existenceCheckQueue[ key ] ) {
			existenceCheckQueue[ key ] = $.Deferred();
		}
		processExistenceCheckQueueDebounced();
		return existenceCheckQueue[ key ].promise();
	}

	/**
	 * Category selector capsule item widget. Extends OO.ui.CapsuleItemWidget with the ability to link
	 * to the given page, and to show its existence status (i.e., whether it is a redlink).
	 *
	 * @uses mw.Api
	 * @extends OO.ui.CapsuleItemWidget
	 *
	 * @constructor
	 * @param {Object} config Configuration options
	 * @cfg {mw.Title} title Page title to use (required)
	 */
	mw.widgets.CategoryCapsuleItemWidget = function MWWCategoryCapsuleItemWidget( config ) {
		// Parent constructor
		mw.widgets.CategoryCapsuleItemWidget.parent.call( this, $.extend( {
			data: config.title.getMainText(),
			label: config.title.getMainText()
		}, config ) );

		// Properties
		this.title = config.title;
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
		checkPageExistence( this.title ).done( function ( exists ) {
			this.setMissing( !exists );
		}.bind( this ) );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.CategoryCapsuleItemWidget, OO.ui.CapsuleItemWidget );

	/* Methods */

	/**
	 * Update label link href and CSS classes to reflect page existence status.
	 *
	 * @private
	 * @param {boolean} missing Whether the page is missing (does not exist)
	 */
	mw.widgets.CategoryCapsuleItemWidget.prototype.setMissing = function ( missing ) {
		if ( !missing ) {
			this.$link
				.attr( 'href', this.title.getUrl() )
				.removeClass( 'new' );
		} else {
			this.$link
				.attr( 'href', this.title.getUrl( { action: 'edit', redlink: 1 } ) )
				.addClass( 'new' );
		}
	};

}( jQuery, mediaWiki ) );
