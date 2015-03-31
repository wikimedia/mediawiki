( function ( mw ) {
	var TEST_MODEL = 'test-content-model';

	QUnit.module( 'mediawiki.messagePoster', QUnit.newMwEnvironment( {
		teardown: function () {
			mw.messagePoster.factory.unregister( TEST_MODEL );
		}
	} ) );

	QUnit.test( 'register', 2, function ( assert ) {
		var testMessagePosterConstructor = function () {};

		mw.messagePoster.factory.register( TEST_MODEL, testMessagePosterConstructor );
		assert.strictEqual(
			mw.messagePoster.factory.contentModelToClass[TEST_MODEL],
			testMessagePosterConstructor,
			'Constructor is registered'
		);

		assert.throws(
			function () {
				mw.messagePoster.factory.register( TEST_MODEL, testMessagePosterConstructor );
			},
			new RegExp( 'The content model \'' + TEST_MODEL + '\' is already registered.' ),
			'Throws exception is same model is registered a second time'
		);
	} );
}( mediaWiki ) );
