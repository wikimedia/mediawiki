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

	/* Methods */

	/**
	 * Update the namespace to use for search suggestions of the title when the value of namespace
	 * dropdown changes.
	 */
	mw.widgets.ComplexTitleInputWidget.prototype.updateTitleNamespace = function () {
		this.title.setNamespace( Number( this.namespace.getValue() ) );
	};

}( jQuery, mediaWiki ) );
