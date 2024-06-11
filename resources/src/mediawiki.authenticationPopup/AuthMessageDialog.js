/**
 * Message dialog used as a backdrop for the browser popup window.
 *
 * @class
 * @private
 */
function AuthMessageDialog() {
	AuthMessageDialog.super.apply( this, arguments );
}
OO.inheritClass( AuthMessageDialog, OO.ui.MessageDialog );

AuthMessageDialog.static.name = 'authMessageDialog';
AuthMessageDialog.static.size = 'medium';
AuthMessageDialog.static.title = null;
AuthMessageDialog.static.message = null;
AuthMessageDialog.static.actions = [
	{
		action: 'retry',
		label: OO.ui.deferMsg( 'userlogin-authpopup-retry' ),
		flags: [ 'primary', 'progressive' ]
	},
	{
		action: 'cancel',
		label: OO.ui.deferMsg( 'userlogin-authpopup-cancel' ),
		flags: 'safe'
	}
];

AuthMessageDialog.prototype.getBodyHeight = function () {
	// Leaving 200px on the top and bottom of the user's screen feels about right.
	// Keep the height to at least 500px though (which is the same as the width).
	// This height is also clamped by the browser window height.
	return Math.max( document.body.clientHeight - 400, 500 );
};

AuthMessageDialog.prototype.getActionProcess = function ( action ) {
	// We emit the event immediately, outside the async process, because of iOS Safari, which only
	// allows window.open() when it occurs immediately in response to a user-initiated event like
	// 'click', not async.
	this.emit( action || 'cancel' );
	// Unlike in normal OOUI windows, none of the actions closes the dialog.
	// It may be closed from the outside by the module in response to this event.
	return new OO.ui.Process( () => {} );
};

module.exports = AuthMessageDialog;
