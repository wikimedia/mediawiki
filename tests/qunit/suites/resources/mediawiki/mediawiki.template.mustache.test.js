( function ( mw ) {

	QUnit.module( 'mediawiki.template', {
		setup: function () {
			// Stub register some templates
			this.sandbox.stub( mw.templates, 'get' ).returns( {
				'test_templates_foo.mustache': '<div>{{msg}}{{>partial}}</div>',
				'test_templates_foo_partial.mustache': ' goodbye'
			} );
		}
	} );


	QUnit.test( 'render', 2, function ( assert ) {
		var html, htmlPartial, data,
			template = mw.template.get( 'module.name.notimportant', 'test_templates_foo.mustache' ),
			partial = mw.template.get( 'module.name.notimportant', 'test_templates_foo_partial.mustache' );

		data = {
			msg: 'Hello'
		};

		html = template.render( data ).html();
		htmlPartial = template.render( data, {
			partial: partial
		} ).html();
		assert.strictEqual( html, 'Hello', 'Render without partial' );
		assert.strictEqual( htmlPartial, 'Hello goodbye', 'Render with partial' );
	} );

}( mediaWiki ) );
