/**
 * A wrapper for OO.ui.SelectFileWidget
 *
 * @class
 * @private
 * @constructor
 */
function UploadParamWidget() {
	UploadParamWidget.parent.call( this );
}

OO.inheritClass( UploadParamWidget, OO.ui.SelectFileWidget );

/**
 * @return {string}
 */
UploadParamWidget.prototype.getApiValueForDisplay = function () {
	return '...';
};

/**
 * @return {Mixed}
 */
UploadParamWidget.prototype.getApiValue = function () {
	return this.getValue();
};

/**
 * There should be `@param {Mixed} newValue` but that results in
 * `no-unused-vars` eslint rule failing
 */
UploadParamWidget.prototype.setApiValue = function () {
	// No-op
};

/**
 * @param {boolean} shouldSuppressErrors
 * @return {jQuery.Promise}
 */
UploadParamWidget.prototype.apiCheckValid = function ( shouldSuppressErrors ) {
	var ok = this.getValue() !== null && this.getValue() !== undefined || shouldSuppressErrors;
	this.info.setIcon( ok ? null : 'alert' );
	this.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
	return $.Deferred().resolve( ok ).promise();
};

module.exports = UploadParamWidget;
