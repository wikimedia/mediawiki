/**
 * A wrapper for OO.ui.LabelWidget
 *
 * @class
 * @private
 * @constructor
 */
function ParamLabelWidget() {
	ParamLabelWidget.parent.call(
		this,
		{ classes: [ 'oo-ui-inline-help' ] }
	);
}

OO.inheritClass( ParamLabelWidget, OO.ui.LabelWidget );

/**
 * @param {jQuery} $description
 */
ParamLabelWidget.prototype.addDescription = function ( $description ) {
	this.$element.append(
		$( '<div>' ).addClass( 'description' ).append( $description )
	);
};

/**
 * @param {jQuery} $info
 */
ParamLabelWidget.prototype.addInfo = function ( $info ) {
	this.$element.append(
		$( '<div>' ).addClass( 'info' ).append( $info )
	);
};

module.exports = ParamLabelWidget;
