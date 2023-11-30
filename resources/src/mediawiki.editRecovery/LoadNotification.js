/**
 * @class mw.widgets.EditRecovery.LoadNotification
 * @constructor
 * @extends OO.ui.Widget
 * @ignore
 * @param {Object} config
 * @param {boolean} config.differentRev Whether to display the 'different revision' warning.
 */
const LoadNotification = function mwWidgetsEditRecoveryLoadNotification( config ) {
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
	let $differentRev = null;
	if ( config.differentRev ) {
		$differentRev = $( '<em>' )
			.text( mw.message( 'edit-recovery-loaded-message-different-rev' ) );
	}
	this.$element.append(
		mw.message( 'edit-recovery-loaded-message' ).escaped(),
		' ',
		$differentRev,
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
