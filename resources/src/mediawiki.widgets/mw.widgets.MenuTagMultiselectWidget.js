/*!
 * MediaWiki Widgets - MenuTagMultiselectWidget class.
 *
 * @copyright 2024 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {
	mw.widgets.MenuTagMultiselectWidget = function MwWidgetsMenuTagMultiselectWidget( config ) {
		const menuTagOptions = [], flatOptions = [], selected = config.selected;

		// MenuTagMultiselectWidget does not support the optgroup config like DropdownInputWidget
		for ( const optionGroup in config.options ) {
			if ( optionGroup ) {
				menuTagOptions.push( new OO.ui.MenuSectionOptionWidget( {
					label: optionGroup
				} ) );
			}
			config.options[ optionGroup ].forEach( ( option ) => {
				flatOptions.push( option );
				menuTagOptions.push( new OO.ui.MenuOptionWidget( option ) );
			} );
		}

		// Parent constructor use these config in a way that is not compatible
		config.options = [];
		config.selected = [];

		// Parent constructor
		mw.widgets.MenuTagMultiselectWidget.super.call( this, Object.assign( {}, config, {
			menu: {
				items: menuTagOptions
			}
		} ) );

		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, Object.assign( {}, config, { $pending: this.$handle } ) );

		this.setValue( selected );

		if ( 'name' in config ) {
			this.$hiddenInputs = {};
			const $container = $( '<div>' ).addClass( 'oo-ui-element-hidden' );
			flatOptions.forEach( ( option ) => {
				// Use this instead of <input type="hidden">, because hidden inputs do not have separate
				// 'value' and 'defaultChecked' properties.
				this.$hiddenInputs[ option.data ] = $( '<input>' )
					.attr( 'type', 'checkbox' )
					.attr( 'value', option.data )
					.attr( 'name', config.name + '[]' )
					.prop( 'defaultChecked', selected.includes( option.data ) )
					.appendTo( $container );
			} );
			$container.appendTo( this.$element );
		}

		this.connect( this, {
			disable: 'onDisable'
		} );
	};

	/* Setup */
	OO.inheritClass( mw.widgets.MenuTagMultiselectWidget, OO.ui.MenuTagMultiselectWidget );
	OO.mixinClass( mw.widgets.MenuTagMultiselectWidget, OO.ui.mixin.PendingElement );

	mw.widgets.MenuTagMultiselectWidget.prototype.onChangeTags = function ( items ) {
		if ( '$hiddenInputs' in this ) {
			const values = this.getValue();
			for ( const name in this.$hiddenInputs ) {
				const $input = this.$hiddenInputs[ name ];
				$input.prop( 'checked', values.includes( $input.attr( 'value' ) ) );
			}
		}

		mw.widgets.MenuTagMultiselectWidget.super.prototype.onChangeTags.call( this, items );
	};

	mw.widgets.MenuTagMultiselectWidget.prototype.onDisable = function ( disabled ) {
		if ( '$hiddenInputs' in this ) {
			for ( const name in this.$hiddenInputs ) {
				this.$hiddenInputs[ name ].prop( 'disabled', disabled );
			}
		}
	};
}() );
