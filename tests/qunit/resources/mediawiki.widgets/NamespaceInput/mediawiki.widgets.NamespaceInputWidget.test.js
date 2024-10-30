/*!
 * MediaWiki Widgets NamespaceInputWidget tests.
 */

QUnit.module( 'mediawiki.widgets.NamespaceInputWidget' );

( function () {
	const widgetA = new mw.widgets.NamespaceInputWidget( {} );
	const widgetB = new mw.widgets.NamespaceInputWidget( {
		include: [ 0 ]
	} );

	QUnit.test( 'NamespaceInputWidget initialization', ( assert ) => {
		// Assert that the input widget initialized
		assert.true( widgetA.$element.has( 'select' ).length > 0, 'Select input initialized successfully' );

		// Assert that the main namespace (0) exists - every wiki should have this
		assert.true( widgetA.$element.find( 'option[value=0]' ).length > 0, 'Filter for main name space exists' );
	} );

	QUnit.test( 'NamespaceInputWidget \'include\' config parameter', ( assert ) => {
		// Assert that only the main namespace filters is available
		assert.true( widgetB.$element.find( 'option' ).length === 1, 'Only one filter option is available' );
		assert.true( widgetB.$element.find( 'option[value=0]' ).length > 0, 'Filter for main name space exists' );
	} );
}() );
