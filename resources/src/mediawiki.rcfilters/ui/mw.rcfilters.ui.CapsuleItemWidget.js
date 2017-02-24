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

		this.closeButton.connect( this, { click: 'onCapsuleRemovedByUser' } );

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
	 * Respond to the user removing the capsule with the close button
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onCapsuleRemovedByUser = function () {
		this.controller.updateFilter( this.model.getName(), false );
		this.controller.clearHighlightColor( this.model.getName() );
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
