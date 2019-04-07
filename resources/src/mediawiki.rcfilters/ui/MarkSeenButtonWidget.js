/**
 * Button for marking all changes as seen on the Watchlist
 *
 * @class mw.rcfilters.ui.MarkSeenButtonWidget
 * @extends OO.ui.ButtonWidget
 *
 * @constructor
 * @param {mw.rcfilters.Controller} controller
 * @param {mw.rcfilters.dm.ChangesListViewModel} model Changes list view model
 * @param {Object} [config] Configuration object
 */
var MarkSeenButtonWidget = function MwRcfiltersUiMarkSeenButtonWidget( controller, model, config ) {
	config = config || {};

	// Parent
	MarkSeenButtonWidget.parent.call( this, $.extend( {
		label: mw.message( 'rcfilters-watchlist-markseen-button' ).text(),
		icon: 'checkAll'
	}, config ) );

	this.controller = controller;
	this.model = model;

	// Events
	this.connect( this, { click: 'onClick' } );
	this.model.connect( this, { update: 'onModelUpdate' } );

	this.$element.addClass( 'mw-rcfilters-ui-markSeenButtonWidget' );

	this.onModelUpdate();
};

/* Initialization */

OO.inheritClass( MarkSeenButtonWidget, OO.ui.ButtonWidget );

/* Methods */

/**
 * Respond to the button being clicked
 */
MarkSeenButtonWidget.prototype.onClick = function () {
	this.controller.markAllChangesAsSeen();
	// assume there's no more unseen changes until the next model update
	this.setDisabled( true );
};

/**
 * Respond to the model being updated with new changes
 */
MarkSeenButtonWidget.prototype.onModelUpdate = function () {
	this.setDisabled( !this.model.hasUnseenWatchedChanges() );
};

module.exports = MarkSeenButtonWidget;
