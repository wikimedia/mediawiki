( function () {
	/**
	 * @class mw.widgets.InlineToggleField
	 * @extends OO.ui.FieldLayout
	 * @param {string} initDiffType
	 */
	function InlineToggleField( initDiffType ) {
		this.inlineToggleSwitch = new OO.ui.ToggleSwitchWidget( {
			value: initDiffType === 'inline'
		} );
		this.inlineToggleSwitch.connect( this, {
			change: this.onToggleSwitchChange,
			disable: this.onToggleSwitchDisabled
		} );
		var config = {
			label: mw.msg( 'diff-inline-format-label' ),
			classes: [ 'mw-diff-inline-toggle-layout' ]
		};

		InlineToggleField.super.call( this, this.inlineToggleSwitch, config );
	}
	OO.inheritClass( InlineToggleField, OO.ui.FieldLayout );

	/**
	 * Emit a 'change' event when the toggle switch selection changes.
	 *
	 * @private
	 */
	InlineToggleField.prototype.onToggleSwitchChange = function () {
		this.emit( 'change', this.inlineToggleSwitch.getValue() );
	};

	/**
	 * Emit a 'disabled' event when the toggle switch disabled state changes.
	 *
	 * @private
	 */
	InlineToggleField.prototype.onToggleSwitchDisabled = function () {
		this.emit( 'disable', this.inlineToggleSwitch.isDisabled() );
	};

	mw.widgets.InlineToggleField = InlineToggleField;
}() );
