( function () {
	/**
	 * Extend OOUI's TagItemWidget to also display a popup on hover.
	 *
	 * @class
	 * @extends OO.ui.TagItemWidget
	 * @mixins OO.ui.mixin.PopupElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel
	 * @param {mw.rcfilters.dm.FilterItem} invertModel
	 * @param {mw.rcfilters.dm.FilterItem} itemModel Item model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.TagItemWidget = function MwRcfiltersUiTagItemWidget(
		controller, filtersViewModel, invertModel, itemModel, config
	) {
		// Configuration initialization
		config = config || {};

		this.controller = controller;
		this.invertModel = invertModel;
		this.filtersViewModel = filtersViewModel;
		this.itemModel = itemModel;
		this.selected = false;

		mw.rcfilters.ui.TagItemWidget.parent.call( this, $.extend( {
			data: this.itemModel.getName()
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
					.addClass( 'mw-rcfilters-ui-tagItemWidget-popup-content' )
					.append( this.popupLabel.$element ),
				$floatableContainer: this.$element,
				classes: [ 'mw-rcfilters-ui-tagItemWidget-popup' ]
			}
		}, config ) );

		this.popupTimeoutShow = null;
		this.popupTimeoutHide = null;

		this.$highlight = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-tagItemWidget-highlight' );

		// Add title attribute with the item label to 'x' button
		this.closeButton.setTitle( mw.msg( 'rcfilters-tag-remove', this.itemModel.getLabel() ) );

		// Events
		this.filtersViewModel.connect( this, { highlightChange: 'updateUiBasedOnState' } );
		this.invertModel.connect( this, { update: 'updateUiBasedOnState' } );
		this.itemModel.connect( this, { update: 'updateUiBasedOnState' } );

		// Initialization
		this.$overlay.append( this.popup.$element );
		this.$element
			.addClass( 'mw-rcfilters-ui-tagItemWidget' )
			.prepend( this.$highlight )
			.attr( 'aria-haspopup', 'true' )
			.on( 'mouseenter', this.onMouseEnter.bind( this ) )
			.on( 'mouseleave', this.onMouseLeave.bind( this ) );

		this.updateUiBasedOnState();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.TagItemWidget, OO.ui.TagItemWidget );
	OO.mixinClass( mw.rcfilters.ui.TagItemWidget, OO.ui.mixin.PopupElement );

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.updateUiBasedOnState = function () {
		// Update label if needed
		var labelMsg = this.itemModel.getLabelMessageKey( this.invertModel.isSelected() );
		if ( labelMsg ) {
			this.setLabel( $( '<div>' ).append(
				$( '<bdi>' ).html(
					mw.message( labelMsg, mw.html.escape( this.itemModel.getLabel() ) ).parse()
				)
			).contents() );
		} else {
			this.setLabel(
				$( '<bdi>' ).append(
					this.itemModel.getLabel()
				)
			);
		}

		this.setCurrentMuteState();
		this.setHighlightColor();
	};

	/**
	 * Set the current highlight color for this item
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.setHighlightColor = function () {
		var selectedColor = this.filtersViewModel.isHighlightEnabled() && this.itemModel.isHighlighted ?
			this.itemModel.getHighlightColor() :
			null;

		this.$highlight
			.attr( 'data-color', selectedColor )
			.toggleClass(
				'mw-rcfilters-ui-tagItemWidget-highlight-highlighted',
				!!selectedColor
			);
	};

	/**
	 * Set the current mute state for this item
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.setCurrentMuteState = function () {};

	/**
	 * Respond to mouse enter event
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.onMouseEnter = function () {
		var labelText = this.itemModel.getStateMessage();

		if ( labelText ) {
			this.popupLabel.setLabel( labelText );

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
	mw.rcfilters.ui.TagItemWidget.prototype.onMouseLeave = function () {
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
	mw.rcfilters.ui.TagItemWidget.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected !== undefined ? isSelected : !this.selected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;

			this.$element.toggleClass( 'mw-rcfilters-ui-tagItemWidget-selected', this.selected );
		}
	};

	/**
	 * Get the selected state of this widget
	 *
	 * @return {boolean} Tag is selected
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.isSelected = function () {
		return this.selected;
	};

	/**
	 * Get item name
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.getName = function () {
		return this.itemModel.getName();
	};

	/**
	 * Get item model
	 *
	 * @return {string} Filter model
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.getModel = function () {
		return this.itemModel;
	};

	/**
	 * Get item view
	 *
	 * @return {string} Filter view
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.getView = function () {
		return this.itemModel.getGroupModel().getView();
	};

	/**
	 * Remove and destroy external elements of this widget
	 */
	mw.rcfilters.ui.TagItemWidget.prototype.destroy = function () {
		// Destroy the popup
		this.popup.$element.detach();

		// Disconnect events
		this.itemModel.disconnect( this );
		this.closeButton.disconnect( this );
	};
}() );
