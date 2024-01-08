/**
 * A wrapper for a widget that provides an enable/disable button
 *
 * @class
 * @private
 * @constructor
 * @param {OO.ui.Widget} widget
 * @param {Object} [config] Configuration options
 */
function OptionalParamWidget( widget, config ) {
	var k;

	config = config || {};

	this.widget = widget;
	this.$cover = config.$cover ||
		$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-cover' );
	this.checkbox = new OO.ui.CheckboxInputWidget( config.checkbox )
		.on( 'change', this.onCheckboxChange, [], this );

	OptionalParamWidget.super.call( this, config );

	// Forward most methods for convenience
	for ( k in this.widget ) {
		if ( typeof this.widget[ k ] === 'function' && !this[ k ] ) {
			this[ k ] = this.widget[ k ].bind( this.widget );
		}
	}

	widget.connect( this, {
		change: [ this.emit, 'change' ]
	} );

	this.$cover.on( 'click', this.onOverlayClick.bind( this ) );

	this.$element
		.addClass( 'mw-apisandbox-optionalWidget' )
		.append(
			this.$cover,
			$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-fields' ).append(
				$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-widget' ).append(
					widget.$element
				),
				$( '<div>' ).addClass( 'mw-apisandbox-optionalWidget-checkbox' ).append(
					this.checkbox.$element
				)
			)
		);

	this.setDisabled( widget.isDisabled() );
}

OO.inheritClass( OptionalParamWidget, OO.ui.Widget );

/**
 * @param {boolean} checked
 */
OptionalParamWidget.prototype.onCheckboxChange = function ( checked ) {
	this.setDisabled( !checked );
};

OptionalParamWidget.prototype.onOverlayClick = function () {
	this.setDisabled( false );
	if ( typeof this.widget.focus === 'function' ) {
		this.widget.focus();
	}
};

/**
 * @param {boolean} disabled
 * @return {OptionalParamWidget}
 * @chainable
 */
OptionalParamWidget.prototype.setDisabled = function ( disabled ) {
	OptionalParamWidget.super.prototype.setDisabled.call( this, disabled );
	this.widget.setDisabled( this.isDisabled() );
	this.checkbox.setSelected( !this.isDisabled() );
	this.$cover.toggle( this.isDisabled() );
	this.emit( 'change' );
	return this;
};

/**
 * @return {any|undefined}
 */
OptionalParamWidget.prototype.getApiValue = function () {
	return this.isDisabled() ? undefined : this.widget.getApiValue();
};

/**
 * @param {any|undefined} newValue
 */
OptionalParamWidget.prototype.setApiValue = function ( newValue ) {
	this.setDisabled( newValue === undefined );
	this.widget.setApiValue( newValue );
};

/**
 * @return {jQuery.Promise}
 */
OptionalParamWidget.prototype.apiCheckValid = function () {
	if ( this.isDisabled() ) {
		return $.Deferred().resolve( true ).promise();
	} else {
		return this.widget.apiCheckValid();
	}
};

module.exports = OptionalParamWidget;
