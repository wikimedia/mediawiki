/**
 * MediaWiki Widgets UserInputWidget tests.
 */

QUnit.module( 'mediawiki.widgets.UserInputWidget' );

( function () {
	const widgetWithDefaults = new mw.widgets.UserInputWidget( {} );
	const widgetExcludingNamedAndTemp = new mw.widgets.UserInputWidget( {
		excludetemp: true,
		excludenamed: true
	} );

	QUnit.test( 'UserInputWidget initialization sets lookup status correctly', ( assert ) => {
		assert.false( widgetWithDefaults.alwaysDisableLookups, 'Normal widget should have lookups enabled' );
		assert.true( widgetExcludingNamedAndTemp.alwaysDisableLookups, 'Widget where named and temp users are excluded always should have lookups disabled' );
		assert.false( widgetWithDefaults.lookupsDisabled, 'Normal widget should have lookups enabled initially' );
		assert.true( widgetExcludingNamedAndTemp.lookupsDisabled, 'Widget where named and temp users are excluded should always have lookups disabled' );
	} );

	QUnit.test( 'UserInputWidget.setLookupsDisabled for default widget', ( assert ) => {
		// Test that setLookupsDisabled() works normally for a default UserInputWidget
		widgetWithDefaults.setLookupsDisabled( true );
		assert.true( widgetWithDefaults.lookupsDisabled, 'Normal widget should allow lookups to be disabled' );
		widgetWithDefaults.setLookupsDisabled( false );
		assert.false( widgetWithDefaults.lookupsDisabled, 'Normal widget should allow lookups to be enabled' );
	} );

	QUnit.test( 'setLookupsDisabled() for widget that excludes named and temp users', ( assert ) => {
		// Test that setLookupsDisabled() does not allow enabling lookups
		widgetExcludingNamedAndTemp.setLookupsDisabled( false );
		assert.true( widgetExcludingNamedAndTemp.lookupsDisabled, 'Widget that disallows temp and named users should not allow lookups' );
	} );

	QUnit.test( 'onLookupMenuChoose() for widget that excludes named and temp users', ( assert ) => {
		// Test that onLookupMenuChoose() does not re-enable the lookups
		widgetExcludingNamedAndTemp.onLookupMenuChoose( new OO.ui.MenuOptionWidget( { data: 'abc', label: 'test' } ) );
		assert.true( widgetExcludingNamedAndTemp.lookupsDisabled, 'Widget that disallows temp and named users should not allow lookups' );
		assert.strictEqual( widgetExcludingNamedAndTemp.$input.val(), 'abc', 'Value correctly set by ::onLookupMenuChoose' );
	} );

	QUnit.test( 'onLookupMenuChoose() for widget wth default config', ( assert ) => {
		// Test that onLookupMenuChoose() re-enables lookup after the call, even if it was disabled before the call.
		widgetWithDefaults.setLookupsDisabled( true );
		widgetWithDefaults.onLookupMenuChoose( new OO.ui.MenuOptionWidget( { data: 'abc', label: 'test' } ) );
		assert.false( widgetWithDefaults.lookupsDisabled, 'Widget that with default config should have re-enabled lookup after call' );
		assert.strictEqual( widgetExcludingNamedAndTemp.$input.val(), 'abc', 'Value correctly set by ::onLookupMenuChoose' );
	} );
}() );
