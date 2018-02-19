/*!
 * MediaWiki Widgets - TitleInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	var trimByteLength = require( 'mediawiki.String' ).trimByteLength;

	/**
	 * Creates an mw.widgets.TitleInputWidget object.
	 *
	 * @class
	 * @extends OO.ui.TextInputWidget
	 * @mixins mw.widgets.TitleWidget
	 * @mixins OO.ui.mixin.LookupElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {boolean} [suggestions=true] Display search suggestions
	 * @cfg {RegExp|Function|string} [validate] Perform title validation
	 */
	mw.widgets.TitleInputWidget = function MwWidgetsTitleInputWidget( config ) {
		config = config || {};

		// Parent constructor
		mw.widgets.TitleInputWidget.parent.call( this, $.extend( {}, config, {
			validate: config.validate !== undefined ? config.validate : this.isQueryValid.bind( this ),
			autocomplete: false
		} ) );

		// Mixin constructors
		mw.widgets.TitleWidget.call( this, config );
		OO.ui.mixin.LookupElement.call( this, config );

		// Properties
		this.suggestions = config.suggestions !== undefined ? config.suggestions : true;

		// Initialization
		this.$element.addClass( 'mw-widget-titleInputWidget' );
		this.lookupMenu.$element.addClass( 'mw-widget-titleWidget-menu' );
		if ( this.showImages ) {
			this.lookupMenu.$element.addClass( 'mw-widget-titleWidget-menu-withImages' );
		}
		if ( this.showDescriptions ) {
			this.lookupMenu.$element.addClass( 'mw-widget-titleWidget-menu-withDescriptions' );
		}
		this.setLookupsDisabled( !this.suggestions );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.TitleInputWidget, OO.ui.TextInputWidget );
	OO.mixinClass( mw.widgets.TitleInputWidget, mw.widgets.TitleWidget );
	OO.mixinClass( mw.widgets.TitleInputWidget, OO.ui.mixin.LookupElement );

	/* Methods */

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 */
	mw.widgets.TitleInputWidget.prototype.getQueryValue = function () {
		return this.getValue();
	};

	/**
	 * @inheritdoc mw.widgets.TitleWidget
	 */
	mw.widgets.TitleInputWidget.prototype.setNamespace = function ( namespace ) {
		// Mixin method
		mw.widgets.TitleWidget.prototype.setNamespace.call( this, namespace );

		this.lookupCache = {};
		this.closeLookupMenu();
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupRequest = function () {
		return this.getSuggestionsPromise();
	};

	/**
	 * @inheritdoc OO.ui.mixin.LookupElement
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupCacheDataFromResponse = function ( response ) {
		return response.query || {};
	};

	/**
	 * @inheritdoc OO.ui.mixin.LookupElement
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupMenuOptionsFromData = function ( response ) {
		return this.getOptionsFromData( response );
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
	mw.widgets.TitleInputWidget.prototype.cleanUpValue = function ( value ) {
		var widget = this;

		// Parent method
		value = mw.widgets.TitleInputWidget.parent.prototype.cleanUpValue.call( this, value );

		return trimByteLength( this.value, value, this.maxLength, function ( value ) {
			var title = widget.getMWTitle( value );
			return title ? title.getMain() : value;
		} ).newVal;
	};

}( jQuery, mediaWiki ) );
