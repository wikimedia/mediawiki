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
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.HighlightColorPickerWidget = function MwRcfiltersUiHighlightColorPickerWidget( controller, model, config ) {
		var colors = [ 'none' ].concat( mw.rcfilters.HighlightColors );
		config = config || {};

		// Parent
		mw.rcfilters.ui.HighlightColorPickerWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
			label: mw.message( 'rcfilters-highlightmenu-title' ).text()
		} ) );

		this.controller = controller;
		this.model = model;

		this.currentSelection = 'none';
		this.buttonSelect = new OO.ui.ButtonSelectWidget( {
			items: colors.map( function ( color ) {
				return new OO.ui.ButtonOptionWidget( {
					icon: color === 'none' ? 'check' : null,
					data: color,
					classes: [
						'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color',
						'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-' + color
					],
					framed: false
				} );
			} ),
			classes: 'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect'
		} );

		// Event
		this.model.connect( this, { update: 'updateUiBasedOnModel' } );
		this.buttonSelect.connect( this, { choose: 'onChooseColor' } );

		this.updateUiBasedOnModel();

		this.$element
			.addClass( 'mw-rcfilters-ui-highlightColorPickerWidget' )
			.append(
				this.$label
					.addClass( 'mw-rcfilters-ui-highlightColorPickerWidget-label' ),
				this.buttonSelect.$element
			);
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
	mw.rcfilters.ui.HighlightColorPickerWidget.prototype.updateUiBasedOnModel = function () {
		this.selectColor( this.model.getHighlightColor() || 'none' );
	};

	/**
	 * Select the color for this widget
	 *
	 * @param {string} color Selected color
	 */
	mw.rcfilters.ui.HighlightColorPickerWidget.prototype.selectColor = function ( color ) {
		var previousItem = this.buttonSelect.findItemFromData( this.currentSelection ),
			selectedItem = this.buttonSelect.findItemFromData( color );

		if ( this.currentSelection !== color ) {
			this.currentSelection = color;

			this.buttonSelect.selectItem( selectedItem );
			if ( previousItem ) {
				previousItem.setIcon( null );
			}

			if ( selectedItem ) {
				selectedItem.setIcon( 'check' );
			}
		}
	};

	mw.rcfilters.ui.HighlightColorPickerWidget.prototype.onChooseColor = function ( button ) {
		var color = button.data;
		if ( color === 'none' ) {
			this.controller.clearHighlightColor( this.model.getName() );
		} else {
			this.controller.setHighlightColor( this.model.getName(), color );
		}
		this.emit( 'chooseColor', color );
	};
}( mediaWiki, jQuery ) );
