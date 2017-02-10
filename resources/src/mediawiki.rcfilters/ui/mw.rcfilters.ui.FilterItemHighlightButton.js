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
		mw.rcfilters.ui.FilterItemHighlightButton.parent.call( this, $.extend( {}, config, {
			icon: 'flag',
			indicator: 'down',
			popup: {
				anchor: false,
				padded: true,
				width: '415px',
				$content: this.colorPickerWidget.$element
			}
		} ) );

		this.controller = controller;
		this.model = model;

		// Event
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.colorPickerWidget.connect( this, { chooseColor: 'onChooseColor' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterItemHighlightButton' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterItemHighlightButton, OO.ui.PopupButtonWidget );

	/* Methods */

	/**
	 * Respond to item model update event
	 */
	mw.rcfilters.ui.FilterItemHighlightButton.prototype.onModelUpdate = function () {
		this.$button.css( 'background-color', this.model.getHighlightColor() || '' );
	};

	mw.rcfilters.ui.FilterItemHighlightButton.prototype.onChooseColor = function () {
		this.popup.toggle( false );
	};
}( mediaWiki, jQuery ) );
