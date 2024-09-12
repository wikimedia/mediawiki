const TextParamMixin = require( './TextParamMixin.js' ),
	UtilMixin = require( './UtilMixin.js' );

/**
 * A wrapper for OO.ui.TextInputWidget
 *
 * @class
 * @private
 * @constructor
 * @param {Object} config Configuration options
 */
function PasswordParamWidget( config ) {
	config.type = 'password';
	PasswordParamWidget.super.call( this, config );
}

OO.inheritClass( PasswordParamWidget, OO.ui.TextInputWidget );
OO.mixinClass( PasswordParamWidget, TextParamMixin );
OO.mixinClass( PasswordParamWidget, UtilMixin );

/**
 * @return {string}
 */
PasswordParamWidget.prototype.getApiValueForDisplay = function () {
	return '';
};

module.exports = PasswordParamWidget;
