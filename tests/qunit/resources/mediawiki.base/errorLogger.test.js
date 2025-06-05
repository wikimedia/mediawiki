QUnit.module( 'mediawiki.base/errorLogger', () => {

	QUnit.test( 'installGlobalHandler', function ( assert ) {
		const errorMessage = 'Foo';
		const url = 'http://example.com';
		const lineNumber = '123';
		const columnNumber = '45';
		const errorObject = new Error( 'Foo' );

		const track = this.sandbox.stub( mw, 'track' );

		let w = {};
		mw.errorLogger.installGlobalHandler( w );

		assert.strictEqual( typeof w.onerror, 'function', 'install global handler' );
		assert.false(
			w.onerror(
				errorMessage,
				url,
				lineNumber,
				columnNumber,
				errorObject
			),
			'return value when there is no previous handler'
		);
		assert.deepEqual( track.getCall( 0 ).args, [
			'global.error',
			{
				errorMessage,
				url,
				lineNumber,
				columnNumber,
				errorObject,
				stackTrace: errorObject.stack
			}
		], 'global.errror call' );
		assert.deepEqual( track.getCall( 1 ).args, [ 'error.uncaught', errorObject ], 'error.uncaught call' );

		// ---

		const oldHandler = this.sandbox.stub();
		w = { onerror: oldHandler };
		mw.errorLogger.installGlobalHandler( w );
		w.onerror( errorMessage, url, lineNumber );

		assert.deepEqual(
			oldHandler.firstCall.args,
			[ errorMessage, url, lineNumber ],
			'oldHandler call'
		);

		oldHandler.returns( false );
		assert.false( w.onerror( errorMessage, url, lineNumber ), 'return value when previous handler returns false' );
		oldHandler.returns( true );
		assert.true( w.onerror( errorMessage, url, lineNumber ), 'return value when previous handler returns true' );
	} );

	QUnit.test( 'logError', function ( assert ) {
		const errorObject = new Error( 'Foo' );
		const track = this.sandbox.stub( mw, 'track' );

		mw.errorLogger.logError( errorObject );
		assert.deepEqual( track.getCall( 0 ).args, [ 'error.caught', errorObject ], 'track caught' );

		mw.errorLogger.logError( errorObject, 'error.vue' );
		assert.deepEqual( track.getCall( 1 ).args, [ 'error.vue', errorObject ], 'track vue' );
	} );
} );
