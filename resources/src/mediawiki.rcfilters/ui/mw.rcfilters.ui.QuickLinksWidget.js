( function ( mw ) {
	/**
	 * Quick links widget
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.SavedQueriesModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.QuickLinksWidget = function MwRcfiltersUiQuickLinksWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.QuickLinksWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		this.menu = new OO.ui.ButtonGroupWidget( {
			classes: [ 'mw-rcfilters-ui-quickLinksWidget-menu' ]
		} );
		this.button = new OO.ui.PopupButtonWidget( {
			classes: [ 'mw-rcfilters-ui-quickLinksWidget-button' ],
			label: mw.msg( 'rcfilters-quickfilters' ),
			icon: 'bookmark',
			$overlay: this.$overlay,
			popup: {
				anchor: false,
				$autoCloseIgnore: this.$overlay,
				$content: this.menu.$element
			}
		} );

		this.menu.aggregate( {
			click: 'menuItemClick',
			delete: 'menuItemDelete',
			default: 'menuItemDefault',
			edit: 'menuItemEdit'
		} );

		// Events
		this.button.connect( this, { click: 'onButtonClick' } );
		this.model.connect( this, {
			// initialize: 'onModelInitialize',
			add: 'onModelAddItem',
			remove: 'onModelRemoveItem'
		} );
		this.menu.connect( this, {
			menuItemClick: 'onMenuItemClick',
			menuItemRemove: 'onMenuItemRemove',
			menuItemDefault: 'onMenuItemDefault',
			menuItemEdit: 'onMenuItemEdit'
		} );

		this.button.toggle( !this.menu.isEmpty() );
		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-quickLinksWidget' )
			.append( this.button.$element );

	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.QuickLinksWidget, OO.ui.Widget );

	mw.rcfilters.ui.QuickLinksWidget.prototype.onButtonClick = function () {
		this.menu.toggle( true );
	};

	mw.rcfilters.ui.QuickLinksWidget.prototype.onMenuItemClick = function ( item ) {
		this.controller.loadSavedQuery( item.getID() );
	};
	mw.rcfilters.ui.QuickLinksWidget.prototype.onMenuItemRemove = function ( item ) {
		this.controller.removeSavedQuery( item.getID() );
		this.menu.removeItems( [ item ] );
	};

	mw.rcfilters.ui.QuickLinksWidget.prototype.onMenuItemDefault = function ( item ) {
		debugger;
	};

	mw.rcfilters.ui.QuickLinksWidget.prototype.onMenuItemEdit = function ( item, newLabel ) {
		this.controller.renameSavedQuery( item.getID(), newLabel );
	};



	// mw.rcfilters.ui.QuickLinksWidget.prototype.onModelInitialize = function () {
	// 	var menuOptions = [];

	// 	this.model.getItems().forEach( function ( item ) {
	// 		menuOptions.push(
	// 			new mw.rcfilters.ui.QuickLinkMenuOptionWidget( item )
	// 		);
	// 	} );

	// 	this.menu.addItems( menuOptions );
	// 	this.button.toggle( !this.menu.isEmpty() );
	// };


	mw.rcfilters.ui.QuickLinksWidget.prototype.onModelAddItem = function ( item ) {
		if ( this.menu.getItemFromData( item.getID() ) ) {
			return;
		}

		this.menu.addItems( [
			new mw.rcfilters.ui.QuickLinkMenuOptionWidget( item, { $overlay: this.$overlay } )
		] );
		this.button.toggle( !this.menu.isEmpty() );
	};
	mw.rcfilters.ui.QuickLinksWidget.prototype.onModelRemoveItem = function ( item ) {
		this.menu.removeItems( [ this.model.getItemByID( item.getID() ) ] );
		this.button.toggle( !this.menu.isEmpty() );
	};
}( mediaWiki ) );
