( function ( $ ) {
	QUnit.module( 'jquery.placeholder', QUnit.newMwEnvironment() );

	QUnit.test( 'caches results of feature tests', function ( assert ) {
		assert.strictEqual( typeof $.fn.placeholder.input, 'boolean', '$.fn.placeholder.input' );
		assert.strictEqual( typeof $.fn.placeholder.textarea, 'boolean', '$.fn.placeholder.textarea' );
	} );
}( jQuery ) );
