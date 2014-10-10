( function( M, $ ) {

	QUnit.module( 'Templates', {
		setup: function() {
			var abcCompiler = {
				registerPartial: $.noop,
				compile: function() { return 'abc default compiler'; }
			};
			// Register some template compiler languages
			M.template.registerCompiler( 'abc', abcCompiler );
			M.template.registerCompiler( 'xyz', {
				registerPartial: $.noop,
				compile: function() { return 'xyz compiler'; }
			} );

			// register some templates
			M.template.add( 'test_templates_foo.xyz', 'goodbye' );
			M.template.add( 'test_templates_foo.abc', 'thankyou' );
		}
	} );

	QUnit.test( 'Template, getCompiler - default case', 4, function( assert ) {
		assert.throws( function() {
				M.template.add( 'test_templates_foo', 'hello' );
			}, 'When no prefix throw exception.' );
		assert.throws( function() {
				M.template.compile( '{{foo}}', 'rainbow' );
			}, 'Unknown compiler names throw exceptions.' );
		assert.strictEqual( M.template.get( 'test_templates_foo.xyz' ), 'xyz compiler' );
		assert.strictEqual( M.template.get( 'test_templates_foo.abc' ), 'abc default compiler' );
	} );

}( mediaWiki, jQuery ) );
