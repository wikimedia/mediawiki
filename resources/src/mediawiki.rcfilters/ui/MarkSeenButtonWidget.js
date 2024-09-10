/**
 * Button for marking all changes as seen on the Watchlist.
 *
 * @class mw.rcfilters.ui.MarkSeenButtonWidget
 * @ignore
 * @extends OO.ui.ButtonWidget
 *
 * @param {mw.rcfilters.Controller} controller
 * @param {mw.rcfilters.dm.ChangesListViewModel} model Changes list view model
 * @param {Object} [config] Configuration object
 */
const MarkSeenButtonWidget = function MwRcfiltersUiMarkSeenButtonWidget( controller, model, config ) {
	config = config || {};

	// Parent
	MarkSeenButtonWidget.super.call( this, Object.assign( {
		label: mw.msg( 'rcfilters-watchlist-markseen-button' ),
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
