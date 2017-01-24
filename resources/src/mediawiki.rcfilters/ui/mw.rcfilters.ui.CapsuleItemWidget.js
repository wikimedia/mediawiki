( function ( mw, $ ) {
	/**
	 * Extend OOUI's CapsuleItemWidget to also display a popup on hover.
	 *
	 * @class
	 * @extends OO.ui.CapsuleItemWidget
	 * @mixins OO.ui.mixin.PopupElement
	 *
	 * @constructor
	 * @param {[type]} config Configuration object
	 * @cfg {string} [description] Description of the item to be displayed in the popup
	 */
	mw.rcfilters.ui.CapsuleItemWidget = function MwRcfiltersUiCapsuleItemWidget( config ) {
		var $popupContent = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget-popup' ),
			descLabelWidget = new OO.ui.LabelWidget();

		// Configuration initialization
		config = config || {};

		// Parent constructor
		mw.rcfilters.ui.CapsuleItemWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.PopupElement.call( this, $.extend( {
			popup: {
				$content: $popupContent
					.append( descLabelWidget.$element )
			}
		}, config ) );

		this.description = config.description;
		descLabelWidget.setLabel( this.description );

		// Events
		if ( this.description ) {
			this.$element
				.on( 'hover', this.onHover.bind( this, true ), this.onHover.bind( this, false ) )
				.attr( 'aria-haspopup', 'true' )
				.append( this.popup.$element );
		}

		this.$element
			.addClass( 'mw-rcfilters-ui-capsuleItemWidget' );
	};

	OO.inheritClass( mw.rcfilters.ui.CapsuleItemWidget, OO.ui.CapsuleItemWidget );
	OO.mixinClass( mw.rcfilters.ui.CapsuleItemWidget, OO.ui.mixin.PopupElement );

	/**
	 * Respond to hover event on the capsule item.
	 *
	 * @param {boolean} isHovering Mouse is hovering on the item
	 */
	mw.rcfilters.ui.CapsuleItemWidget.prototype.onHover = function ( isHovering ) {
debugger;
		this.popup.toggle( isHovering );
	};
}( mediaWiki, jQuery ) );
