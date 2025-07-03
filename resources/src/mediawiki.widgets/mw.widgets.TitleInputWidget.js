/*!
 * MediaWiki Widgets - TitleInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	const trimByteLength = require( 'mediawiki.String' ).trimByteLength;

	/**
	 * @classdesc Title input widget.
	 *
	 * @class
	 * @extends OO.ui.TextInputWidget
	 * @mixes mw.widgets.TitleWidget
	 * @mixes OO.ui.mixin.LookupElement
	 *
	 * @constructor
	 * @description Create an mw.widgets.TitleInputWidget object.
	 * @param {Object} [config] Configuration options
	 * @param {boolean} [config.suggestions=true] Display search suggestions
	 * @param {RegExp|Function|string} [config.validate] Perform title validation
	 */
	mw.widgets.TitleInputWidget = function MwWidgetsTitleInputWidget( config = {} ) {
		// Parent constructor
		mw.widgets.TitleInputWidget.super.call( this, Object.assign( {}, config, {
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
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.getQueryValue = function () {
		return this.getValue();
	};

	/**
	 * @inheritdoc
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
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupCacheDataFromResponse = function ( response ) {
		return response.query || {};
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.getLookupMenuOptionsFromData = function ( response ) {
		return this.getOptionsFromData( response );
	};

	/**
	 * Handle menu item 'choose' event, updating the text input value to the value of the clicked item.
	 *
	 * TODO: Replace this with an override of onLookupMenuChoose()
	 *
	 * @param {OO.ui.MenuOptionWidget} item Selected item
	 */
	mw.widgets.TitleInputWidget.prototype.onLookupMenuChoose = function ( item ) {
		this.closeLookupMenu();
		this.setLookupsDisabled( true );
		this.setValue( item.getData() );
		this.setLookupsDisabled( !this.suggestions );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.focus = function () {
		// Prevent programmatic focus from opening the menu
		this.setLookupsDisabled( true );

		// Parent method
		const retval = mw.widgets.TitleInputWidget.super.prototype.focus.apply( this, arguments );

		this.setLookupsDisabled( !this.suggestions );

		return retval;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleInputWidget.prototype.cleanUpValue = function ( value ) {
		// Parent method
		value = mw.widgets.TitleInputWidget.super.prototype.cleanUpValue.call( this, value );

		return trimByteLength( this.value, value, this.maxLength, ( val ) => {
			const title = this.getMWTitle( val );
			return title ? title.getMain() : val;
		} ).newVal;
	};

}() );
