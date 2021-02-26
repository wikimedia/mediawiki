var TextParamMixin = require( './TextParamMixin.js' ),
	UtilMixin = require( './UtilMixin.js' );

/**
 * A wrapper for mw.widgets.datetime.DateTimeInputWidget
 *
 * @class
 * @private
 * @constructor
 * @param {Object} config Configuration options
 */
function DateTimeParamWidget( config ) {
	config.formatter = {
		format: '${year|0}-${month|0}-${day|0}T${hour|0}:${minute|0}:${second|0}${zone|short}'
	};
	config.clearable = false;

	DateTimeParamWidget.parent.call( this, config );
}

OO.inheritClass( DateTimeParamWidget, mw.widgets.datetime.DateTimeInputWidget );
OO.mixinClass( DateTimeParamWidget, TextParamMixin );
OO.mixinClass( DateTimeParamWidget, UtilMixin );

/**
 * @return {jQuery.Promise}
 */
DateTimeParamWidget.prototype.getValidity = function () {
	if ( !this.apiBool( this.paramInfo.required ) || this.getApiValue() !== '' ) {
		return $.Deferred().resolve().promise();
	} else {
		return $.Deferred().reject().promise();
	}
};

module.exports = DateTimeParamWidget;
