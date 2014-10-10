( function ( mw, $ ) {

	QUnit.module( 'Templates', {
		setup: function () {
			var abcCompiler = {
				compile: function () {
					return 'abc default compiler';
				}
			};
			// Register some template compiler languages
			mw.template.registerCompiler( 'abc', abcCompiler );
			mw.template.registerCompiler( 'xyz', {
				compile: function () {
					return 'xyz compiler';
				}
			} );

			// register some templates
			mw.templates.set( {
				'test.mediawiki.templates': {
					'test_templates_foo.xyz': 'goodbye',
					'test_templates_foo.abc': 'thankyou'
				}
			} );
		}
	} );

	QUnit.test( 'Template, getCompiler - default case', 4, function ( assert ) {
		assert.throws( function () {
				mw.template.add( 'module', 'test_templates_foo', 'hello' );
			}, 'When no prefix throw exception.' );
		assert.throws( function () {
				mw.template.compile( '{{foo}}', 'rainbow' );
			}, 'Unknown compiler names throw exceptions.' );
		assert.strictEqual( mw.template.get( 'test.mediawiki.templates', 'test_templates_foo.xyz' ), 'xyz compiler' );
		assert.strictEqual( mw.template.get( 'test.mediawiki.templates', 'test_templates_foo.abc' ), 'abc default compiler' );
	} );

	QUnit.test( 'Template, get module that is not loaded.', 2, function ( assert ) {
		assert.throws( function () {
				mw.template.get( 'this.should.not.exist', 'hello' );
			}, 'When bad module name given throw error.' );

		assert.throws( function () {
				mw.template.get( 'mediawiki.templates', 'hello' );
			}, 'The template hello should not exist in the mediawiki.templates module and should throw an exception.' );
	} );

}( mediaWiki, jQuery ) );
