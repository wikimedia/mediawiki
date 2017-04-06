( function ( mw ) {
	/**
	 * Widget to display and manipulate saved queries
	 *
	 * @extends OO.ui.ButtonOptionWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration object
	 * @cfg {boolean} [editing] Widget is in edit mode
	 */
	mw.rcfilters.ui.QueryButtonWidget = function MwRcfiltersUiQueryButtonWidget( label, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.QueryButtonWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
			label: label,
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-queryButtonWidget-label' ),
		} ) );
		OO.ui.mixin.TitledElement.call( this, $.extend( { title: label }, config ) );
		OO.ui.mixin.IconElement.call( this, $.extend( {
			icon: 'bookmark'
		}, config ) );

		this.$overlay = config.$overlay || this.$element;

		this.editInput = new OO.ui.TextInputWidget( {
			classes: [ 'mw-rcfilters-ui-queryButtonWidget-input' ]
		} );
		this.saveButton = new OO.ui.ButtonWidget( {
			icon: 'check',
			flags: [ 'primary', 'progressive' ]
		} );

		this.editMenuWidget = new OO.ui.SelectWidget( {
			items: [
				new OO.ui.DecoratedOptionWidget( {
					data: 'edit',
					icon: 'edit',
					label: mw.msg( 'rcfilters-savedqueries-rename' )
				} ),
				new OO.ui.DecoratedOptionWidget( {
					data: 'remove',
					icon: 'close',
					label: mw.msg( 'rcfilters-savedqueries-remove' )
				} ),
				new OO.ui.DecoratedOptionWidget( {
					data: 'default',
					icon: 'heart',
					label: mw.msg( 'rcfilters-savedqueries-setdefault' )
				} ),
			]
		} );
		this.popupButton = new OO.ui.PopupButtonWidget( {
			framed: false,
			icon: 'ellipsis',
			$overlay: this.$overlay,
			popup: {
				width: 200,
				$content: $( '<div>' )
					.addClass( 'mw-rcfilters-ui-queryButtonWidget-popup' )
					.append( this.editMenuWidget.$element ),
				align: 'before'
			},
			classes: [ 'mw-rcfilters-ui-queryButtonWidget-button' ]
		} );

		this.toggleEdit( !!config.editing );

		// Events
		this.$label.on( { click: this.emit.bind( this, 'click' ) } );
		this.editMenuWidget.connect( this, { choose: 'onEditMenuChoose' } );
		this.saveButton.connect( this, { click: 'onSaveButtonClick' } );

		this.editInput.$input.on( {
			blur: this.onInputBlur.bind( this ),
			keypress: this.onInputKeypress.bind( this ),
			keyup: this.onInputKeyup.bind( this )
		} );

		this.$element
			.append(
				this.$icon
					.addClass( 'mw-rcfilters-ui-queryButtonWidget-icon' ),
				this.$label,
				this.editInput.$element,
				this.saveButton.$element,
				this.popupButton.$element
			)
			.addClass( 'mw-rcfilters-ui-queryButtonWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.QueryButtonWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.QueryButtonWidget, OO.ui.mixin.LabelElement );
	OO.mixinClass( mw.rcfilters.ui.QueryButtonWidget, OO.ui.mixin.TitledElement );
	OO.mixinClass( mw.rcfilters.ui.QueryButtonWidget, OO.ui.mixin.IconElement );

	/* Methods */

	mw.rcfilters.ui.QueryButtonWidget.prototype.onEditMenuChoose = function ( buttonOptionWidget ) {
		if ( buttonOptionWidget.getData() === 'edit' ) {
			this.toggleEdit( true );
		} else if ( buttonOptionWidget.getData() === 'remove' ) {
			this.emit( 'remove' );
		} else if ( buttonOptionWidget.getData() === 'default' ) {
			this.emit( 'default', !this.default );
		}
		this.popupButton.popup.toggle( false );
	};

	/**
	 * Respond to save button click
	 */
	mw.rcfilters.ui.QueryButtonWidget.prototype.onSaveButtonClick = function () {
		this.emit( 'edit', this.editInput.getValue() );
		this.toggleEdit( false );
	};

	/**
	 * Respond to input keypress event
	 *
	 * @param {jQuery.Event} e Event data
	 */
	mw.rcfilters.ui.QueryButtonWidget.prototype.onInputKeypress = function ( e ) {
		if ( e.which === OO.ui.Keys.ENTER ) {
			this.emit( 'edit', this.editInput.getValue() );
			this.toggleEdit( false );
		}
	};

	/**
	 * Respond to input keyup event, this is the way to intercept 'escape' key
	 * @param {jQuery.Event} e Event data
	 */
	mw.rcfilters.ui.QueryButtonWidget.prototype.onInputKeyup = function ( e ) {
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
	mw.rcfilters.ui.QueryButtonWidget.prototype.onInputBlur = function () {
		this.emit( 'edit', this.editInput.getValue() );
		this.toggleEdit( false );
	};

	/**
	 * Set the value of the label
	 *
	 * @param {string} label Button label
	 */
	mw.rcfilters.ui.QueryButtonWidget.prototype.changeLabel = function ( label ) {
		this.setLabel( label );
		this.setTitle( label );
	};

	/**
	 * Toggle edit mode on this widget
	 *
	 * @param {boolean} isEdit Widget is in edit mode
	 */
	mw.rcfilters.ui.QueryButtonWidget.prototype.toggleEdit = function ( isEdit ) {
		isEdit = isEdit === undefined ? !this.editing : isEdit;

		if ( this.editing !== isEdit ) {
			this.$element.toggleClass( 'mw-rcfilters-ui-queryButtonWidget-edit', isEdit );
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

	mw.rcfilters.ui.QueryButtonWidget.prototype.toggleDefault = function ( isDefault ) {
		var menuItem;

		isDefault = isDefault === undefined ? !this.default : isDefault;

		if ( this.default !== isDefault ) {
			this.$element
				.toggleClass( 'mw-rcfilters-ui-queryButtonWidget-default', isDefault );
			this.setIcon( isDefault ? 'heart' : 'bookmark' );

			menuItem = this.editMenuWidget.getItemFromData( 'default' );
			menuItem.setLabel(
				isDefault ?
				// Unset default
				mw.msg( 'rcfilters-savedqueries-unsetdefault' ) :
				// Set default
				mw.msg( 'rcfilters-savedqueries-setdefault' )
			);

			this.default = isDefault;
		}
	};
}( mediaWiki ) );
