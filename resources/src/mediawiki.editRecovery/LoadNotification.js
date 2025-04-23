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
	this.recoverButton = new OO.ui.ButtonWidget( {
		label: mw.msg( 'edit-recovery-loaded-recover' ),
		flags: [ 'progressive' ]
	} );
	this.discardButton = new OO.ui.ButtonWidget( {
		label: mw.msg( 'edit-recovery-loaded-discard' ),
		framed: false,
		flags: [ 'destructive' ]
	} );
	const $buttons = $( '<div>' )
		.addClass( 'mw-special-EditRecovery-LoadNotification-buttons' )
		.append(
			this.recoverButton.$element,
			this.discardButton.$element
		);
	let differentRev = null;
	let differentRevSave = null;
	if ( config.differentRev ) {
		differentRev = mw.message( 'edit-recovery-loaded-message-different-rev' ).parse();
		differentRevSave = mw.config.get( 'wgEditSubmitButtonLabelPublish' ) ?
			mw.message( 'edit-recovery-loaded-message-different-rev-publish' ).parse() :
			mw.message( 'edit-recovery-loaded-message-different-rev-save' ).parse();
	}
	this.$element.append(
		mw.message( 'edit-recovery-loaded-message' ).escaped(),
		mw.message( 'word-separator' ).parse(),
		differentRev,
		mw.message( 'word-separator' ).parse(),
		differentRevSave,
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
LoadNotification.prototype.getRecoverButton = function () {
	return this.recoverButton;
};

/**
 * @ignore
 * @return {OO.ui.ButtonWidget}
 */
LoadNotification.prototype.getDiscardButton = function () {
	return this.discardButton;
};

module.exports = LoadNotification;
