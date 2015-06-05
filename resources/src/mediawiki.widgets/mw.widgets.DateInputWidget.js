/*!
 * MediaWiki Widgets – DateInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {
	/**
	 * Creates an mw.widgets.DateInputWidget object.
	 *
	 * @class
	 * @extends OO.ui.TextInputWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} [namespace] Namespace to prepend to queries
	 */
	mw.widgets.DateInputWidget = function MWWDateInputWidget( config ) {
		// Config initialization
		config = config || {};

		// Parent constructor
		OO.ui.TextInputWidget.call( this, config );

		// Properties
		this.namespace = config.namespace || null;
		this.calendar = new mw.widgets.CalendarWidget( { precision: config.precision } );

		// Initialization
		this.$element.addClass( 'mw-widget-DateInputWidget' );
		this.lookupMenu.$element.addClass( 'mw-widget-DateInputWidget-menu' );
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.DateInputWidget, OO.ui.TextInputWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.onLookupMenuItemChoose = function ( item ) {
		this.closeLookupMenu();
		this.setLookupsDisabled( true );
		this.setValue( item.getData() );
		this.setLookupsDisabled( false );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.getLookupRequest = function () {
		var value = this.value;

		// Prefix with default namespace name
		if ( this.namespace !== null && mw.Title.newFromText( value, this.namespace ) ) {
			value = mw.Title.newFromText( value, this.namespace ).getPrefixedText();
		}

		// Dont send leading ':' to open search
		if ( value.charAt( 0 ) === ':' ) {
			value = value.slice( 1 );
		}

		return new mw.Api().get( {
			action: 'opensearch',
			search: value,
			suggest: ''
		} );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.getLookupCacheDataFromResponse = function ( data ) {
		return data[1] || [];
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.getLookupMenuOptionsFromData = function ( data ) {
		var i, len, title, value,
			items = [],
			matchingPages = data;

		// Matching pages
		if ( matchingPages && matchingPages.length ) {
			for ( i = 0, len = matchingPages.length; i < len; i++ ) {
				title = new mw.Title( matchingPages[i] );
				if ( this.namespace !== null ) {
					value = title.getRelativeText( this.namespace );
				} else {
					value = title.getPrefixedText();
				}
				items.push( new OO.ui.MenuOptionWidget( {
					data: value,
					label: value
				} ) );
			}
		}

		return items;
	};

	/**
	 * Get title object corresponding to #getValue
	 *
	 * @returns {mw.Title|null} Title object, or null if value is invalid
	 */
	mw.widgets.DateInputWidget.prototype.getTitle = function () {
		var title = this.getValue(),
			// mw.Title doesn't handle null well
			titleObj = mw.Title.newFromText( title, this.namespace !== null ? this.namespace : undefined );

		return titleObj;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.isValid = function () {
		return $.Deferred().resolve( !!this.getTitle() ).promise();
	};

}( jQuery, mediaWiki ) );
