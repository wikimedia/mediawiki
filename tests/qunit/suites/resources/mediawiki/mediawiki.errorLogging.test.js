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

	QUnit.test( 'register', 4, function ( assert ) {
		var w = {};

		this.sandbox.stub( Math, 'random').returns( 0.25 );

		this.sandbox.stub( mw.config, 'get' ).withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 0 );
		mw.errorLogging.register( w );
		assert.ok( !w.onerror, 'No registering when disabled' );

		w.onerror = undefined;
		mw.config.get.withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 1 );
		mw.errorLogging.register( w );
		assert.ok( w.onerror, 'Registering when enabled' );

		w.onerror = undefined;
		mw.config.get.withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 5 );
		mw.errorLogging.register( w );
		assert.ok( !w.onerror, 'This should not be in sample' );

		w.onerror = undefined;
		mw.config.get.withArgs( 'wgJavascriptErrorLoggingSamplingRate' ).returns( 2 );
		mw.errorLogging.register( w );
		assert.ok( w.onerror, 'This should be in sample' );
	} );

}( jQuery, mediaWiki ) );
