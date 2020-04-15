/**
 * Quick links menu option widget
 *
 * @class mw.rcfilters.ui.SavedLinksListItemWidget
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {mw.rcfilters.dm.SavedQueryItemModel} model View model
 * @param {Object} [config] Configuration object
 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
 */
var SavedLinksListItemWidget = function MwRcfiltersUiSavedLinksListWidget( model, config ) {
	config = config || {};

	this.model = model;

	// Parent
	SavedLinksListItemWidget.parent.call( this, $.extend( {
		data: this.model.getID(),
		label: this.model.getLabel(),
		title: this.model.getLabel()
	}, config ) );

	this.edit = false;
	this.$overlay = config.$overlay || this.$element;

	this.buttonMenu = new OO.ui.ButtonMenuSelectWidget( {
		classes: [ 'mw-rcfilters-ui-savedLinksListItemWidget-button' ],
		icon: 'ellipsis',
		framed: false,
		menu: {
			classes: [ 'mw-rcfilters-ui-savedLinksListItemWidget-menu' ],
			width: 200,
			horizontalPosition: 'end',
			$overlay: this.$overlay,
			items: [
				new OO.ui.MenuOptionWidget( {
					data: 'edit',
					icon: 'edit',
					label: mw.msg( 'rcfilters-savedqueries-rename' )
				} ),
				new OO.ui.MenuOptionWidget( {
					data: 'delete',
					icon: 'trash',
					label: mw.msg( 'rcfilters-savedqueries-remove' )
				} ),
				new OO.ui.MenuOptionWidget( {
					data: 'default',
					icon: 'pushPin',
					label: mw.msg( 'rcfilters-savedqueries-setdefault' )
				} )
			]
		}
	} );

	this.editInput = new OO.ui.TextInputWidget( {
		classes: [ 'mw-rcfilters-ui-savedLinksListItemWidget-input' ]
	} );
	this.saveButton = new OO.ui.ButtonWidget( {
		icon: 'check',
		flags: [ 'primary', 'progressive' ]
	} );
	this.toggleEdit( false );

	// Events
	this.model.connect( this, { update: 'onModelUpdate' } );
	this.buttonMenu.menu.connect( this, {
		choose: 'onMenuChoose'
	} );
	this.saveButton.connect( this, { click: 'save' } );
	this.editInput.connect( this, {
		change: 'onInputChange',
		enter: 'save'
	} );
	this.editInput.$input.on( {
		blur: this.onInputBlur.bind( this ),
		keyup: this.onInputKeyup.bind( this )
	} );
	this.$element.on( { mousedown: this.onMouseDown.bind( this ) } );
	this.$icon.on( { click: this.onDefaultIconClick.bind( this ) } );

	// Prevent clicks on interactive elements from closing the parent menu
	this.buttonMenu.$element.add( this.$icon ).on( 'mousedown', function ( e ) {
		e.stopPropagation();
	} );

	// Initialize
	this.toggleDefault( !!this.model.isDefault() );
	// eslint-disable-next-line mediawiki/class-doc
	this.$element
		.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget' )
		.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-query-' + this.model.getID() )
		.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-table' )
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-row' )
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-content' )
								.append(
									this.$label
										.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-label' ),
									this.editInput.$element,
									this.saveButton.$element
								),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-savedLinksListItemWidget-icon' )
								.append( this.$icon ),
							this.buttonMenu.$element
								.addClass( 'mw-rcfilters-ui-cell' )
						)
				)
		);
};

/* Initialization */
OO.inheritClass( SavedLinksListItemWidget, OO.ui.MenuOptionWidget );

/* Events */

/**
 * @event delete
 *
 * The delete option was selected for this item
 */

/**
 * @event default
 * @param {boolean} default Item is default
 *
 * The 'make default' option was selected for this item
 */

/**
 * @event edit
 * @param {string} newLabel New label for the query
 *
 * The label has been edited
 */

/* Methods */

/**
 * Respond to model update event
 */
SavedLinksListItemWidget.prototype.onModelUpdate = function () {
	this.setLabel( this.model.getLabel() );
	this.toggleDefault( this.model.isDefault() );
};

