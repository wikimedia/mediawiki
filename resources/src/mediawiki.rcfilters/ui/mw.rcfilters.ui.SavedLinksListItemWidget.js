( function ( mw ) {
	/**
	 * Quick links menu option widget
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.LabelElement
	 * @mixins OO.ui.mixin.IconElement
	 * @mixins OO.ui.mixin.TitledElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.SavedQueryItemModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget = function MwRcfiltersUiSavedLinksListWidget( model, config ) {
		config = config || {};

		this.model = model;

		// Parent
		mw.rcfilters.ui.SavedLinksListItemWidget.parent.call( this, $.extend( {
			data: this.model.getID()
		}, config ) );

		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, $.extend( {
			label: this.model.getLabel()
		}, config ) );
		OO.ui.mixin.IconElement.call( this, $.extend( {
			icon: ''
		}, config ) );
		OO.ui.mixin.TitledElement.call( this, $.extend( {
			title: this.model.getLabel()
		}, config ) );

		this.edit = false;
		this.$overlay = config.$overlay || this.$element;

		this.popupButton = new OO.ui.ButtonWidget( {
			classes: [ 'mw-rcfilters-ui-savedLinksListItemWidget-button' ],
			icon: 'ellipsis',
			framed: false
		} );
		this.menu = new OO.ui.FloatingMenuSelectWidget( {
			classes: [ 'mw-rcfilters-ui-savedLinksListItemWidget-menu' ],
			widget: this.popupButton,
			width: 200,
			horizontalPosition: 'end',
			$container: this.popupButton.$element,
			items: [
				new OO.ui.MenuOptionWidget( {
					data: 'edit',
					icon: 'edit',
					label: mw.msg( 'rcfilters-savedqueries-rename' )
				} ),
				new OO.ui.MenuOptionWidget( {
					data: 'delete',
					icon: 'close',
					label: mw.msg( 'rcfilters-savedqueries-remove' )
				} ),
				new OO.ui.MenuOptionWidget( {
					data: 'default',
					icon: 'pushPin',
					label: mw.msg( 'rcfilters-savedqueries-setdefault' )
				} )
			]
		} );

		this.editInput = new OO.ui.TextInputWidget( {
			classes: [ 'mw-rcfilters-ui-savedLinksListItemWidget-input' ]
		} );
		this.saveButton = new OO.ui.ButtonWidget( {
			icon: 'check',
			flags: [ 'primary', 'progressive' ]
		} );
		this.toggleEdit( false );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.popupButton.connect( this, { click: 'onPopupButtonClick' } );
		this.menu.connect( this, {
			choose: 'onMenuChoose'
		} );
		this.saveButton.connect( this, { click: 'onSaveButtonClick' } );
		this.editInput.connect( this, { enter: 'onEditInputEnter' } );
		this.editInput.$input.on( {
			blur: this.onInputBlur.bind( this ),
			keyup: this.onInputKeyup.bind( this )
		} );
		this.$element.on( { click: this.onClick.bind( this ) } );
		this.$label.on( { click: this.onClick.bind( this ) } );
		// Prevent propagation on mousedown for the save button
		// so the menu doesn't close
		this.saveButton.$element.on( { mousedown: function () { return false; } } );

		// Initialize
		this.toggleDefault( !!this.model.isDefault() );
		this.$overlay.append( this.menu.$element );
		this.$element
			.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget' )
			.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-query-' + this.model.getID() )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-content' )
									.append(
										this.$label
											.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-label' ),
										this.editInput.$element,
										this.saveButton.$element
									),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-icon' )
									.append( this.$icon ),
								this.popupButton.$element
									.addClass( 'mw-rcfilters-ui-cell' )
							)
					)
			);
	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.SavedLinksListItemWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.SavedLinksListItemWidget, OO.ui.mixin.LabelElement );
	OO.mixinClass( mw.rcfilters.ui.SavedLinksListItemWidget, OO.ui.mixin.IconElement );
	OO.mixinClass( mw.rcfilters.ui.SavedLinksListItemWidget, OO.ui.mixin.TitledElement );

	/* Events */

	/**
	 * @event delete
	 *
	 * The delete option was selected for this item
	 */

	/**
	 * @event default
	 * @param {boolean} default Item is default
	 *
	 * The 'make default' option was selected for this item
	 */

	/**
	 * @event edit
	 * @param {string} newLabel New label for the query
	 *
	 * The label has been edited
	 */

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onModelUpdate = function () {
		this.setLabel( this.model.getLabel() );
		this.toggleDefault( this.model.isDefault() );
	};

	/**
	 * Respond to click on the element or label
	 *
	 * @fires click
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onClick = function () {
		if ( !this.editing ) {
			this.emit( 'click' );
		}
	};
	/**
	 * Respond to popup button click event
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onPopupButtonClick = function () {
		this.menu.toggle();
	};

	/**
	 * Respond to menu choose event
	 *
	 * @param {OO.ui.MenuOptionWidget} item Chosen item
	 * @fires delete
	 * @fires default
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onMenuChoose = function ( item ) {
		var action = item.getData();

		if ( action === 'edit' ) {
			this.toggleEdit( true );
		} else if ( action === 'delete' ) {
			this.emit( 'delete' );
		} else if ( action === 'default' ) {
			this.emit( 'default', !this.default );
		}
		// Reset selected
		this.menu.selectItem( null );
		// Close the menu
		this.menu.toggle( false );
	};

	/**
	 * Respond to save button click
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onSaveButtonClick = function () {
		this.emit( 'edit', this.editInput.getValue() );
		this.toggleEdit( false );
	};

	/**
	 * Respond to input enter event
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onEditInputEnter = function () {
		this.emit( 'edit', this.editInput.getValue() );
		this.toggleEdit( false );
	};

	/**
	 * Respond to input keyup event, this is the way to intercept 'escape' key
	 *
	 * @param {jQuery.Event} e Event data
	 * @returns {boolean} false
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onInputKeyup = function ( e ) {
		if ( e.which === OO.ui.Keys.ESCAPE ) {
			// Return the input to the original label
			this.editInput.setValue( this.getLabel() );
			this.toggleEdit( false );
			return false;
		}
	};

	/**
	 * Respond to blur event on the input
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.onInputBlur = function () {
		this.emit( 'edit', this.editInput.getValue() );
		this.toggleEdit( false );
	};

	/**
	 * Toggle edit mode on this widget
	 *
	 * @param {boolean} isEdit Widget is in edit mode
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.toggleEdit = function ( isEdit ) {
		isEdit = isEdit === undefined ? !this.editing : isEdit;

		if ( this.editing !== isEdit ) {
			this.$element.toggleClass( 'mw-rcfilters-ui-savedLinksListItemWidget-edit', isEdit );
			this.editInput.setValue( this.getLabel() );

			this.editInput.toggle( isEdit );
			this.$label.toggleClass( 'oo-ui-element-hidden', isEdit );
			this.$icon.toggleClass( 'oo-ui-element-hidden', isEdit );
			this.popupButton.toggle( !isEdit );
			this.saveButton.toggle( isEdit );

			if ( isEdit ) {
				this.editInput.$input.focus();
			}
			this.editing = isEdit;
		}
	};

	/**
	 * Toggle default this widget
	 *
	 * @param {boolean} isDefault This item is default
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.toggleDefault = function ( isDefault ) {
		isDefault = isDefault === undefined ? !this.default : isDefault;

		if ( this.default !== isDefault ) {
			this.default = isDefault;
			this.setIcon( this.default ? 'pushPin' : '' );
			this.menu.getItemFromData( 'default' ).setLabel(
				this.default ?
					mw.msg( 'rcfilters-savedqueries-unsetdefault' ) :
					mw.msg( 'rcfilters-savedqueries-setdefault' )
			);
		}
	};

	/**
	 * Get item ID
	 *
	 * @returns {string} Query identifier
	 */
	mw.rcfilters.ui.SavedLinksListItemWidget.prototype.getID = function () {
		return this.model.getID();
	};

}( mediaWiki ) );
