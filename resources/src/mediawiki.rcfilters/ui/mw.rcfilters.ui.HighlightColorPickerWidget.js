( function ( mw, $ ) {
	/**
	 * A widget representing a filter item highlight color picker
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.LabelElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.HighlightColorPickerWidget = function MwRcfiltersUiHighlightColorPickerWidget( controller, model, config ) {
		var colors = [ 'none', 'blue', 'green', 'yellow', 'orange', 'red' ];
		config = config || {};

		// Parent
		mw.rcfilters.ui.HighlightColorPickerWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
			label: 'Highlight color'
		} ) );

		this.controller = controller;
		this.model = model;

		this.buttonSelect = new OO.ui.ButtonSelectWidget( {
			items: colors.map( function ( color ) {
				return new OO.ui.ButtonOptionWidget( {
					data: color,
					label: color
				} );
			} )
		} );

		// Event
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.buttonSelect.connect( this, { choose: 'onChooseColor' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-HighlightColorPickerWidget' )
			.append( this.buttonSelect.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.HighlightColorPickerWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.HighlightColorPickerWidget, OO.ui.mixin.LabelElement );

	/* Events */

	/**
	 * @event chooseColor
	 * @param {string} The chosen color
	 *
	 * A color has been chosen
	 */

	/* Methods */

	/**
	 * Respond to item model update event
	 */
	mw.rcfilters.ui.HighlightColorPickerWidget.prototype.onModelUpdate = function () {
		var color = this.model.getColor();
		// todo: update which icon is selected
		this.buttonSelect.getItems().forEach( function ( button ) {
			button.setActive( button.data === color );
		} );
	};

	mw.rcfilters.ui.HighlightColorPickerWidget.prototype.onChooseColor = function ( button ) {
		var color = button.data;
		if ( color === 'none' ) {
			this.controller.clearHighlight( this.model.getName() );
		} else {
			this.controller.chooseHighlightColor( this.model.getName(), color );
		}
		this.emit( 'chooseColor', color );
	};
}( mediaWiki, jQuery ) );
