( function ( $, mw ) {
	QUnit.module( 'mediawiki.errorLogging', QUnit.newMwEnvironment() );

	QUnit.test( 'getId', 1, function ( assert ) {
		assert.ok( mw.errorLogging.getId(), 'Sanity check' );
	} );

	QUnit.test( 'logError', 2, function () {
		var error = new Error(),
			context = {};

		this.sandbox.stub( mw, 'track' );
		mw.errorLogging.logError( error );
		sinon.assert.calledWith( mw.track, 'errorLogging.exception',
			sinon.match( { exception: error, id: sinon.match.defined } ) );

		mw.track.reset();
		mw.errorLogging.logError( error, context );
		sinon.assert.calledWith( mw.track, 'errorLogging.exception',
			sinon.match( { exception: error, id: sinon.match.defined, context: context } ) );

	} );

	QUnit.test( 'installGlobalHandler', 7, function ( assert ) {
		var w = {},
			errorMessage = 'Foo',
			errorUrl = 'http://example.com',
			errorLine = '123',
			errorColumn = '45',
			errorObject = new Error( 'Foo'),
			oldHandler = this.sandbox.stub();

		this.sandbox.stub( mw, 'track' );

		mw.errorLogging.installGlobalHandler( w );

		assert.ok( w.onerror, 'Global handler has been installed' );
		assert.strictEqual( w.onerror( errorMessage, errorUrl, errorLine ), false,
			'Global handler returns false when there is no previous handler' );
		sinon.assert.calledWithExactly( mw.track, 'errorLogging.windowOnerror',
			sinon.match( { errorMessage: errorMessage, url: errorUrl, lineNumber: errorLine,
				id: sinon.match.defined } ) );

		mw.track.reset();
		w.onerror( errorMessage, errorUrl, errorLine, errorColumn, errorObject );
		sinon.assert.calledWithExactly( mw.track, 'errorLogging.windowOnerror',
			sinon.match( { errorMessage: errorMessage, url: errorUrl, lineNumber: errorLine,
			columnNumber: errorColumn, errorObject: errorObject, id: sinon.match.defined } ) );

		w = { onerror: oldHandler };

		mw.errorLogging.installGlobalHandler( w );
		w.onerror( errorMessage, errorUrl, errorLine );
		sinon.assert.calledWithExactly( oldHandler, errorMessage, errorUrl, errorLine );

		oldHandler.returns( false );
		assert.strictEqual( w.onerror( errorMessage, errorUrl, errorLine ), false,
			'Global handler preserves false return from previous handler' );
		oldHandler.returns( true );
		assert.strictEqual( w.onerror( errorMessage, errorUrl, errorLine ), true,
			'Global handler preserves true return from previous handler' );
	} );
}( jQuery, mediaWiki ) );
