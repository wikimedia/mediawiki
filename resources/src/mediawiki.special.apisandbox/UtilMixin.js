/**
 * Simple mixin to allow all widgets to use `apiBool`
 *
 * @class
 * @private
 * @constructor
 */
function UtilMixin() {
	// Nothing
}

/**
 * Test an API boolean
 *
 * @param {any} value
 * @return {boolean}
 */
UtilMixin.prototype.apiBool = function ( value ) {
	return value !== undefined && value !== false;
};

module.exports = UtilMixin;
