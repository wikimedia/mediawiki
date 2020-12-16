/**
 * A wrapper for OO.ui.SelectFileWidget
 *
 * @class
 * @private
 * @constructor
 */
function UploadSelectFileParamWidget() {
	UploadSelectFileParamWidget.parent.call( this );
}

OO.inheritClass( UploadSelectFileParamWidget, OO.ui.SelectFileWidget );

/**
 * @return {string}
 */
UploadSelectFileParamWidget.prototype.getApiValueForDisplay = function () {
	return '...';
};

/**
 * @return {Mixed}
 */
UploadSelectFileParamWidget.prototype.getApiValue = function () {
	return this.getValue();
};

/**
 * There should be `@param {Mixed} newValue` but that results in
 * `no-unused-vars` eslint rule failing
 */
UploadSelectFileParamWidget.prototype.setApiValue = function () {
	// No-op
};

/**
 * @param {boolean} shouldSuppressErrors
 * @return {jQuery.Promise}
 */
UploadSelectFileParamWidget.prototype.apiCheckValid = function ( shouldSuppressErrors ) {
	var ok = this.getValue() !== null && this.getValue() !== undefined || shouldSuppressErrors;
	this.info.setIcon( ok ? null : 'alert' );
	this.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
	return $.Deferred().resolve( ok ).promise();
};

module.exports = UploadSelectFileParamWidget;
