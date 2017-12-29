/*!
 * MediaWiki Widgets - SizeFilterWidget class.
 *
 * @copyright 2011-2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * RadioSelectInputWidget and a TextInputWidget to set minimum or maximum byte size
	 *
	 *     mw.loader.using( 'mediawiki.widgets.SizeFilterWidget', function () {
	 *       var sf = new mw.widgets.SizeFilterWidget();
	 *       $( 'body' ).append( sf.$element );
	 *     } );
	 *
	 * @class mw.widgets.SizeFilterWidget
	 * @extends OO.ui.Widget
	 * @uses OO.ui.RadioSelectInputWidget
	 * @uses OO.ui.TextInputWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {boolean} [selectMin=true] Whether to select 'min', false would select 'max'
	 */
	mw.widgets.SizeFilterWidget = function MwWidgetsSizeFilterWidget( config ) {
		// Config initialization
		config = $.extend( { selectMin: true }, config );

		// Properties
		this.radioselect = new OO.ui.RadioSelectInputWidget( { options: [
			{ data: 'min', label: mw.msg( 'minimum-size' ) },
			{ data: 'max', label: mw.msg( 'maximum-size' ) }
		] } );
		this.textinput = new OO.ui.TextInputWidget( {
			type: 'number',
			placeholder: mw.msg( 'pagesize' )
		} );

		// Parent constructor
		mw.widgets.SizeFilterWidget.parent.call( this, config );

		// Initialization
		this.radioselect.setValue( config.selectMin ? 'min' : 'max' );
		this.$element
			.addClass( 'mw-widget-sizeFilterWidget' )
			.append(
				this.radioselect.$element,
				this.textinput.$element
			);
	};

	/* Setup */
	OO.inheritClass( mw.widgets.SizeFilterWidget, OO.ui.Widget );

	/* Static Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.SizeFilterWidget.static.reusePreInfuseDOM = function ( node, config ) {
		config = mw.widgets.SizeFilterWidget.parent.static.reusePreInfuseDOM( node, config );
		config.radioselect = OO.ui.RadioSelectInputWidget.static.reusePreInfuseDOM(
			$( node ).find( '.oo-ui-radioSelectWidget' ),
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
	mw.widgets.SizeFilterWidget.static.gatherPreInfuseState = function ( node, config ) {
		var state = mw.widgets.SizeFilterWidget.parent.static.gatherPreInfuseState( node, config );
		state.radioselect = OO.ui.RadioSelectInputWidget.static.gatherPreInfuseState(
			$( node ).find( '.oo-ui-radioSelectWidget' ),
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
	mw.widgets.SizeFilterWidget.prototype.restorePreInfuseState = function ( state ) {
		mw.widgets.SizeFilterWidget.parent.prototype.restorePreInfuseState.call( this, state );
		this.radioselect.restorePreInfuseState( state.dropdowninput );
		this.textinput.restorePreInfuseState( state.textinput );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.SizeFilterWidget.prototype.setDisabled = function ( disabled ) {
		var textinputIsHidden = this.or && this.dropdowninput.getValue() !== 'other';
		mw.widgets.SizeFilterWidget.parent.prototype.setDisabled.call( this, disabled );
		this.radioselect.setDisabled( disabled );
		// It is impossible to submit a form with hidden fields failing validation, e.g. one that
		// is required. However, validity is not checked for disabled fields, as these are not
		// submitted with the form. So we should also disable fields when hiding them.
		this.textinput.setDisabled( textinputIsHidden || disabled );
	};
}( jQuery, mediaWiki ) );
