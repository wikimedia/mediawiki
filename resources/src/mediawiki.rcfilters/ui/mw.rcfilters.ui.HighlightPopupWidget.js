( function () {
	/**
	 * A popup containing a color picker, for setting highlight colors.
	 *
	 * @extends OO.ui.PopupWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.HighlightPopupWidget = function MwRcfiltersUiHighlightPopupWidget( controller, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.HighlightPopupWidget.parent.call( this, $.extend( {
			autoClose: true,
			anchor: false,
			padded: true,
			align: 'backwards',
			horizontalPosition: 'end',
			width: 290
		}, config ) );

		this.colorPicker = new mw.rcfilters.ui.HighlightColorPickerWidget( controller );

		this.colorPicker.connect( this, { chooseColor: 'onChooseColor' } );

		this.$body.append( this.colorPicker.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.HighlightPopupWidget, OO.ui.PopupWidget );

	/* Methods */

	/**
	 * Set the button (or other widget) that this popup should hang off.
	 *
	 * @param {OO.ui.Widget} widget Widget the popup should orient itself to
	 */
	mw.rcfilters.ui.HighlightPopupWidget.prototype.setAssociatedButton = function ( widget ) {
		this.setFloatableContainer( widget.$element );
		this.$autoCloseIgnore = widget.$element;
	};

	/**
	 * Set the filter item that this popup should control the highlight color for.
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item
	 */
	mw.rcfilters.ui.HighlightPopupWidget.prototype.setFilterItem = function ( item ) {
		this.colorPicker.setFilterItem( item );
	};

	/**
	 * When the user chooses a color in the color picker, close the popup.
	 */
	mw.rcfilters.ui.HighlightPopupWidget.prototype.onChooseColor = function () {
		this.toggle( false );
	};

}() );
