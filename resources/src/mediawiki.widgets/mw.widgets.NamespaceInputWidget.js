/*!
 * MediaWiki Widgets - NamespaceInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Creates a mw.widgets.NamespaceInputWidget object.
	 *
	 * This is not a complete implementation and is not intended for public usage. It only exists
	 * because of HTMLForm shenanigans.
	 *
	 * @class
	 * @private
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {OO.ui.DropdownInputWidget} namespace Widget to include
	 * @cfg {OO.ui.CheckboxInputWidget|null} invert Widget to include
	 * @cfg {OO.ui.CheckboxInputWidget|null} associated Widget to include
	 * @cfg {string|null} allValue Value for "all namespaces" option, if any
	 */
	mw.widgets.NamespaceInputWidget = function MwWidgetsNamespaceInputWidget( config ) {
		// Parent constructor
		OO.ui.Widget.call( this, config );

		// Events
		config.namespace.on( 'change', function () {
			if ( config.invert ) {
				config.invert.getField().setDisabled( config.namespace.getValue() === config.allValue );
			}
			if ( config.associated ) {
				config.associated.getField().setDisabled( config.namespace.getValue() === config.allValue );
			}
		} );

		// Intialization
		this.$element
			.addClass( 'mw-widget-namespaceInputWidget' )
			.append(
				config.namespace.$element,
				config.invert ? config.invert.$element : '',
				config.associated ? config.associated.$element : ''
			);
		if ( config.invert ) {
			config.invert.getField().setDisabled( config.namespace.getValue() === config.allValue );
		}
		if ( config.associated ) {
			config.associated.getField().setDisabled( config.namespace.getValue() === config.allValue );
		}
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.NamespaceInputWidget, OO.ui.Widget );

}( jQuery, mediaWiki ) );
