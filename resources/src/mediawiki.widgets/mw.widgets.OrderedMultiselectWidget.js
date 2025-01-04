/*!
 * MediaWiki Widgets - OrderedMultiselectWidget class.
 *
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {
	mw.widgets.OrderedMultiselectWidget = function MwWidgetsOrderedMultiselectWidget( config ) {
		const menuTagOptions = [], selected = config.selected;

		for ( const optionGroup in config.options ) {
			if ( optionGroup ) {
				menuTagOptions.push( new OO.ui.MenuSectionOptionWidget( {
					label: optionGroup
				} ) );
			}
			config.options[ optionGroup ].forEach( ( option ) => {
				menuTagOptions.push( new OO.ui.MenuOptionWidget( option ) );
			} );
		}

		// Parent constructor use these config in a way that is not compatible
		config.options = [];
		config.selected = [];

		// Parent constructor
		mw.widgets.OrderedMultiselectWidget.super.call( this, Object.assign( {}, config, {
			menu: {
				items: menuTagOptions
			}
		} ) );

		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, Object.assign( {}, config, { $pending: this.$handle } ) );

		this.setValue( selected );

		if ( 'name' in config ) {
			this.$hiddenInput = $( '<textarea>' )
				.addClass( 'oo-ui-element-hidden' )
				.attr( 'name', config.name )
				.appendTo( this.$element );
			// Update with preset values
			this.onChangeTags();
			// Set the default value (it might be different from just being empty)
			this.$hiddenInput.prop( 'defaultValue', this.getValue().join( '\n' ) );
		}

		this.connect( this, {
			disable: 'onDisable'
		} );
	};

	/* Setup */
	OO.inheritClass( mw.widgets.OrderedMultiselectWidget, OO.ui.MenuTagMultiselectWidget );
	OO.mixinClass( mw.widgets.OrderedMultiselectWidget, OO.ui.mixin.PendingElement );

	mw.widgets.OrderedMultiselectWidget.prototype.onChangeTags = function ( items ) {
		if ( '$hiddenInput' in this ) {
			this.$hiddenInput.val( this.getValue().join( '\n' ) );
			// Trigger a 'change' event as if a user edited the text
			// (it is not triggered when changing the value from JS code).
			this.$hiddenInput.trigger( 'change' );
		}

		mw.widgets.OrderedMultiselectWidget.super.prototype.onChangeTags.call( this, items );
	};

	mw.widgets.OrderedMultiselectWidget.prototype.onDisable = function ( disabled ) {
		if ( '$hiddenInput' in this ) {
			this.$hiddenInput.prop( 'disabled', disabled );
		}
	};
}() );
