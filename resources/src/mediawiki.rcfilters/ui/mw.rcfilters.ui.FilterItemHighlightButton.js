( function ( mw, $ ) {
	/**
	 * A button to configure highlight for a filter item
	 *
	 * @extends OO.ui.PopupButtonWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
	 * @param {mw.rcfilters.ui.MenuSelectWidget} menu Parent menu
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.FilterItemHighlightButton = function MwRcfiltersUiFilterItemHighlightButton( controller, model, menu, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterItemHighlightButton.parent.call( this, $.extend( true, {}, config, {
			icon: 'highlight',
			indicator: 'down'
		} ) );

		this.controller = controller;
		this.model = model;
		this.popup = menu.highlightPopup;
		this.colorPicker = menu.highlightColorPicker;

		// Event
		this.model.connect( this, { update: 'updateUiBasedOnModel' } );
		// This lives inside a MenuOptionWidget, which intercepts mousedown
		// to select the item. We want to prevent that when we click the highlight
		// button
		this.$element.on( 'mousedown', function ( e ) { e.stopPropagation(); } );

		this.updateUiBasedOnModel();

		this.$element
			.addClass( 'mw-rcfilters-ui-filterItemHighlightButton' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterItemHighlightButton, OO.ui.PopupButtonWidget );

	/* Static Properties */

	/**
	 * @static
	 */
	mw.rcfilters.ui.FilterItemHighlightButton.static.cancelButtonMouseDownEvents = true;

	/* Methods */

	mw.rcfilters.ui.FilterItemHighlightButton.prototype.onAction = function () {
		// Attach the shared popup to this button
		this.popup.setFloatableContainer( this.$element );
		// TODO add setAutoCloseIgnore to PopupWidget upstream
		this.popup.$autoCloseIgnore = this.$element;
		this.colorPicker.setItem( this.model );
		this.colorPicker.connect( this, { chooseColor: 'onChooseColor' } );

		// Parent method
		mw.rcfilters.ui.FilterItemHighlightButton.parent.prototype.onAction.call( this );
	};

	/**
	 * Respond to item model update event
	 */
	mw.rcfilters.ui.FilterItemHighlightButton.prototype.updateUiBasedOnModel = function () {
		var currentColor = this.model.getHighlightColor(),
			widget = this;

		this.$icon.toggleClass(
			'mw-rcfilters-ui-filterItemHighlightButton-circle',
			currentColor !== null
		);

		mw.rcfilters.HighlightColors.forEach( function ( c ) {
			widget.$icon
				.toggleClass(
					'mw-rcfilters-ui-filterItemHighlightButton-circle-color-' + c,
					c === currentColor
				);
		} );
	};

	mw.rcfilters.ui.FilterItemHighlightButton.prototype.onChooseColor = function () {
		// Close the popup and disconnect it from this button
		this.popup.toggle( false );
		this.popup.disconnect( this );
		this.popup.$autoCloseIgnore = undefined;
	};
}( mediaWiki, jQuery ) );
