const TextParamMixin = require( './TextParamMixin.js' );

/**
 * A wrapper for OO.ui.TextInputWidget
 *
 * @class
 * @private
 * @constructor
 * @param {Object} config Configuration options
 */
function LimitParamWidget( config ) {
	LimitParamWidget.super.call( this, config );
}

OO.inheritClass( LimitParamWidget, OO.ui.TextInputWidget );
OO.mixinClass( LimitParamWidget, TextParamMixin );

/**
 * For a limit parameter, a value can either be "max" or a number
 * that is
 *  - not NaN (not "Not-A-Number")
 *  - finite
 *  - not a decimal (identical to Math.floor with that number)
 *  - at least the minimum set in paramInfo.min
 *  - at most the maximum set in paramInfo.apiSandboxMax
 *
 * this.paramInfo should be set by the code using this widget, and
 * should have `min` and `apiSandboxMax` set as intergers
 *
 * @param {string} value
 * @return {boolean}
 */
LimitParamWidget.prototype.validate = function ( value ) {
	let n;
	if ( value === 'max' ) {
		return true;
	} else {
		n = +value;
		return !isNaN( n ) &&
			isFinite( n ) &&
			Math.floor( n ) === n &&
			n >= this.paramInfo.min &&
			n <= this.paramInfo.apiSandboxMax;
	}
};

module.exports = LimitParamWidget;
