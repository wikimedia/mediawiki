/*!
 * MediaWiki Widgets - UserListInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Namespace input widget. Displays a dropdown box with the choice of available namespaces, plus
	 * two checkboxes to include associated namespace or to invert selection.
	 *
	 * @class
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * //FIXME: Documentation
	 */
	mw.widgets.UserListInputWidget = function MwWidgetsComplexNamespaceInputWidget( config ) {
		// Configuration initialization
		config = $.extend(
			{
				// Config options for nested widgets
				'userinput': {},
				'userinputLabel': {},
				'groupinput': {},
				'groupinputLabel': {},
				'editsonlyCheck': {},
				'editsonlyCheckLabel': {},
				'creationsortCheck': {},
				'creationsortCheckLabel': {},
				'descsortCheck': {},
				'descsortCheckLabel': {},
			},
			config
		);

		// Parent constructor
		mw.widgets.UserListInputWidget.parent.call( this, config );

		// Properties
		this.config = config;

		userinputWidgetItems = [];

		this.userinput = new mw.widgets.UserInputWidget( config.userinput );
		this.userinputLabel = new OO.ui.FieldLayout(
			this.userinput,
			config.userinputLabel
		);
		userinputWidgetItems.push( this.userinputLabel );

		// add a group dropdown field, if needed
		if ( config.groupinput !== null ) {
			this.groupinput = new OO.ui.DropdownInputWidget( config.groupinput );

			this.groupinputLabel = new OO.ui.FieldLayout(
				this.groupinput,
				config.groupinputLabel
			);
			userinputWidgetItems.push( this.groupinputLabel );
		}

		// $checkboxItems hold all checkbox items that should be added under the input fields
		checkboxItems = [];
		if ( config.editsonlyCheck !== null ) {
			this.editsonlyCheck = new OO.ui.CheckboxInputWidget( $.extend(
				config.editsonlyCheck,
				{ 'value': 1 }
			) );

			this.editsonlyCheckLabel = new OO.ui.FieldLayout(
				this.editsonlyCheck,
				$.extend(
					{ 'align': 'inline' },
					config.editsonlyCheckLabel
				)
			);
			checkboxItems.push( this.editsonlyCheckLabel );
		}
		if ( config.creationsortCheck !== null ) {
			this.creationsortCheck = new OO.ui.CheckboxInputWidget( $.extend(
				config.creationsortCheck,
				{ 'value': 1 }
			) );

			this.creationsortCheckLabel = new OO.ui.FieldLayout(
				this.creationsortCheck,
				$.extend(
					{ 'align': 'inline' },
					config.creationsortCheckLabel
				)
			);
			checkboxItems.push( this.creationsortCheckLabel );
		}
		if ( config.descsortCheck !== null ) {
			this.descsortCheck = new OO.ui.CheckboxInputWidget($.extend(
				config.descsortCheck,
				{ 'value': 1 }
			) );

			this.creationsortCheckLabel = new OO.ui.FieldLayout(
				this.descsortCheck,
				$.extend(
					{ 'align': 'inline' },
					config.descsortCheckLabel
				)
			);
			checkboxItems.push( this.creationsortCheckLabel );
		}

		if ( checkboxItems ) {
			// wrap the elements in checkboxItems into it's own horizontal layout
			this.sortWidget = new OO.ui.HorizontalLayout( {
				'items': checkboxItems
			} );
		}

		if ( userinputWidgetItems ) {
			this.userinputWidget = new OO.ui.HorizontalLayout( {
				'items': userinputWidgetItems,
			} );
		}

		// add the content's to the widget
		this.$element.
			append(
				this.userinputWidget ? this.userinputWidget.$element : '',
				this.userinputWidget ? this.userinputWidget.$element : '',
				this.sortWidget ? this.sortWidget.$element : ''
			);
	};

	/* Setup */

	OO.inheritClass( mw.widgets.UserListInputWidget, OO.ui.Widget );

}( jQuery, mediaWiki ) );
