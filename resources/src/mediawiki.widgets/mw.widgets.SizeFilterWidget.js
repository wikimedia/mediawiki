/*!
 * MediaWiki Widgets - SizeFilterWidget class.
 *
 * @copyright 2011-2018 MediaWiki Widgets Team and others; see AUTHORS.txt
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
   * @cfg {Object} [radioselectinput] Config for the radio select input
	 * @cfg {Object} [textinput] Config for the text input
	 * @cfg {boolean} [selectMin=true] Whether to select 'min', false would select 'max'
	 */
	mw.widgets.SizeFilterWidget = function MwWidgetsSizeFilterWidget( config ) {
		// Config initialization
		config = $.extend( { selectMin: true }, config );
		config.textinput = $.extend( {
			type: 'number'
		}, config.textinput );
		config.radioselectinput = $.extend( {
			options: [
				{ data: 'min', label: mw.msg( 'minimum-size' ) },
				{ data: 'max', label: mw.msg( 'maximum-size' ) }
			]
		}, config.radioselectinput );

		// Properties
		this.radioselectinput = new OO.ui.RadioSelectInputWidget( config.radioselectinput );
		this.textinput = new OO.ui.TextInputWidget( config.textinput );
		this.label = new OO.ui.LabelWidget( { label: mw.msg( 'pagesize' ) } );

		// Parent constructor
		mw.widgets.SizeFilterWidget.parent.call( this, config );

		// Initialization
		this.radioselectinput.setValue( config.selectMin ? 'min' : 'max' );
		this.$element
			.addClass( 'mw-widget-sizeFilterWidget' )
			.append(
				this.radioselectinput.$element,
				this.textinput.$element,
				this.label.$element
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
		config.radioselectinput = OO.ui.RadioSelectInputWidget.static.reusePreInfuseDOM(
			$( node ).find( '.oo-ui-radioSelectInputWidget' ),
			config.radioselectinput
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
		state.radioselectinput = OO.ui.RadioSelectInputWidget.static.gatherPreInfuseState(
			$( node ).find( '.oo-ui-radioSelectInputWidget' ),
			config.radioselectinput
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
		this.radioselectinput.restorePreInfuseState( state.radioselectinput );
		this.textinput.restorePreInfuseState( state.textinput );
	};

}( jQuery, mediaWiki ) );
