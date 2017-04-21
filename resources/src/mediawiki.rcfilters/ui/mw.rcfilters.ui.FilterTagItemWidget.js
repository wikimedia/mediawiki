( function ( mw, $ ) {
	/**
	 * Extend OOUI's FilterTagItemWidget to also display a popup on hover.
	 *
	 * @class
	 * @extends OO.ui.FilterTagItemWidget
	 * @mixins OO.ui.mixin.PopupElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FilterItem} model Item model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterTagItemWidget = function MwRcfiltersUiFilterTagItemWidget( controller, model, config ) {
		// Configuration initialization
		config = config || {};

		this.controller = controller;
		this.model = model;
		this.selected = false;

		mw.rcfilters.ui.FilterTagItemWidget.parent.call( this, $.extend( {
			data: this.model.getName(),
			label: this.model.getLabel()
		}, config ) );

		this.$overlay = config.$overlay || this.$element;
		this.popupLabel = new OO.ui.LabelWidget();

		// Mixin constructors
		OO.ui.mixin.PopupElement.call( this, $.extend( {
			popup: {
				padded: false,
				align: 'center',
				position: 'above',
				$content: $( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterTagItemWidget-popup-content' )
					.append( this.popupLabel.$element ),
				$floatableContainer: this.$element,
				classes: [ 'mw-rcfilters-ui-filterTagItemWidget-popup' ]
			}
		}, config ) );

		this.positioned = false;
		this.popupTimeoutShow = null;
		this.popupTimeoutHide = null;

		this.$highlight = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterTagItemWidget-highlight' );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );

		// Initialization
		this.$overlay.append( this.popup.$element );
		this.$element
			.addClass( 'mw-rcfilters-ui-filterTagItemWidget' )
			.prepend( this.$highlight )
			.attr( 'aria-haspopup', 'true' )
			.on( 'mouseenter', this.onMouseEnter.bind( this ) )
			.on( 'mouseleave', this.onMouseLeave.bind( this ) );

		this.setCurrentMuteState();
		this.setHighlightColor();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterTagItemWidget, OO.ui.TagItemWidget );
	OO.mixinClass( mw.rcfilters.ui.FilterTagItemWidget, OO.ui.mixin.PopupElement );

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.onModelUpdate = function () {
		this.setCurrentMuteState();

		this.setHighlightColor();
	};

	mw.rcfilters.ui.FilterTagItemWidget.prototype.setHighlightColor = function () {
		var selectedColor = this.model.isHighlightEnabled() ? this.model.getHighlightColor() : null;

		this.$highlight
			.attr( 'data-color', selectedColor )
			.toggleClass(
				'mw-rcfilters-ui-filterTagItemWidget-highlight-highlighted',
				!!selectedColor
			);
	};

	/**
	 * Set the current mute state for this item
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.setCurrentMuteState = function () {
		this.setFlags( {
			muted: (
				!this.model.isSelected() ||
				this.model.isIncluded() ||
				this.model.isFullyCovered()
			),
			invalid: this.model.isSelected() && this.model.isConflicted()
		} );
	};

	/**
	 * Respond to mouse enter event
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.onMouseEnter = function () {
		var labelText = this.model.getStateMessage();

		if ( labelText ) {
			this.popupLabel.setLabel( labelText );

			if ( !this.positioned ) {
				// Recalculate anchor position to be center of the capsule item
				this.popup.$anchor.css( 'margin-left', ( this.$element.width() / 2 ) );
				this.positioned = true;
			}

			// Set timeout for the popup to show
			this.popupTimeoutShow = setTimeout( function () {
				this.popup.toggle( true );
			}.bind( this ), 500 );

			// Cancel the hide timeout
			clearTimeout( this.popupTimeoutHide );
			this.popupTimeoutHide = null;
		}
	};

	/**
	 * Respond to mouse leave event
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.onMouseLeave = function () {
		this.popupTimeoutHide = setTimeout( function () {
			this.popup.toggle( false );
		}.bind( this ), 250 );

		// Clear the show timeout
		clearTimeout( this.popupTimeoutShow );
		this.popupTimeoutShow = null;
	};

	/**
	 * Set selected state on this widget
	 *
	 * @param {boolean} [isSelected] Widget is selected
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected !== undefined ? isSelected : !this.selected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;

			this.$element.toggleClass( 'mw-rcfilters-ui-filterTagItemWidget-selected', this.selected );
		}
	};

	/**
	 * Get the selected state of this widget
	 *
	 * @return {boolean} Tag is selected
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.isSelected = function () {
		return this.selected;
	};

	/**
	 * Get item name
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.getName = function () {
		return this.model.getName();
	};

	/**
	 * Remove and destroy external elements of this widget
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.destroy = function () {
		// Destroy the popup
		this.popup.$element.detach();

		// Disconnect events
		this.model.disconnect( this );
		this.closeButton.disconnect( this );
	};
}( mediaWiki, jQuery ) );
