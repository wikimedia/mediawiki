( function ( mw ) {

	QUnit.module( 'mediawiki.template.mustache', {
		setup: function () {
			// Stub register some templates
			this.sandbox.stub( mw.templates, 'get' ).returns( {
				'test_greeting.mustache': '<div>{{foo}}{{>suffix}}</div>',
				'test_greeting_suffix.mustache': ' goodbye'
			} );
		}
	} );

	QUnit.test( 'render', function ( assert ) {
		var html, htmlPartial, data, partials,
			template = mw.template.get( 'stub', 'test_greeting.mustache' ),
			partial = mw.template.get( 'stub', 'test_greeting_suffix.mustache' );

		data = {
			foo: 'Hello'
		};
		partials = {
			suffix: partial
		};

		html = template.render( data ).html();
		htmlPartial = template.render( data, partials ).html();

		assert.strictEqual( html, 'Hello', 'Render without partial' );
		assert.strictEqual( htmlPartial, 'Hello goodbye', 'Render with partial' );
	} );

}( mediaWiki ) );
