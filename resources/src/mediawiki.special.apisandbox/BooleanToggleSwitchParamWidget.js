const UtilMixin = require( './UtilMixin.js' );

/**
 * A wrapper for OO.ui.ToggleSwitchWidget
 *
 * @class
 * @private
 * @constructor
 */
function BooleanToggleSwitchParamWidget() {
	BooleanToggleSwitchParamWidget.super.call( this );
}

OO.inheritClass( BooleanToggleSwitchParamWidget, OO.ui.ToggleSwitchWidget );
OO.mixinClass( BooleanToggleSwitchParamWidget, UtilMixin );

/**
 * @return {number|undefined}
 */
BooleanToggleSwitchParamWidget.prototype.getApiValue = function () {
	return this.getValue() ? 1 : undefined;
};

/**
 * @param {any} newValue
 */
BooleanToggleSwitchParamWidget.prototype.setApiValue = function ( newValue ) {
	this.setValue( this.apiBool( newValue ) );
};

/**
 * @return {jQuery.Promise}
 */
BooleanToggleSwitchParamWidget.prototype.apiCheckValid = function () {
	return $.Deferred().resolve( true ).promise();
};

module.exports = BooleanToggleSwitchParamWidget;
