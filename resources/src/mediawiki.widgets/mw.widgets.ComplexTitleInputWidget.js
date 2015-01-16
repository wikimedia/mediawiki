/*!
 * MediaWiki Widgets - ComplexTitleInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Creates a mw.widgets.ComplexTitleInputWidget object.
	 *
	 * This is not a complete implementation and is not intended for public usage.
	 *
	 * @class
	 * @private
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {mw.widgets.NamespaceInputWidget} namespace Widget to include
	 * @cfg {mw.widgets.TitleInputWidget} title Widget to include
	 */
	mw.widgets.ComplexTitleInputWidget = function MwWidgetsComplexTitleInputWidget( config ) {
		// Parent constructor
		mw.widgets.ComplexTitleInputWidget.parent.call( this, config );

		// Properties
		this.namespace = config.namespace;
		this.title = config.title;

		// Events
		this.namespace.namespace.connect( this, { change: 'updateTitleNamespace' } );

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
		this.title.setNamespace( Number( this.namespace.namespace.getValue() ) );
	};

}( jQuery, mediaWiki ) );
