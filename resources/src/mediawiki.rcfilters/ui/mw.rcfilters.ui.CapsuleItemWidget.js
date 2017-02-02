( function ( mw, $ ) {
	/**
	 * Extend OOUI's CapsuleItemWidget to also display a popup on hover.
	 *
	 * @class
	 * @extends OO.ui.CapsuleItemWidget
	 * @mixins OO.ui.mixin.PopupElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FilterItem} model Item model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.CapsuleItemWidget = function MwRcfiltersUiCapsuleItemWidget( model, config ) {
		var $popupContent = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget-popup' ),
			descLabelWidget = new OO.ui.LabelWidget();

		// Configuration initialization
		config = config || {};

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
				padded: true,
				align: 'center',
				$content: $popupContent
					.append( descLabelWidget.$element ),
				$floatableContainer: this.$element
			}
		}, config ) );

		// Set initial text for the popup - the description
		descLabelWidget.setLabel( this.model.getDescription() );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );

		// Initialization
		this.$overlay.append( this.popup.$element );
		this.$element
			.attr( 'aria-haspopup', 'true' )
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget' )
			.on( 'mouseover', this.onHover.bind( this, true ) )
			.on( 'mouseout', this.onHover.bind( this, false ) );
	};

	OO.inheritClass( mw.rcfilters.ui.CapsuleItemWidget, OO.ui.CapsuleItemWidget );
	OO.mixinClass( mw.rcfilters.ui.CapsuleItemWidget, OO.ui.mixin.PopupElement );

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onModelUpdate = function () {
		// Deal with active/inactive capsule filter items
		this.$element
			.toggleClass(
				'mw-rcfilters-ui-filterCapsuleMultiselectWidget-item-inactive',
				item.isIncluded() || item.isConflicted() ||
				// If the group is full coverage **and** all items are selected
				( item.getGroupModel().isFullCoverage() && item.getGroupModel().areAllSelected() )
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
}( mediaWiki, jQuery ) );
