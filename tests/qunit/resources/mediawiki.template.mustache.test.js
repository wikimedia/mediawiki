QUnit.module( 'mediawiki.template.mustache', ( hooks ) => {

	hooks.beforeEach( function () {
		// Stub register some templates
		this.sandbox.stub( mw.templates, 'get' ).returns( {
			'test_greeting.mustache': '<div>{{foo}}{{>suffix}}</div>',
			'test_greeting_suffix.mustache': ' goodbye'
		} );
	} );

	QUnit.test( 'render', function ( assert ) {
		const template = mw.template.get( 'stub', 'test_greeting.mustache' );
		const partial = mw.template.get( 'stub', 'test_greeting_suffix.mustache' );
		const data = {
			foo: 'Hello'
		};
		const partials = {
			suffix: partial
		};

		const html = template.render( data ).html();
		assert.strictEqual( html, 'Hello', 'Render without partial' );

		const htmlPartial = template.render( data, partials ).html();
		assert.strictEqual( htmlPartial, 'Hello goodbye', 'Render with partial' );
	} );
} );
