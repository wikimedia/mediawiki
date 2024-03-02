/*!
 * MediaWiki Widgets - ToggleSwitchWidget class.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {
	/**
	 * This extends ToggleSwitchWidget by adding an invisible checkbox
	 * element which will be used to submit the value.
	 *
	 * @class
	 * @extends OO.ui.ToggleSwitchWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @param {string} [config.name] Name of input to submit results (when used in HTML forms)
	 */
	mw.widgets.ToggleSwitchWidget = function MwWidgetsToggleWidget( config ) {
		// Parent constructor
		mw.widgets.ToggleSwitchWidget.super.call( this, $.extend( {}, config, {} ) );

		if ( 'name' in config ) {
			// Use this instead of <input type="hidden">, because hidden inputs do not have separate
			// 'value' and 'defaultValue' properties.
			this.$hiddenInput = $( '<input>' )
				.addClass( 'oo-ui-element-hidden' )
				.attr( 'type', 'checkbox' )
				.attr( 'name', config.name )
				.attr( 'id', config.inputId )
				.appendTo( this.$element );
			// Update with preset values
			this.updateHiddenInput();
			// Set the default value (it might be different from just being empty)
			this.$hiddenInput.prop( 'defaultChecked', this.isSelected() );
		}

		// Events
		// When list of selected tags changes, update hidden input
		this.connect( this, {
			change: 'updateHiddenInput'
		} );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.ToggleSwitchWidget, OO.ui.ToggleSwitchWidget );

	mw.widgets.ToggleSwitchWidget.static.tagName = 'span';

	/* Methods */

	mw.widgets.ToggleSwitchWidget.prototype.isSelected = function () {
		return mw.widgets.ToggleSwitchWidget.super.prototype.getValue.call( this );
	};

	mw.widgets.ToggleSwitchWidget.prototype.getValue = function () {
		return this.isSelected() ? '1' : '0';
	};

	/**
	 * If used inside HTML form, then update hiddenInput.
	 *
	 * @private
	 */
	mw.widgets.ToggleSwitchWidget.prototype.updateHiddenInput = function () {
		if ( '$hiddenInput' in this ) {
			this.$hiddenInput.prop( 'checked', this.isSelected() );
			// Trigger a 'change' event as if a user edited the text
			// (it is not triggered when changing the value from JS code).
			this.$hiddenInput.trigger( 'change' );
		}
	};

}() );
