var TextParamMixin = require( './TextParamMixin.js' );

/**
 * A wrapper for OO.ui.NumberInputWidget
 *
 * @class
 * @private
 * @constructor
 * @param {Object} config Configuration options
 */
function IntegerParamWidget( config ) {
	config.isInteger = true;
	IntegerParamWidget.parent.call( this, config );

	this.setIcon = this.input.setIcon.bind( this.input );
	this.setTitle = this.input.setTitle.bind( this.input );
	this.getValidity = this.input.getValidity.bind( this.input );
}

OO.inheritClass( IntegerParamWidget, OO.ui.NumberInputWidget );
OO.mixinClass( IntegerParamWidget, TextParamMixin );

module.exports = IntegerParamWidget;
