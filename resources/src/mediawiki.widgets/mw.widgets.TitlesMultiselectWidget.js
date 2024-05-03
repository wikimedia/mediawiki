/*!
 * MediaWiki Widgets - TitlesMultiselectWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Titles multiselect widget.
	 *
	 * @class
	 * @extends OO.ui.MenuTagMultiselectWidget
	 * @mixes OO.ui.mixin.RequestManager
	 * @mixes OO.ui.mixin.PendingElement
	 * @mixes mw.widgets.TitleWidget
	 *
	 * @constructor
	 * @description Create an mw.widgets.TitlesMultiselectWidget object.
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.TitlesMultiselectWidget = function MwWidgetsTitlesMultiselectWidget( config ) {
		// Parent constructor
		mw.widgets.TitlesMultiselectWidget.super.call( this, $.extend( true,
			{
				allowEditTags: false
			},
			config
		) );

		// Mixin constructors
		mw.widgets.TitleWidget.call( this, $.extend( true, {
			addQueryInput: true,
			highlightSearchQuery: false
		}, config ) );
		OO.ui.mixin.RequestManager.call( this, config );
		OO.ui.mixin.PendingElement.call( this, $.extend( true, {}, config, {
			$pending: this.$handle
		} ) );

		// Validate from mw.widgets.TitleWidget
		this.input.setValidation( this.isQueryValid.bind( this ) );

		// TODO limit max tag length to this.maxLength

		// Initialization
		this.$element
			.addClass( 'mw-widgets-titlesMultiselectWidget' );

		this.menu.$element
			// For consistency, use the same classes as TitleWidget
			// expects for menu results
			.addClass( 'mw-widget-titleWidget-menu' )
			.toggleClass( 'mw-widget-titleWidget-menu-withImages', this.showImages )
			.toggleClass( 'mw-widget-titleWidget-menu-withDescriptions', this.showDescriptions );

		if ( 'name' in config ) {
			// Use this instead of <input type="hidden">, because hidden inputs do not have separate
			// 'value' and 'defaultValue' properties. The script on Special:Preferences
			// (mw.special.preferences.confirmClose) checks this property to see if a field was changed.
			this.$hiddenInput = $( '<textarea>' )
				.addClass( 'oo-ui-element-hidden' )
				.attr( 'name', config.name )
				.appendTo( this.$element );
			// Update with preset values
			// Set the default value (it might be different from just being empty)
			this.$hiddenInput.prop( 'defaultValue', this.getItems().map( function ( item ) {
				return item.getData();
			} ).join( '\n' ) );
			this.on( 'change', function ( items ) {
				this.$hiddenInput.val( items.map( function ( item ) {
					return item.getData();
				} ).join( '\n' ) );
				// Trigger a 'change' event as if a user edited the text
				// (it is not triggered when changing the value from JS code).
				this.$hiddenInput.trigger( 'change' );
			}.bind( this ) );
		}

	};

	/* Setup */

	OO.inheritClass( mw.widgets.TitlesMultiselectWidget, OO.ui.MenuTagMultiselectWidget );
	OO.mixinClass( mw.widgets.TitlesMultiselectWidget, OO.ui.mixin.RequestManager );
	OO.mixinClass( mw.widgets.TitlesMultiselectWidget, OO.ui.mixin.PendingElement );
	OO.mixinClass( mw.widgets.TitlesMultiselectWidget, mw.widgets.TitleWidget );

	/* Methods */

	mw.widgets.TitlesMultiselectWidget.prototype.getQueryValue = function () {
		return this.input.getValue();
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitlesMultiselectWidget.prototype.onInputChange = function () {
		const widget = this;

		this.getRequestData()
			.then( function ( data ) {
				// Reset
				widget.menu.clearItems();
				widget.menu.addItems( widget.getOptionsFromData( data ) );
			} ).always( function () {
				// Parent method
				mw.widgets.TitlesMultiselectWidget.super.prototype.onInputChange.call( widget );
			} );
	};

	/**
	 * We have an empty menu when the input is empty, override the implementation from
	 * MenuTagMultiselectWidget to avoid error and make tags editable.
	 *
	 * Only editable when the input is empty.
	 */
	mw.widgets.TitlesMultiselectWidget.prototype.onTagSelect = function () {
		if ( this.hasInput && !this.input.getValue() ) {
			OO.ui.TagMultiselectWidget.prototype.onTagSelect.apply( this, arguments );
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitlesMultiselectWidget.prototype.getRequestQuery = function () {
		return this.getQueryValue();
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitlesMultiselectWidget.prototype.getRequest = function () {
		return this.getSuggestionsPromise();
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.TitlesMultiselectWidget.prototype.getRequestCacheDataFromResponse = function ( response ) {
		return response.query || {};
	};
}() );
