( function ( $, mw ) {
	QUnit.module( 'mediawiki.errorLogging', QUnit.newMwEnvironment( {
		config: {
			wgJavascriptErrorLoggingSamplingRate: 1
		}
	} ) );

	QUnit.test( 'handleWindowOnerror', 1, function () {
		this.sandbox.stub( mw, 'track' );
		mw.errorLogging.handleWindowOnerror( 1, 2, 3 );
		sinon.assert.calledWithExactly( mw.track, 'errorLogging.windowOnerror',
			sinon.match( { args: [1, 2, 3] } ) );
	} );

	QUnit.test( 'registerOnerror', 8, function ( assert ) {
		var w = {},
			oldOnerror = this.sandbox.stub();

		this.sandbox.stub( mw, 'track' );
		this.sandbox.spy( mw.errorLogging, 'handleWindowOnerror' );
		mw.errorLogging.registerOnerror( w );

		w.onerror( 0, 1, 2 );
		assert.ok( mw.errorLogging.handleWindowOnerror.calledWithExactly( 0, 1, 2 ),
			'Arguments are passed to handler' );

		mw.errorLogging.unregisterOnerror( w );
		w.onerror = oldOnerror;
		mw.errorLogging.registerOnerror( w );

		mw.errorLogging.handleWindowOnerror.reset();
		w.onerror( 0, 1, 2 );
		assert.ok( mw.errorLogging.handleWindowOnerror.calledWithExactly( 0, 1, 2 ),
			'Arguments are passed to handler' );
		assert.ok( mw.track.called, 'Arguments passed to mw.track' );
		assert.ok( oldOnerror.calledWithExactly( 0, 1, 2 ), 'Arguments are passed to previous handler' );

		mw.errorLogging.handleWindowOnerror.reset();
		oldOnerror.reset();
		w.onerror( 0, 1, 2, 3, 4 );
		assert.ok( mw.errorLogging.handleWindowOnerror.calledWithExactly( 0, 1, 2, 3, 4 ),
			'Arguments are passed to handler' );
		assert.ok( oldOnerror.calledWithExactly( 0, 1, 2, 3, 4 ), 'Arguments are passed to previous handler' );

		mw.errorLogging.unregisterOnerror( w );

		mw.errorLogging.handleWindowOnerror.reset();
		oldOnerror.reset();
		w.onerror( 0, 1, 2 );
		assert.ok( !mw.errorLogging.handleWindowOnerror.called, 'Unregistering works' );
		assert.ok( oldOnerror.calledWithExactly( 0, 1, 2 ), 'Unregistering restores old handler' );
	} );

	QUnit.test( 'register', 4, function ( assert ) {
		var w = {};

		this.sandbox.stub( Math, 'random').returns( 0.25 );
		this.sandbox.stub( mw.errorLogging, 'registerOnerror' );

		this.sandbox.stub( mw.config, 'get' ).withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 0 );
		mw.errorLogging.register( w );
		assert.ok( !mw.errorLogging.registerOnerror.called, 'No registering when disabled' );

		mw.errorLogging.registerOnerror.reset();
		mw.config.get.withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 1 );
		mw.errorLogging.register( w );
		assert.ok( mw.errorLogging.registerOnerror.called, 'Registering when enabled' );

		mw.errorLogging.registerOnerror.reset();
		mw.config.get.withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 5 );
		mw.errorLogging.register( w );
		assert.ok( !mw.errorLogging.registerOnerror.called, 'This should not be in sample' );

		mw.errorLogging.registerOnerror.reset();
		mw.config.get.withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 2 );
		mw.errorLogging.register( w );
		assert.ok( mw.errorLogging.registerOnerror.called, 'This should be in sample' );
	} );

}( jQuery, mediaWiki ) );
