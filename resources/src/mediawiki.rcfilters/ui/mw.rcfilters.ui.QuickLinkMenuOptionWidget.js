( function ( mw ) {
	/**
	 * Quick links menu option widget
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.LabelElement
	 * @mixins OO.ui.mixin.IconElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.SavedQueryItemModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.QuickLinkMenuOptionWidget = function MwRcfiltersUiQuickLinksWidget( model, config ) {
		config = config || {};

		this.model = model;

		// Parent
		mw.rcfilters.ui.QuickLinkMenuOptionWidget.parent.call( this, $.extend( {
			data: this.model.getID()
		}, config ) );

		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, $.extend( {
			label: this.model.getLabel()
		}, config ) );
		OO.ui.mixin.IconElement.call( this, $.extend( {
			icon: 'bookmark'
		}, config ) );

		this.edit = false;
		this.$overlay = config.$overlay || this.$element;

		this.popupButton = new OO.ui.ButtonWidget( {
			classes: [ 'mw-rcfilters-ui-quickLinkMenuOptionWidget-button' ],
			icon: 'ellipsis',
			framed: false
		} );
		this.menu = new OO.ui.FloatingMenuSelectWidget( {
			classes: [ 'mw-rcfilters-ui-quickLinkMenuOptionWidget-menu' ],
			widget: this.popupButton,
			width: 200,
			horizontalPosition: 'end',
			$container: this.popupButton.$element,
			$autoCloseIgnore: this.$overlay,
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
					icon: 'heart',
					label: mw.msg( 'rcfilters-savedqueries-setdefault' )
				} ),
			]
		} );

		this.editInput = new OO.ui.TextInputWidget( {
			classes: [ 'mw-rcfilters-ui-quickLinkMenuOptionWidget-input' ]
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
		this.editInput.$input.on( {
			blur: this.onInputBlur.bind( this ),
			keypress: this.onInputKeypress.bind( this ),
			keyup: this.onInputKeyup.bind( this )
		} );
		this.$element.on( { click: this.emit.bind( this, 'click' ) } );
		this.$label.on( { click: this.emit.bind( this, 'click' ) } );

		// Initialize
		this.toggleDefault( !!this.model.isDefault() );
		this.$overlay.append( this.menu.$element );
		this.$element
			.addClass( 'mw-rcfilters-ui-quickLinkMenuOptionWidget' )
			.addClass( 'mw-rcfilters-ui-quickLinkMenuOptionWidget-query-' + this.model.getID() )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-quickLinkMenuOptionWidget-icon' )
									.append( this.$icon ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-quickLinkMenuOptionWidget-content' )
									.append(
										this.$label
											.addClass( 'mw-rcfilters-ui-quickLinkMenuOptionWidget-label' ),
										this.editInput.$element,
										this.saveButton.$element
									),
								this.popupButton.$element
									.addClass( 'mw-rcfilters-ui-cell' )
							)
					)
			);
	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.QuickLinkMenuOptionWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.QuickLinkMenuOptionWidget, OO.ui.mixin.LabelElement );
	OO.mixinClass( mw.rcfilters.ui.QuickLinkMenuOptionWidget, OO.ui.mixin.IconElement );

	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.onModelUpdate = function () {
		this.setLabel( this.model.getLabel() );
		this.toggleDefault( this.model.isDefault() );
	};

	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.onPopupButtonClick = function () {
		this.menu.toggle();
	};

	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.onMenuChoose = function ( item ) {
		var action = item.getData();

		if ( action === 'edit' ) {
			this.toggleEdit( true );
		} else if ( action === 'delete' ) {
			this.emit( 'delete' );
		} else if ( action === 'default' ) {
			this.emit( 'default', !this.default );
		}
		this.menu.toggle( false );
	};
	/**
	 * Respond to save button click
	 */
	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.onSaveButtonClick = function () {
		this.emit( 'edit', this.editInput.getValue() );
		this.toggleEdit( false );
	};

	/**
	 * Respond to input keypress event
	 *
	 * @param {jQuery.Event} e Event data
	 */
	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.onInputKeypress = function ( e ) {
		if ( e.which === OO.ui.Keys.ENTER ) {
			this.emit( 'edit', this.editInput.getValue() );
			this.toggleEdit( false );
		}
	};

	/**
	 * Respond to input keyup event, this is the way to intercept 'escape' key
	 * @param {jQuery.Event} e Event data
	 */
	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.onInputKeyup = function ( e ) {
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
	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.onInputBlur = function () {
		this.emit( 'edit', this.editInput.getValue() );
		this.toggleEdit( false );
	};

	/**
	 * Toggle edit mode on this widget
	 *
	 * @param {boolean} isEdit Widget is in edit mode
	 */
	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.toggleEdit = function ( isEdit ) {
		isEdit = isEdit === undefined ? !this.editing : isEdit;

		if ( this.editing !== isEdit ) {
			this.$element.toggleClass( 'mw-rcfilters-ui-quickLinkMenuOptionWidget-edit', isEdit );
			this.editInput.setValue( this.getLabel() );

			this.editInput.toggle( isEdit );
			this.$label.toggleClass( 'oo-ui-element-hidden', isEdit );
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
	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.toggleDefault = function ( isDefault ) {
		isDefault = isDefault === undefined ? !this.default : isDefault;


		if ( this.default !== isDefault ) {
			this.default = isDefault;
			this.setIcon( this.default ? 'heart' : 'bookmark' );
		}
	};

	mw.rcfilters.ui.QuickLinkMenuOptionWidget.prototype.getID = function () {
		return this.model.getID();
	};

}( mediaWiki ) );
