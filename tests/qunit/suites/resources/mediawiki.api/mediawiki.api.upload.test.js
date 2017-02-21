( function ( mw, $ ) {
	QUnit.module( 'mediawiki.api.upload', QUnit.newMwEnvironment( {} ) );

	QUnit.test( 'Basic functionality', function ( assert ) {
		var api = new mw.Api();
		assert.ok( api.upload );
		assert.throws( function () {
			api.upload();
		} );
	} );

	QUnit.test( 'Set up iframe upload', function ( assert ) {
		var $iframe, $form, $input,
			api = new mw.Api();

		this.sandbox.stub( api, 'getEditToken', function () {
			return $.Deferred().promise();
		} );

		api.uploadWithIframe( $( '<input>' )[ 0 ], { filename: 'Testing API upload.jpg' } );

		$iframe = $( 'iframe' );
		$form = $( 'form.mw-api-upload-form' );
		$input = $form.find( 'input[name=filename]' );

		assert.ok( $form.length > 0 );
		assert.ok( $input.length > 0 );
		assert.ok( $iframe.length > 0 );
		assert.strictEqual( $form.prop( 'target' ), $iframe.prop( 'id' ) );
		assert.strictEqual( $input.val(), 'Testing API upload.jpg' );
	} );

}( mediaWiki, jQuery ) );
