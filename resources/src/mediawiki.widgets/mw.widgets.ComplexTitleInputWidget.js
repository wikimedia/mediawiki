/*!
 * MediaWiki Widgets - ComplexTitleInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Like TitleInputWidget, but the namespace has to be input through a separate dropdown field.
	 *
	 * @class
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {Object} namespace Configuration for the NamespaceInputWidget dropdown with list of
	 *     namespaces
	 * @cfg {Object} title Configuration for the TitleInputWidget text field
	 */
	mw.widgets.ComplexTitleInputWidget = function MwWidgetsComplexTitleInputWidget( config ) {
		// Parent constructor
		mw.widgets.ComplexTitleInputWidget.parent.call( this, config );

		// Properties
		this.namespace = new mw.widgets.NamespaceInputWidget( config.namespace );
		this.title = new mw.widgets.TitleInputWidget( $.extend(
			{},
			config.title,
			{
				relative: true,
				namespace: config.namespace.value || null
			}
		) );

		// Events
		this.namespace.connect( this, { change: 'updateTitleNamespace' } );

		// Initialization
		this.$element
			.addClass( 'mw-widget-complexTitleInputWidget' )
			.append(
				this.namespace.$element,
				this.title.$element
			);
		this.updateTitleNamespace();
	};

	/* Setup */

	OO.inheritClass( mw.widgets.ComplexTitleInputWidget, OO.ui.Widget );

	/* Static Methods */
	/*jshint -W024*/

	/**
	 * @inheritdoc
	 */
	mw.widgets.ComplexTitleInputWidget.static.reusePreInfuseDOM = function ( node, config ) {
		config = mw.widgets.ComplexTitleInputWidget.parent.static.reusePreInfuseDOM( node, config );
		config.namespace = mw.widgets.NamespaceInputWidget.static.reusePreInfuseDOM(
			$( node ).find( '.mw-widget-namespaceInputWidget' ),
			config.namespace
		);
		config.title = mw.widgets.TitleInputWidget.static.reusePreInfuseDOM(
			$( node ).find( '.mw-widget-titleInputWidget' ),
			config.title
		);
		return config;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.ComplexTitleInputWidget.static.gatherPreInfuseState = function ( node, config ) {
		var state = mw.widgets.ComplexTitleInputWidget.parent.static.gatherPreInfuseState( node, config );
		state.namespace = mw.widgets.NamespaceInputWidget.static.gatherPreInfuseState(
			$( node ).find( '.mw-widget-namespaceInputWidget' ),
			config.namespace
		);
		state.title = mw.widgets.TitleInputWidget.static.gatherPreInfuseState(
			$( node ).find( '.mw-widget-titleInputWidget' ),
			config.title
		);
		return state;
	};

	/*jshint +W024*/

	/* Methods */

	/**
	 * Update the namespace to use for search suggestions of the title when the value of namespace
	 * dropdown changes.
	 */
	mw.widgets.ComplexTitleInputWidget.prototype.updateTitleNamespace = function () {
		this.title.setNamespace( Number( this.namespace.getValue() ) );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.ComplexTitleInputWidget.prototype.restorePreInfuseState = function ( state ) {
		mw.widgets.ComplexTitleInputWidget.parent.prototype.restorePreInfuseState.call( this, state );
		this.namespace.restorePreInfuseState( state.namespace );
		this.title.restorePreInfuseState( state.title );
	};

}( jQuery, mediaWiki ) );
