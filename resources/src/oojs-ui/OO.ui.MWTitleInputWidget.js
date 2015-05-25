( function ( $, mw ) {

/**
 * Creates an OO.ui.MWTitleInputWidget object.
 *
 * @class
 * @extends OO.ui.TextInputWidget
 * @mixins OO.ui.LookupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {number} [namespace] Namespace to prepend to queries
 */
OO.ui.MWTitleInputWidget = function OoUiMWTitleInputWidget( config ) {
	// Config initialization
	config = config || {};

	// Parent constructor
	OO.ui.TextInputWidget.call( this, config );

	// Mixin constructors
	OO.ui.LookupElement.call( this, config );

	// Properties
	this.namespace = config.namespace || null;

	// Initialization
	this.$element.addClass( 'oo-ui-mwTitleInputWidget' );
	this.lookupMenu.$element.addClass( 'oo-ui-mwTitleInputWidget-menu' );
};

/* Inheritance */

OO.inheritClass( OO.ui.MWTitleInputWidget, OO.ui.TextInputWidget );

OO.mixinClass( OO.ui.MWTitleInputWidget, OO.ui.LookupElement );

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.MWTitleInputWidget.prototype.onLookupMenuItemChoose = function ( item ) {
	this.closeLookupMenu();
	this.setLookupsDisabled( true );
	this.setValue( item.getData() );
	this.setLookupsDisabled( false );
};

/**
 * @inheritdoc
 */
OO.ui.MWTitleInputWidget.prototype.getLookupRequest = function () {
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
OO.ui.MWTitleInputWidget.prototype.getLookupCacheDataFromResponse = function ( data ) {
	return data[1] || [];
};

/**
 * @inheritdoc
 */
OO.ui.MWTitleInputWidget.prototype.getLookupMenuOptionsFromData = function ( data ) {
	var i, len, title, value,
		items = [],
		matchingPages = data,
		linkCacheUpdate = {};

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
OO.ui.MWTitleInputWidget.prototype.getTitle = function () {
	var title = this.getValue(),
		// mw.Title doesn't handle null well
		titleObj = mw.Title.newFromText( title, this.namespace !== null ? this.namespace : undefined );

	return titleObj;
};

/**
 * @inheritdoc
 */
OO.ui.MWTitleInputWidget.prototype.isValid = function () {
	return $.Deferred().resolve( !!this.getTitle() ).promise();
};

}( jQuery, mediaWiki ) );
