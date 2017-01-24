( function ( mw, $ ) {
	/**
	 * Extend OOUI's MenuItemWidget used in the CapsuleMultiselectWidget
	 * to also store a description that can be used for displaying in a popup.
	 *
	 * @class
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @param {[type]} config Configuration object
	 * @cfg {string} [description] Description of the item to be displayed in the popup
	 */
	mw.rcfilters.ui.CapsuleMenuItemWidget = function MwRcfiltersUiCapsuleMenuItemWidget( config ) {
		// Configuration initialization
		config = config || {};

		// Parent constructor
		mw.rcfilters.ui.CapsuleMenuItemWidget.parent.call( this, config );

		this.description = config.description;
	};
	OO.inheritClass( mw.rcfilters.ui.CapsuleMenuItemWidget, OO.ui.MenuOptionWidget );

	/**
	 * Get the description of this item menu.
	 *
	 * @return {string} Item description
	 */
	mw.rcfilters.ui.CapsuleMenuItemWidget.prototype.getDescription = function () {
		return this.description;
	};
}( mediaWiki, jQuery ) );
