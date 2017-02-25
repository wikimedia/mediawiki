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
		var $popupContent = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-capsuleItemWidget-popup-content' ),
			descLabelWidget = new OO.ui.LabelWidget();

		// Configuration initialization
		config = config || {};

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;
		this.positioned = false;

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
				$content: $popupContent
					.append( descLabelWidget.$element ),
				$floatableContainer: this.$element,
				classes: [ 'mw-rcfilters-ui-capsuleItemWidget-popup' ]
			}
		}, config ) );

		// Set initial text for the popup - the description
		descLabelWidget.setLabel( this.model.getDescription() );

		this.$highlight = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget-highlight' );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );

		this.closeButton.$element.on( 'mousedown', this.onCloseButtonMouseDown.bind( this ) );

		// Initialization
		this.$overlay.append( this.popup.$element );
		this.$element
			.prepend( this.$highlight )
			.attr( 'aria-haspopup', 'true' )
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget' )
			.on( 'mouseover', this.onHover.bind( this, true ) )
			.on( 'mouseout', this.onHover.bind( this, false ) );

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
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onCloseButtonMouseDown = function ( e ) {
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
		this.controller.updateFilter( this.model.getName(), false );
		this.controller.clearHighlightColor( this.model.getName() );
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
				this.model.isConflicted() ||
				this.model.isFullyCovered()
			);
	};

	/**
	 * Respond to hover event on the capsule item.
	 *
	 * @param {boolean} isHovering Mouse is hovering on the item
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onHover = function ( isHovering ) {
		if ( this.model.getDescription() ) {
			this.popup.toggle( isHovering );

			if ( isHovering && !this.positioned ) {
				// Recalculate position to be center of the capsule item
				this.popup.$element.css( 'margin-left', ( this.$element.width() / 2 ) );
				this.positioned = true;
			}
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
