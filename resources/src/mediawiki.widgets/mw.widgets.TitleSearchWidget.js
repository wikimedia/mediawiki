/*!
 * MediaWiki Widgets - TitleSearchWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Title search widget.
	 *
	 * @class
	 * @extends OO.ui.SearchWidget
	 * @mixes OO.ui.mixin.RequestManager
	 * @mixes mw.widgets.TitleWidget
	 *
	 * @constructor
	 * @description Create an mw.widgets.TitleSearchWidget object.
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.TitleSearchWidget = function MwWidgetsTitleSearchWidget( config = {} ) {
		// Parent constructor
		mw.widgets.TitleSearchWidget.super.call( this, config );

		// Mixin constructors
		mw.widgets.TitleWidget.call( this, config );
		OO.ui.mixin.RequestManager.call( this, config );

		this.query.setValidation( this.isQueryValid.bind( this ) );

		// Events
		this.results.connect( this, { choose: 'onTitleSearchResultsChoose' } );

		// Initialization
		this.$element.addClass( 'mw-widget-titleSearchWidget' );
		this.results.$element.addClass( 'mw-widget-titleWidget-menu' );
		if ( this.showImages ) {
			this.results.$element.addClass( 'mw-widget-titleWidget-menu-withImages' );
		}
		if ( this.showDescriptions ) {
			this.results.$element.addClass( 'mw-widget-titleWidget-menu-withDescriptions' );
		}
		if ( this.maxLength !== undefined ) {
			this.getQuery().$input.attr( 'maxlength', this.maxLength );
		}
	};

	/* Setup */

	OO.inheritClass( mw.widgets.TitleSearchWidget, OO.ui.SearchWidget );
	OO.mixinClass( mw.widgets.TitleSearchWidget, OO.ui.mixin.RequestManager );
	OO.mixinClass( mw.widgets.TitleSearchWidget, mw.widgets.TitleWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleSearchWidget.prototype.getQueryValue = function () {
		return this.getQuery().getValue();
	};

	/**
	 * Handle choose events from the result widget.
	 *
	 * @param {OO.ui.OptionWidget} item Chosen item
	 */
	mw.widgets.TitleSearchWidget.prototype.onTitleSearchResultsChoose = function ( item ) {
		this.getQuery().setValue( item.getData() );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleSearchWidget.prototype.onQueryChange = function () {
		this.getRequestData().done( ( data ) => {
			if ( this.query.isReadOnly() ) {
				// The request object is always abortable, so just
				// prevent the results from displaying
				return;
			}
			// Parent method
			mw.widgets.TitleSearchWidget.super.prototype.onQueryChange.call( this );
			this.results.addItems( this.getOptionsFromData( data ) );
		} );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleSearchWidget.prototype.getRequestQuery = function () {
		return this.getQueryValue();
	};
	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleSearchWidget.prototype.getRequest = function () {
		return this.getSuggestionsPromise();
	};
	/**
	 * @inheritdoc
	 */
	mw.widgets.TitleSearchWidget.prototype.getRequestCacheDataFromResponse = function ( response ) {
		return response.query || {};
	};

	/**
	 * Check if the widget is read-only.
	 *
	 * @return {boolean}
	 */
	mw.widgets.TitleSearchWidget.prototype.isReadOnly = function () {
		return this.query.isReadOnly();
	};

	/**
	 * Set the read-only state of the widget.
	 *
	 * @param {boolean} readOnly Make input read-only
	 * @chainable
	 * @return {mw.widgets.TitleSearchWidget} The widget, for chaining
	 */
	mw.widgets.TitleSearchWidget.prototype.setReadOnly = function ( readOnly ) {
		this.query.setReadOnly( readOnly );
		if ( readOnly ) {
			// Hide results
			this.results.clearItems();
		}
		return this;
	};

}() );
