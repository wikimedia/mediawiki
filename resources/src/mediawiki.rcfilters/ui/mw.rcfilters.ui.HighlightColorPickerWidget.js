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

		this.currentSelection = 'none';
		this.buttonSelect = new OO.ui.ButtonSelectWidget( {
			items: colors.map( function ( color ) {
				return new OO.ui.ButtonOptionWidget( {
					icon: color === 'none' ? 'check' : null,
					data: color,
					// label: color,
					classes: [ 'mw-rcfilters-ui-HighlightColorPickerWidget-buttonSelect-color-' + color ],
					framed: false
				} );
			} ),
			classes: 'mw-rcfilters-ui-HighlightColorPickerWidget-buttonSelect'
		} );

		// Event
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.buttonSelect.connect( this, { choose: 'onChooseColor' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-HighlightColorPickerWidget' )
			.append( this.$label, this.buttonSelect.$element );
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
		var color = this.model.getColor(),
			previousItem = this.buttonSelect.getItemFromData( this.currentSelection ),
			selectedItem = this.buttonSelect.getItemFromData( color );

		if ( this.currentSelection !== color ) {
			this.buttonSelect.selectItem( color );
			this.currentSelection = color;

			if ( previousItem ) {
				previousItem.setIcon( null );
			}

			if ( selectedItem ) {
				selectedItem.setIcon( 'check' );
			}
		}
		// this.buttonSelect.getItems().forEach( function ( button ) {
		// 	button.setActive( button.data === color );
		// } );
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
