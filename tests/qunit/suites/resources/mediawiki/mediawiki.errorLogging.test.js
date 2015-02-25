( function ( $, mw ) {
	function getWindowMock( sandbox ) {
		return {
			setTimeout: sandbox.stub().returnsArg( 0 ),
			setInterval: sandbox.stub().returnsArg( 0 )
		};
	}
	function getJqueryMock( sandbox ) {
		return {
			fn: {
				ready: sandbox.stub().returnsArg( 0 )
			},
			event: {
				add: sandbox.stub().returnsArg( 2 )
			},
			ajax: sandbox.stub().returnsArg( 1 ),
			each: $.proxy( $.each, $ )
		};
	}

	QUnit.module( 'mediawiki.errorLogging', QUnit.newMwEnvironment() );

	QUnit.test( 'wrap', 6, function ( assert ) {
		var wrapper, stub,
			exception = new Error(),
			context = {};

		assert.strictEqual( mw.errorLogging.wrap( context, '?' ), context, 'Wrapping a non-function is a noop.' );

		stub = this.sandbox.stub();
		wrapper = mw.errorLogging.wrap( stub, 'stub' );
		wrapper();
		sinon.assert.called( stub );

		stub = this.sandbox.stub();
		wrapper = mw.errorLogging.wrap( stub, 'stub' );
		wrapper.call( context, 'foo', 'bar' );
		sinon.assert.calledOn( stub, context );
		sinon.assert.calledWithExactly( stub, 'foo', 'bar' );

		stub = this.sandbox.stub().throws( exception );
		wrapper = mw.errorLogging.wrap( stub, 'name' );
		this.sandbox.stub( mw, 'track' );
		try {
			wrapper();
		} catch ( e ) {
			assert.strictEqual( e, exception, 'Exceptions are not swallowed and still appear on the console.' );
		}
		sinon.assert.calledWith( mw.track, 'errorLogging.exception', sinon.match( { exception: exception, source: 'name' } ) );

	} );

	QUnit.test( 'decorateWithArgsCallback', 5, function () {
		var decorated, stub, callback,
			context = {};

		stub = this.sandbox.stub();
		callback = this.sandbox.stub();
		decorated = mw.errorLogging.decorateWithArgsCallback( stub, callback );

		decorated.call( context, 'foo', 'bar' );
		sinon.assert.called( stub );
		sinon.assert.calledOn( stub, context );
		sinon.assert.calledWithExactly( stub, 'foo', 'bar' );
		sinon.assert.calledWithExactly( callback, ['foo', 'bar'] );

		stub = this.sandbox.stub();
		callback = function ( args ) {
			args.pop();
			args.push( 'baz' );
		};
		decorated = mw.errorLogging.decorateWithArgsCallback( stub, callback );

		decorated.call( context, 'foo', 'bar' );
		sinon.assert.calledWithExactly( stub, 'foo', 'baz' );
	} );

	QUnit.test( 'registerAsync', 4, function ( assert ) {
		var w = getWindowMock( this.sandbox ),
			jq = getJqueryMock( this.sandbox );

		mw.errorLogging.registerAsync( w, jq );

		// jscs:disable disallowDanglingUnderscores
		assert.ok( w.setTimeout( $.noop, 0 ).__inner__, 'setTimeout callback has been wrapped' );
		assert.ok( w.setInterval( $.noop, 0 ).__inner__, 'setInterval callback has been wrapped' );
		assert.ok( jq.fn.ready( $.noop ).__inner__, '$.ready callback has been wrapped' );
		assert.ok( jq.event.add( {}, 'click', $.noop ).__inner__, '$.on callback has been wrapped' );
		// jscs:enable disallowDanglingUnderscores
	} );

	QUnit.test( 'handleWindowOnerror', 1, function () {
		this.sandbox.stub( mw, 'track' );
		mw.errorLogging.handleWindowOnerror( 1, 2, 3 );
		sinon.assert.calledWithExactly( mw.track, 'errorLogging.windowOnerror',
			sinon.match( { args: [1, 2, 3] } ) );
	} );

	QUnit.test( 'registerOnerror', 10, function ( assert ) {
		var w = getWindowMock( this.sandbox),
			oldOnerror = this.sandbox.stub(),
			processedError = { mwErrorLoggingProcessed: true };

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

		mw.errorLogging.handleWindowOnerror.reset();
		mw.track.reset();
		oldOnerror.reset();
		w.onerror( 0, 1, 2, 3, processedError );
		assert.ok( !mw.track.called, 'mw.track not called when error already processed' );
		assert.ok( oldOnerror.calledWithExactly( 0, 1, 2, 3, processedError ),
			'Arguments are passed to previous handler' );

		mw.errorLogging.unregisterOnerror( w );

		mw.errorLogging.handleWindowOnerror.reset();
		oldOnerror.reset();
		w.onerror( 0, 1, 2 );
		assert.ok( !mw.errorLogging.handleWindowOnerror.called, 'Unregistering works' );
		assert.ok( oldOnerror.calledWithExactly( 0, 1, 2 ), 'Unregistering restores old handler' );
	} );

	QUnit.test( 'register', 4, function ( assert ) {
		var w = getWindowMock( this.sandbox ),
			jq = getJqueryMock( this.sandbox );

		this.sandbox.stub( Math, 'random').returns( 0.25 );
		this.sandbox.stub( mw.errorLogging, 'registerAsync' );
		this.sandbox.stub( mw.errorLogging, 'registerOnerror' );

		this.sandbox.stub( mw.config, 'get' ).withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( false );
		mw.errorLogging.register( w, jq );
		assert.ok( !mw.errorLogging.registerAsync.called, 'No registering when disabled' );

		mw.errorLogging.registerAsync.reset();
		mw.config.get.withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( true );
		mw.errorLogging.register( w, jq );
		assert.ok( mw.errorLogging.registerAsync.called, 'Registering when enabled' );

		mw.errorLogging.registerAsync.reset();
		mw.config.get.withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( 5 );
		mw.errorLogging.register( w, jq );
		assert.ok( !mw.errorLogging.registerAsync.called, 'This should not be in sample' );

		mw.errorLogging.registerAsync.reset();
		mw.config.get.withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( 2 );
		mw.errorLogging.register( w, jq );
		assert.ok( mw.errorLogging.registerAsync.called, 'This should be in sample' );
	} );

}( jQuery, mediaWiki ) );
