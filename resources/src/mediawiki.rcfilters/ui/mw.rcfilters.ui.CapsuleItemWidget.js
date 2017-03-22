( function ( mw, $ ) {
	/**
	 * Extend OOUI's CapsuleItemWidget to also display a popup on hover.
	 *
	 * @class
	 * @extends OO.ui.CapsuleItemWidget
	 * @mixins OO.ui.mixin.PopupElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FilterItem} model Item model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.CapsuleItemWidget = function MwRcfiltersUiCapsuleItemWidget( controller, model, config ) {
		// Configuration initialization
		config = config || {};

		this.controller = controller;
		this.model = model;
		this.popupLabel = new OO.ui.LabelWidget();
		this.$overlay = config.$overlay || this.$element;
		this.positioned = false;
		this.popupTimeoutShow = null;
		this.popupTimeoutHide = null;

		// Parent constructor
		mw.rcfilters.ui.CapsuleItemWidget.parent.call( this, $.extend( {
			data: this.model.getName(),
			label: this.model.getLabel()
		}, config ) );

		// Mixin constructors
		OO.ui.mixin.PopupElement.call( this, $.extend( {
			popup: {
				padded: false,
				align: 'center',
				position: 'above',
				$content: $( '<div>' )
					.addClass( 'mw-rcfilters-ui-capsuleItemWidget-popup-content' )
					.append( this.popupLabel.$element ),
				$floatableContainer: this.$element,
				classes: [ 'mw-rcfilters-ui-capsuleItemWidget-popup' ]
			}
		}, config ) );

		this.$highlight = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget-highlight' );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );

		// Initialization
		this.$overlay.append( this.popup.$element );
		this.$element
			.prepend( this.$highlight )
			.attr( 'aria-haspopup', 'true' )
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget' )
			.on( 'mousedown', this.onMouseDown.bind( this ) )
			.on( 'mouseenter', this.onMouseEnter.bind( this ) )
			.on( 'mouseleave', this.onMouseLeave.bind( this ) );

		this.setCurrentMuteState();
		this.setHighlightColor();
	};

	OO.inheritClass( mw.rcfilters.ui.CapsuleItemWidget, OO.ui.CapsuleItemWidget );
	OO.mixinClass( mw.rcfilters.ui.CapsuleItemWidget, OO.ui.mixin.PopupElement );

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onModelUpdate = function () {
		this.setCurrentMuteState();

		this.setHighlightColor();
	};

	/**
	 * Override mousedown event to prevent its propagation to the parent,
	 * since the parent (the multiselect widget) focuses the popup when its
	 * mousedown event is fired.
	 *
	 * @param {jQuery.Event} e Event
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onMouseDown = function ( e ) {
		e.stopPropagation();
	};

	/**
	 * Emit a click event when the capsule is clicked so we can aggregate this
	 * in the parent (the capsule)
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onClick = function () {
		this.emit( 'click' );
	};

	/**
	 * Override the event listening to the item close button click
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onCloseClick = function () {
		var element = this.getElementGroup();

		if ( element && $.isFunction( element.removeItems ) ) {
			element.removeItems( [ this ] );
		}

		// Respond to user removing the filter
		this.controller.clearFilter( this.model.getName() );
	};

	mw.rcfilters.ui.CapsuleItemWidget.prototype.setHighlightColor = function () {
		var selectedColor = this.model.isHighlightEnabled() ? this.model.getHighlightColor() : null;

		this.$highlight
			.attr( 'data-color', selectedColor )
			.toggleClass(
				'mw-rcfilters-ui-capsuleItemWidget-highlight-highlighted',
				!!selectedColor
			);
	};

	/**
	 * Set the current mute state for this item
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.setCurrentMuteState = function () {
		this.$element
			.toggleClass(
				'mw-rcfilters-ui-capsuleItemWidget-muted',
				!this.model.isSelected() ||
				this.model.isIncluded() ||
				this.model.isFullyCovered()
			)
			.toggleClass(
				'mw-rcfilters-ui-capsuleItemWidget-conflicted',
				this.model.isConflicted()
			);
	};

	/**
	 * Respond to mouse enter event
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onMouseEnter = function () {
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
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onMouseLeave = function () {
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
	mw.rcfilters.ui.CapsuleItemWidget.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected !== undefined ? isSelected : !this.selected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;

			this.$element.toggleClass( 'mw-rcfilters-ui-capsuleItemWidget-selected', this.selected );
		}
	};

	/**
	 * Remove and destroy external elements of this widget
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.destroy = function () {
		// Destroy the popup
		this.popup.$element.detach();

		// Disconnect events
		this.model.disconnect( this );
		this.closeButton.disconnect( this );
	};
}( mediaWiki, jQuery ) );
