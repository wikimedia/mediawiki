QUnit.module( 'mediawiki.messagePoster', ( hooks ) => {
	const TEST_MODEL = 'test-content-model';

	hooks.afterEach( () => {
		mw.messagePoster.factory.unregister( TEST_MODEL );
	} );

	QUnit.test( 'register', ( assert ) => {
		function MessagePosterConstructor() {}

		mw.messagePoster.factory.register( TEST_MODEL, MessagePosterConstructor );
		assert.strictEqual(
			mw.messagePoster.factory.contentModelToClass[ TEST_MODEL ],
			MessagePosterConstructor,
			'Constructor is registered'
		);

		assert.throws(
			() => {
				mw.messagePoster.factory.register( TEST_MODEL, MessagePosterConstructor );
			},
			new RegExp( 'Content model "' + TEST_MODEL + '" is already registered' ),
			'Throws exception is same model is registered a second time'
		);
	} );
} );
