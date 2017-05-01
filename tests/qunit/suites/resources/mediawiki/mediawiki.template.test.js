( function ( mw ) {

	QUnit.module( 'mediawiki.template', {
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

			// Stub register some templates
			this.sandbox.stub( mw.templates, 'get' ).returns( {
				'test_templates_foo.xyz': 'goodbye',
				'test_templates_foo.abc': 'thankyou'
			} );
		}
	} );

	QUnit.test( 'add', 1, function ( assert ) {
		assert.throws(
			function () {
				mw.template.add( 'module', 'test_templates_foo', 'hello' );
			},
			'When no prefix throw exception'
		);
	} );

	QUnit.test( 'compile', 1, function ( assert ) {
		assert.throws(
			function () {
				mw.template.compile( '{{foo}}', 'rainbow' );
			},
			'Unknown compiler names throw exceptions'
		);
	} );

	QUnit.test( 'get', 4, function ( assert ) {
		assert.strictEqual( mw.template.get( 'test.mediawiki.template', 'test_templates_foo.xyz' ), 'xyz compiler' );
		assert.strictEqual( mw.template.get( 'test.mediawiki.template', 'test_templates_foo.abc' ), 'abc default compiler' );
		assert.throws(
			function () {
				mw.template.get( 'this.should.not.exist', 'hello' );
			},
			'When bad module name given throw error.'
		);

		assert.throws(
			function () {
				mw.template.get( 'mediawiki.template', 'hello' );
			},
			'The template hello should not exist in the mediawiki.templates module and should throw an exception.'
		);
	} );

}( mediaWiki ) );
