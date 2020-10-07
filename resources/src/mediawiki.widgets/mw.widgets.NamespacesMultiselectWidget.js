/*!
 * MediaWiki Widgets - NamespacesMultiselectWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * Creates an mw.widgets.NamespacesMultiselectWidget object
	 *
	 * TODO: A lot of this is duplicated in mw.widgets.UsersMultiselectWidget
	 * and mw.widgets.TitlesMultiselectWidget. These classes should be
	 * refactored.
	 *
	 * @class
	 * @extends OO.ui.MenuTagMultiselectWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.NamespacesMultiselectWidget = function MwWidgetsNamespacesMultiselectWidget( config ) {
		var i, ilen, option,
			namespaces = {},
			options = mw.widgets.NamespaceInputWidget.static.getNamespaceDropdownOptions( {} );

		for ( i = 0, ilen = options.length; i < ilen; i++ ) {
			option = options[ i ];
			namespaces[ option.data ] = option.label;
		}

		config = $.extend( true, {
			options: mw.widgets.NamespaceInputWidget.static.getNamespaceDropdownOptions( {} )
		}, config );

		// Parent constructor
		mw.widgets.NamespacesMultiselectWidget.parent.call( this, $.extend( true,
			{
				clearInputOnChoose: true,
				inputPosition: 'inline',
				allowEditTags: false,
				menu: {
					filterMode: 'substring'
				}
			},
			config,
			{
				selected: config && config.selected ? config.selected.map( function ( id ) {
					return {
						data: id,
						label: namespaces[ id ]
					};
				} ) : undefined
			}
		) );

		// Initialization
		this.$element
			.addClass( 'mw-widgets-namespacesMultiselectWidget' );

		if ( 'name' in config ) {
			// Use this instead of <input type="hidden">, because hidden inputs do not have separate
			// 'value' and 'defaultValue' properties. The script on Special:Preferences
			// (mw.special.preferences.confirmClose) checks this property to see if a field was changed.
			this.$hiddenInput = $( '<textarea>' )
				.addClass( 'oo-ui-element-hidden' )
				.attr( 'name', config.name )
				.appendTo( this.$element );
			// Update with preset values
			// Set the default value (it might be different from just being empty)
			this.$hiddenInput.prop( 'defaultValue', this.getItems().map( function ( item ) {
				return item.getData();
			} ).join( '\n' ) );
			this.on( 'change', function ( items ) {
				this.$hiddenInput.val( items.map( function ( item ) {
					return item.getData();
				} ).join( '\n' ) );
				// Trigger a 'change' event as if a user edited the text
				// (it is not triggered when changing the value from JS code).
				this.$hiddenInput.trigger( 'change' );
			}.bind( this ) );
		}
	};

	/* Setup */

	OO.inheritClass( mw.widgets.NamespacesMultiselectWidget, OO.ui.MenuTagMultiselectWidget );
	OO.mixinClass( mw.widgets.NamespacesMultiselectWidget, OO.ui.mixin.PendingElement );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.NamespacesMultiselectWidget.prototype.createMenuOptionWidget = function ( data, label, icon ) {
		return new mw.widgets.NamespacesMenuOptionWidget( {
			data: data,
			label: label || data,
			icon: icon
		} );
	};

}() );
