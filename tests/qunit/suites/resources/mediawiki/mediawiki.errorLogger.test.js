( function () {
	QUnit.module( 'mediawiki.errorLogger', QUnit.newMwEnvironment() );

	QUnit.test( 'installGlobalHandler', function ( assert ) {
		var w = {},
			errorMessage = 'Foo',
			errorUrl = 'http://example.com',
			errorLine = '123',
			errorColumn = '45',
			errorObject = new Error( 'Foo' ),
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
				columnNumber: errorColumn, errorObject: errorObject } ) );

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
}() );
