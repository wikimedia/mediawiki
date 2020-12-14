( function () {
	function CustomError( message, stack ) {
		this.name = 'CustomError';
		this.message = message;
		this.stack = stack;
	}

	CustomError.prototype = Error.prototype;

	// ---

	QUnit.module( 'mediawiki.errorLogger', QUnit.newMwEnvironment() );

	QUnit.test( 'installGlobalHandler', function ( assert ) {
		var w = {},
			errorMessage = 'Foo',
			errorUrl = 'http://example.com',
			errorLine = '123',
			errorColumn = '45',
			errorObject = new Error( 'Foo' ),
			errorStackTrace = mw.errorLogger.crossBrowserStackTrace( errorObject.stack || '' ),
			oldHandler = this.sandbox.stub();

		this.sandbox.stub( mw, 'track' );

		mw.errorLogger.installGlobalHandler( w );

		assert.ok( w.onerror, 'Global handler has been installed' );
		assert.strictEqual( w.onerror( errorMessage, errorUrl, errorLine ), false,
			'Global handler returns false when there is no previous handler' );
		sinon.assert.calledWithExactly( mw.track, 'global.error',
			sinon.match( { errorMessage: errorMessage, url: errorUrl, lineNumber: errorLine } ) );

		mw.track.reset();
		w.onerror( errorMessage, errorUrl, errorLine, errorColumn, errorObject );
		sinon.assert.calledWithExactly( mw.track, 'global.error',
			sinon.match( { errorMessage: errorMessage, url: errorUrl, lineNumber: errorLine,
				columnNumber: errorColumn, stackTrace: errorStackTrace, errorObject: errorObject } ) );

		w = { onerror: oldHandler };

		mw.errorLogger.installGlobalHandler( w );
		w.onerror( errorMessage, errorUrl, errorLine );
		sinon.assert.calledWithExactly( oldHandler, errorMessage, errorUrl, errorLine );

		oldHandler.returns( false );
		assert.strictEqual( w.onerror( errorMessage, errorUrl, errorLine ), false,
			'Global handler preserves false return from previous handler' );
		oldHandler.returns( true );
		assert.strictEqual( w.onerror( errorMessage, errorUrl, errorLine ), true,
			'Global handler preserves true return from previous handler' );
	} );

	QUnit.test( 'logError', function ( assert ) {
		var error = new CustomError(
				'Foo',
				'at foo (http://localhost:8080/w/resources/src/mediawiki.base/mediawiki.errorLogger.js:123:8)'
			),
			expectedStackTrace = mw.errorLogger.crossBrowserStackTrace( error.stack );

		this.sandbox.stub( mw, 'track' );

		[ null, true, 'foo', 1 ].forEach( function ( primitiveValue ) {
			mw.errorLogger.logError( primitiveValue );

			assert.ok( mw.track.notCalled, "A global.error event isn't tracked for primitive values ('" + primitiveValue + "')" );
		} );

		// ---

		[ null, '', 'at 0123456789' ].forEach( function ( stack ) {
			mw.errorLogger.logError( new CustomError( 'Foo', stack ) );

			assert.ok( mw.track.notCalled, "A global.error event isn't tracked for empty/unparsable stack traces ('" + stack + "')" );
		} );

		// ---

		mw.errorLogger.logError( error );

		sinon.assert.calledWithExactly(
			mw.track,
			'global.error',
			{
				errorMessage: 'Foo',
				url: 'http://localhost:8080/w/resources/src/mediawiki.base/mediawiki.errorLogger.js',
				lineNumber: 8,
				columnNumber: 123,
				stackTrace: expectedStackTrace,
				errorObject: error
			}
		);
	} );
}() );
