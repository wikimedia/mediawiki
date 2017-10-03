( function ( mw, $ ) {
	/**
	 * A button to configure highlight for a filter item
	 *
	 * @extends OO.ui.PopupButtonWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.FilterItemHighlightButton = function MwRcfiltersUiFilterItemHighlightButton( controller, model, config ) {
		config = config || {};

		this.colorPickerWidget = new mw.rcfilters.ui.HighlightColorPickerWidget( controller, model );

		// Parent
		mw.rcfilters.ui.FilterItemHighlightButton.parent.call( this, $.extend( true, {}, config, {
			icon: 'highlight',
			indicator: 'down',
			popup: {
				anchor: false,
				padded: true,
				align: 'backwards',
				horizontalPosition: 'end',
				$floatableContainer: this.$element,
				width: 290,
				$content: this.colorPickerWidget.$element
			}
		} ) );

		this.controller = controller;
		this.model = model;

		// Event
		this.model.connect( this, { update: 'updateUiBasedOnModel' } );
		this.colorPickerWidget.connect( this, { chooseColor: 'onChooseColor' } );
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
		this.popup.toggle( false );
	};
}( mediaWiki, jQuery ) );
