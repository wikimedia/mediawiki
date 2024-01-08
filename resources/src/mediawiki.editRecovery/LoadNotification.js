/**
 * @class mw.widgets.EditRecovery.LoadNotification
 * @constructor
 * @extends OO.ui.Widget
 * @ignore
 */
const LoadNotification = function mwWidgetsEditRecoveryLoadNotification() {
	LoadNotification.super.call( this, {} );
	this.diffButton = new OO.ui.ButtonWidget( {
		label: mw.msg( 'edit-recovery-loaded-show' )
	} );
	this.discardButton = new OO.ui.ButtonWidget( {
		label: mw.msg( 'edit-recovery-loaded-discard' ),
		framed: false,
		flags: [ 'destructive' ]
	} );
	const $buttons = $( '<div>' )
		.addClass( 'mw-EditRecovery-LoadNotification-buttons' )
		.append(
			this.diffButton.$element,
			this.discardButton.$element
		);
	this.$element.append(
		mw.message( 'edit-recovery-loaded-message' ).escaped(),
		$buttons
	);
};

OO.inheritClass( LoadNotification, OO.ui.Widget );

/**
 * @ignore
 * @return {mw.Notification}
 */
LoadNotification.prototype.getNotification = function () {
	return mw.notification.notify( this.$element, {
		title: mw.msg( 'edit-recovery-loaded-title' ),
		autoHide: false
	} );
};

/**
 * @ignore
 * @return {OO.ui.ButtonWidget}
 */
LoadNotification.prototype.getDiffButton = function () {
	return this.diffButton;
};

/**
 * @ignore
 * @return {OO.ui.ButtonWidget}
 */
LoadNotification.prototype.getDiscardButton = function () {
	return this.discardButton;
};

module.exports = LoadNotification;
