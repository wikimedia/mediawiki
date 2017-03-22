/*!
 * MediaWiki Widgets - SelectWithInput class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Select with input widget. Displays an OO.ui.TextInputWidget along with
	 * an OO.ui.DropdownInputWidget.
	 *
	 *     mw.loader.using( 'mediawiki.widgets.SelectWithInput', function () {
	 *       var swi = new mw.widgets.SelectWithInputWidget( {
	 *         or: true,
	 *         dropdowninput: {
	 *           options: [
	 *             { data: 'other', label: 'Other' },
	 *             { data: 'a', label: 'First' },
	 *             { data: 'b', label: 'Second' },
	 *             { data: 'c', label: 'Third' }
	 *           ]
	 *         },
	 *         textinput: {
	 *         }
	 *       } );
	 *
	 *       $( 'body' ).append( swi.$element );
	 *     } );
	 *
	 * @class mw.widgets.SelectWithInputWidget
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {Object} [dropdowninput] Config for the dropdown
	 * @cfg {Object} [textinput] Config for the text input
	 * @cfg {boolean} [or=false]
	 */
	mw.widgets.SelectWithInputWidget = function MwWidgetsSelectWithInputWidget ( config ) {
		// Config initialization
		config = $.extend( { or: false }, config );


		// Properties
		this.textinput = new OO.ui.TextInputWidget( config.textinput );
		this.dropdowninput = new OO.ui.DropdownInputWidget( config.dropdowninput );

		if ( config.or === true ) {
			this.dropdowninput.on( 'change', function ( value ) {
				if ( value === 'other' ) {
					this.textinput.$element.show();
				} else {
					this.textinput.$element.hide();
				}
			}.bind( this ) );
		};

		// Parent constructor
		mw.widgets.SelectWithInputWidget.parent.call( this, config );

		// Initialization
		this.$element
			.addClass( 'mw-widget-selectWithInputWidget' )
			.append(
				this.dropdowninput.$element,
				this.textinput.$element
			);
	};

	/* Setup */
	OO.inheritClass( mw.widgets.SelectWithInputWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.SelectWithInputWidget.prototype.setDisabled = function ( disabled ) {
		mw.widgets.SelectWithInputWidget.parent.prototype.setDisabled.call( this, disabled );
		this.textinput.setDisabled( disabled );
		this.dropdowninput.setDisabled( disabled );
	};

}( jQuery, mediaWiki ) );
