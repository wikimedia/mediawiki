( function ( mw, $ ) {
	QUnit.module( 'mediawiki.api.upload', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( 'Basic functionality', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api();
		assert.ok( api.upload );
		// The below will return a rejected deferred, but that's OK.
		assert.ok( api.upload() );
	} );

	QUnit.test( 'Set up iframe upload', function ( assert ) {
		QUnit.expect( 5 );
		var iframe, $form, $input,
			api = new mw.Api();

		this.sandbox.stub( api, 'getEditToken', function () {
			return $.Deferred().promise();
		} );

		api.uploadWithIframe( $( '<input>' )[0], { filename: 'Testing API upload.jpg' } );

		iframe = $( 'iframe' )[0];
		$form = $( 'form.mw-api-upload-form' );
		$input = $form.find( 'input[name=filename]' );

		assert.ok( $form.length > 0 );
		assert.ok( $input.length > 0 );
		assert.ok( iframe );
		assert.strictEqual( $form.prop( 'target' ), iframe.id );
		assert.strictEqual( $input.val(), 'Testing API upload.jpg' );
	} );

}( mediaWiki, jQuery ) );
