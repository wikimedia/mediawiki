/*!
 * MediaWiki Widgets - SelectWithInputWidget class.
 *
 * @copyright 2011-2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Select with input widget. Displays an OO.ui.TextInputWidget along with
	 * an OO.ui.DropdownInputWidget.
	 * TODO Explain the OTHER option
	 *
	 *     mw.loader.using( 'mediawiki.widgets.SelectWithInputWidget', function () {
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
	 * @cfg {boolean} [or=false] Config for whether the widget is dropdown AND input
	 *                           or dropdown OR input
	 */
	mw.widgets.SelectWithInputWidget = function MwWidgetsSelectWithInputWidget( config ) {
		// Config initialization
		config = $.extend( { or: false }, config );

		// Properties
		this.textinput = new OO.ui.TextInputWidget( config.textinput );
		this.dropdowninput = new OO.ui.DropdownInputWidget( config.dropdowninput );
		this.or = config.or;

		// Events
		this.dropdowninput.on( 'change', this.onChange.bind( this ) );

		// Parent constructor
		mw.widgets.SelectWithInputWidget.parent.call( this, config );

		// Initialization
		this.$element
			.addClass( 'mw-widget-selectWithInputWidget' )
			.append(
				this.dropdowninput.$element,
				this.textinput.$element
			);
		this.onChange();
	};

	/* Setup */
	OO.inheritClass( mw.widgets.SelectWithInputWidget, OO.ui.Widget );

	/* Static Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.SelectWithInputWidget.static.reusePreInfuseDOM = function ( node, config ) {
		config = mw.widgets.SelectWithInputWidget.parent.static.reusePreInfuseDOM( node, config );
		config.dropdowninput = OO.ui.DropdownInputWidget.static.reusePreInfuseDOM(
			$( node ).find( '.oo-ui-dropdownInputWidget' ),
			config.dropdowninput
		);
		config.textinput = OO.ui.TextInputWidget.static.reusePreInfuseDOM(
			$( node ).find( '.oo-ui-textInputWidget' ),
			config.textinput
		);
		return config;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.SelectWithInputWidget.static.gatherPreInfuseState = function ( node, config ) {
		var state = mw.widgets.SelectWithInputWidget.parent.static.gatherPreInfuseState( node, config );
		state.dropdowninput = OO.ui.DropdownInputWidget.static.gatherPreInfuseState(
			$( node ).find( '.oo-ui-dropdownInputWidget' ),
			config.dropdowninput
		);
		state.textinput = OO.ui.TextInputWidget.static.gatherPreInfuseState(
			$( node ).find( '.oo-ui-textInputWidget' ),
			config.textinput
		);
		return state;
	};

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.SelectWithInputWidget.prototype.restorePreInfuseState = function ( state ) {
		mw.widgets.SelectWithInputWidget.parent.prototype.restorePreInfuseState.call( this, state );
		this.dropdowninput.restorePreInfuseState( state.dropdowninput );
		this.textinput.restorePreInfuseState( state.textinput );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.SelectWithInputWidget.prototype.setDisabled = function ( disabled ) {
		var textinputIsHidden = this.or && this.dropdowninput.getValue() !== 'other';
		mw.widgets.SelectWithInputWidget.parent.prototype.setDisabled.call( this, disabled );
		this.dropdowninput.setDisabled( disabled );
		// It is impossible to submit a form with hidden fields failing validation, e.g. one that
		// is required. However, validity is not checked for disabled fields, as these are not
		// submitted with the form. So we should also disable fields when hiding them.
		this.textinput.setDisabled( textinputIsHidden || disabled );
	};

	/**
	 * Handle change events on the DropdownInput
	 *
	 * @param {string|undefined} value
	 * @private
	 */
	mw.widgets.SelectWithInputWidget.prototype.onChange = function ( value ) {
		if ( this.or ) {
			value = value || this.dropdowninput.getValue();
			this.textinput.$element.toggle( value === 'other' );
			// It is impossible to submit a form with hidden fields failing validation, e.g. one that
			// is required. However, validity is not checked for disabled fields, as these are not
			// submitted with the form. So we should also disable fields when hiding them.
			this.textinput.setDisabled( value !== 'other' || this.isDisabled() );
		}
	};

}( jQuery, mediaWiki ) );
