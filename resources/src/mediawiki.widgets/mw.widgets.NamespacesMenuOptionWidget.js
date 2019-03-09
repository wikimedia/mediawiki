/*!
 * MediaWiki Widgets - NamespacesMenuOptionWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * Creates an mw.widgets.NamespacesMenuOptionWidget object
	 *
	 * This MenuOptionWidget widget returns an item match text that includes
	 * both the label and the data, so the menu can filter on either.
	 *
	 * @class
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.NamespacesMenuOptionWidget = function MwWidgetsNamespacesMenuOptionWidget( config ) {
		// Parent
		mw.widgets.NamespacesMenuOptionWidget.parent.call( this, config );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.NamespacesMenuOptionWidget, OO.ui.MenuOptionWidget );

	/**
	 * @inheritdoc
	 */
	mw.widgets.NamespacesMenuOptionWidget.prototype.getMatchText = function () {
		return this.getData() + ' ' + this.getLabel();
	};

}() );
