( function ( mw, $ ) {

	QUnit.module( 'Templates', {
		setup: function () {
			var abcCompiler = {
				registerPartial: $.noop,
				compile: function () {
					return 'abc default compiler';
				}
			};
			// Register some template compiler languages
			mw.template.registerCompiler( 'abc', abcCompiler );
			mw.template.registerCompiler( 'xyz', {
				registerPartial: $.noop,
				compile: function () {
					return 'xyz compiler';
				}
			} );

			// register some templates
			mw.templates.set( {
				'test_templates_foo.xyz': 'goodbye',
				'test_templates_foo.abc': 'thankyou'
			} );
		}
	} );

	QUnit.test( 'Template, getCompiler - default case', 4, function ( assert ) {
		assert.throws( function () {
				mw.template.add( 'test_templates_foo', 'hello' );
			}, 'When no prefix throw exception.' );
		assert.throws( function () {
				mw.template.compile( '{{foo}}', 'rainbow' );
			}, 'Unknown compiler names throw exceptions.' );
		assert.strictEqual( mw.template.get( 'test_templates_foo.xyz' ), 'xyz compiler' );
		assert.strictEqual( mw.template.get( 'test_templates_foo.abc' ), 'abc default compiler' );
	} );

}( mediaWiki, jQuery ) );
