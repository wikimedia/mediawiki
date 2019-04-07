var GroupWidget = require( './GroupWidget.js' ),
	SavedLinksListItemWidget = require( './SavedLinksListItemWidget.js' ),
	SavedLinksListWidget;

/**
 * Quick links widget
 *
 * @class mw.rcfilters.ui.SavedLinksListWidget
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {mw.rcfilters.Controller} controller Controller
 * @param {mw.rcfilters.dm.SavedQueriesModel} model View model
 * @param {Object} [config] Configuration object
 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
 */
SavedLinksListWidget = function MwRcfiltersUiSavedLinksListWidget( controller, model, config ) {
	var $labelNoEntries = $( '<div>' )
		.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-savedLinksListWidget-placeholder-title' )
				.text( mw.msg( 'rcfilters-quickfilters-placeholder-title' ) ),
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-savedLinksListWidget-placeholder-description' )
				.text( mw.msg( 'rcfilters-quickfilters-placeholder-description' ) )
		);

	config = config || {};

	// Parent
	SavedLinksListWidget.parent.call( this, config );

	this.controller = controller;
	this.model = model;
	this.$overlay = config.$overlay || this.$element;

	this.placeholderItem = new OO.ui.DecoratedOptionWidget( {
		classes: [ 'mw-rcfilters-ui-savedLinksListWidget-placeholder' ],
		label: $labelNoEntries,
		icon: 'bookmark'
	} );

	this.menu = new GroupWidget( {
		events: {
			click: 'menuItemClick',
			delete: 'menuItemDelete',
			default: 'menuItemDefault',
			edit: 'menuItemEdit'
		},
		classes: [ 'mw-rcfilters-ui-savedLinksListWidget-menu' ],
		items: [ this.placeholderItem ]
	} );
	this.button = new OO.ui.PopupButtonWidget( {
		classes: [ 'mw-rcfilters-ui-savedLinksListWidget-button' ],
		label: mw.msg( 'rcfilters-quickfilters' ),
		icon: 'bookmark',
		indicator: 'down',
		$overlay: this.$overlay,
		popup: {
			width: 300,
			anchor: false,
			align: 'backwards',
			$autoCloseIgnore: this.$overlay,
			$content: this.menu.$element
		}
	} );

	// Events
	this.model.connect( this, {
		add: 'onModelAddItem',
		remove: 'onModelRemoveItem'
	} );
	this.menu.connect( this, {
		menuItemClick: 'onMenuItemClick',
		menuItemDelete: 'onMenuItemRemove',
		menuItemDefault: 'onMenuItemDefault',
		menuItemEdit: 'onMenuItemEdit'
	} );

	this.placeholderItem.toggle( this.model.isEmpty() );
	// Initialize
	this.$element
		.addClass( 'mw-rcfilters-ui-savedLinksListWidget' )
		.append( this.button.$element );
};

/* Initialization */
OO.inheritClass( SavedLinksListWidget, OO.ui.Widget );

/* Methods */

/**
 * Respond to menu item click event
 *
 * @param {mw.rcfilters.ui.SavedLinksListItemWidget} item Menu item
 */
SavedLinksListWidget.prototype.onMenuItemClick = function ( item ) {
	this.controller.applySavedQuery( item.getID() );
	this.button.popup.toggle( false );
};

/**
 * Respond to menu item remove event
 *
 * @param {mw.rcfilters.ui.SavedLinksListItemWidget} item Menu item
 */
SavedLinksListWidget.prototype.onMenuItemRemove = function ( item ) {
	this.controller.removeSavedQuery( item.getID() );
};

/**
 * Respond to menu item default event
 *
 * @param {mw.rcfilters.ui.SavedLinksListItemWidget} item Menu item
 * @param {boolean} isDefault Item is default
 */
SavedLinksListWidget.prototype.onMenuItemDefault = function ( item, isDefault ) {
	this.controller.setDefaultSavedQuery( isDefault ? item.getID() : null );
};

/**
 * Respond to menu item edit event
 *
 * @param {mw.rcfilters.ui.SavedLinksListItemWidget} item Menu item
 * @param {string} newLabel New label
 */
SavedLinksListWidget.prototype.onMenuItemEdit = function ( item, newLabel ) {
	this.controller.renameSavedQuery( item.getID(), newLabel );
};

/**
 * Respond to menu add item event
 *
 * @param {mw.rcfilters.ui.SavedLinksListItemWidget} item Menu item
 */
SavedLinksListWidget.prototype.onModelAddItem = function ( item ) {
	if ( this.menu.findItemFromData( item.getID() ) ) {
		return;
	}

	this.menu.addItems( [
		new SavedLinksListItemWidget( item, { $overlay: this.$overlay } )
	] );
	this.placeholderItem.toggle( this.model.isEmpty() );
};

/**
 * Respond to menu remove item event
 *
 * @param {mw.rcfilters.ui.SavedLinksListItemWidget} item Menu item
 */
SavedLinksListWidget.prototype.onModelRemoveItem = function ( item ) {
	this.menu.removeItems( [ this.menu.findItemFromData( item.getID() ) ] );
	this.placeholderItem.toggle( this.model.isEmpty() );
};

module.exports = SavedLinksListWidget;