/**
 * Handle mousedown events
 *
 * @param {jQuery.Event} e
 */
SavedLinksListItemWidget.prototype.onMouseDown = function ( e ) {
	if ( this.editing ) {
		e.stopPropagation();
	}
};

/**
 * Respond to click on the 'default' icon. Open the submenu where the
 * default state can be changed.
 *
 * @return {boolean} false
 */
SavedLinksListItemWidget.prototype.onDefaultIconClick = function () {
	this.buttonMenu.menu.toggle();
	return false;
};

/**
 * Respond to menu choose event
 *
 * @param {OO.ui.MenuOptionWidget} item Chosen item
 * @fires delete
 * @fires default
 */
SavedLinksListItemWidget.prototype.onMenuChoose = function ( item ) {
	var action = item.getData();

	if ( action === 'edit' ) {
		this.toggleEdit( true );
	} else if ( action === 'delete' ) {
		this.emit( 'delete' );
	} else if ( action === 'default' ) {
		this.emit( 'default', !this.default );
	}
};

/**
 * Respond to input keyup event, this is the way to intercept 'escape' key
 *
 * @param {jQuery.Event} e Event data
 * @return {boolean} false
 */
SavedLinksListItemWidget.prototype.onInputKeyup = function ( e ) {
	if ( e.which === OO.ui.Keys.ESCAPE ) {
		// Return the input to the original label
		this.editInput.setValue( this.getLabel() );
		this.toggleEdit( false );
		return false;
	}
};

/**
 * Respond to blur event on the input
 */
SavedLinksListItemWidget.prototype.onInputBlur = function () {
	this.save();

	// Whether the save succeeded or not, the input-blur event
	// means we need to cancel editing mode
	this.toggleEdit( false );
};

/**
 * Respond to input change event
 *
 * @param {string} value Input value
 */
SavedLinksListItemWidget.prototype.onInputChange = function ( value ) {
	value = value.trim();

	this.saveButton.setDisabled( !value );
};

/**
 * Save the name of the query
 *
 * @fires edit
 */
SavedLinksListItemWidget.prototype.save = function () {
	var value = this.editInput.getValue().trim();

	if ( value ) {
		this.emit( 'edit', value );
		this.toggleEdit( false );
	}
};

/**
 * Toggle edit mode on this widget
 *
 * @param {boolean} isEdit Widget is in edit mode
 */
SavedLinksListItemWidget.prototype.toggleEdit = function ( isEdit ) {
	isEdit = isEdit === undefined ? !this.editing : isEdit;

	if ( this.editing !== isEdit ) {
		this.$element.toggleClass( 'mw-rcfilters-ui-savedLinksListItemWidget-edit', isEdit );
		this.editInput.setValue( this.getLabel() );

		this.editInput.toggle( isEdit );
		this.$label.toggleClass( 'oo-ui-element-hidden', isEdit );
		this.$icon.toggleClass( 'oo-ui-element-hidden', isEdit );
		this.buttonMenu.toggle( !isEdit );
		this.saveButton.toggle( isEdit );

		if ( isEdit ) {
			this.editInput.focus();
		}
		this.editing = isEdit;
	}
};

/**
 * Toggle default this widget
 *
 * @param {boolean} isDefault This item is default
 */
SavedLinksListItemWidget.prototype.toggleDefault = function ( isDefault ) {
	isDefault = isDefault === undefined ? !this.default : isDefault;

	if ( this.default !== isDefault ) {
		this.default = isDefault;
		this.setIcon( this.default ? 'pushPin' : '' );
		this.buttonMenu.menu.findItemFromData( 'default' ).setLabel(
			this.default ?
				mw.msg( 'rcfilters-savedqueries-unsetdefault' ) :
				mw.msg( 'rcfilters-savedqueries-setdefault' )
		);
	}
};

/**
 * Get item ID
 *
 * @return {string} Query identifier
 */
SavedLinksListItemWidget.prototype.getID = function () {
	return this.model.getID();
};

module.exports = SavedLinksListItemWidget;
