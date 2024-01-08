/**
 * Simple mixin for text parameters. Should only be used within
 * the apisandbox code, and for objects that have
 *  - a getValue function
 *  - a setValue function
 *  - a getValidity function
 *  - a setIcon function
 *  - a setTitle function
 *  - this.paramInfo object set
 *
 * @class
 * @private
 * @constructor
 */
function TextParamMixin() {
	// This mixin does not manage state, nothing to do here
}

/**
 * @return {any}
 */
TextParamMixin.prototype.getApiValue = function () {
	return this.getValue();
};

/**
 * @param {any|undefined} newValue
 */
TextParamMixin.prototype.setApiValue = function ( newValue ) {
	if ( newValue === undefined ) {
		newValue = this.paramInfo.default;
	}
	this.setValue( newValue );
};

/**
 * Check if a text parameter is valid for the api, and if the result is not valid, set the icon
 * to 'alert' and the title to a message explaining that the value is invalid. If shouldSuppressErrors
 * is true, then the result of the validity check is always treated as valid.
 *
 * @param {boolean} shouldSuppressErrors
 * @return {jQuery.Promise}
 */
TextParamMixin.prototype.apiCheckValid = function ( shouldSuppressErrors ) {
	var that = this;
	return this.getValidity().then( function () {
		return $.Deferred().resolve( true ).promise();
	}, function () {
		return $.Deferred().resolve( false ).promise();
	} ).done( function ( ok ) {
		ok = ok || shouldSuppressErrors;
		that.setIcon( ok ? null : 'alert' );
		that.setTitle( ok ? '' : mw.message( 'apisandbox-alert-field' ).plain() );
	} );
};

module.exports = TextParamMixin;
