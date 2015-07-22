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
		mw.widgets.NamespaceInputWidget.parent.call( this, config );

		// Properties
		this.namespace = config.namespace;
		this.invert = config.invert;
		this.associated = config.associated;
		this.allValue = config.allValue;

		// Events
		this.namespace.connect( this, { change: 'updateCheckboxesState' } );

		// Initialization
		this.$element
			.addClass( 'mw-widget-namespaceInputWidget' )
			.append(
				this.namespace.$element,
				this.invert ? this.invert.$element : '',
				this.associated ? this.associated.$element : ''
			);
		this.updateCheckboxesState();
	};

	/* Setup */

	OO.inheritClass( mw.widgets.NamespaceInputWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Update the disabled state of checkboxes when the value of namespace dropdown changes.
	 */
	mw.widgets.NamespaceInputWidget.prototype.updateCheckboxesState = function () {
		if ( this.invert ) {
			this.invert.getField().setDisabled( this.namespace.getValue() === this.allValue );
		}
		if ( this.associated ) {
			this.associated.getField().setDisabled( this.namespace.getValue() === this.allValue );
		}
	};

}( jQuery, mediaWiki ) );
