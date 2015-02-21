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

	QUnit.test( 'getId', 5, function ( assert ) {
		$.each( [null, {}, 1, 'foo', new Error()], function ( i, v ) {
			assert.ok( mw.errorLogging.getId( v ), 'Sanity check #' + i );
		} );
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
		sinon.assert.calledWith( mw.track, 'errorLogging.exception', sinon.match( { exception: exception, source: 'name', id: sinon.match.defined } ) );

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

	QUnit.test( 'register', 4, function ( assert ) {
		var w = getWindowMock( this.sandbox ),
			jq = getJqueryMock( this.sandbox );

		this.sandbox.stub( mw.config, 'get' ).withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( true );
		mw.errorLogging.register( w, jq );

		// jscs:disable disallowDanglingUnderscores
		assert.ok( w.setTimeout( $.noop, 0 ).__inner__, 'setTimeout callback has been wrapped' );
		assert.ok( w.setInterval( $.noop, 0 ).__inner__, 'setInterval callback has been wrapped' );
		assert.ok( jq.fn.ready( $.noop ).__inner__, '$.ready callback has been wrapped' );
		assert.ok( jq.event.add( {}, 'click', $.noop ).__inner__, '$.on callback has been wrapped' );
		// jscs:enable disallowDanglingUnderscores
	} );

	QUnit.test( 'wgRegisterJavascriptErrorLogging', 3, function ( assert ) {
		var w = getWindowMock( this.sandbox ),
			jq = getJqueryMock( this.sandbox );

		this.sandbox.stub( Math, 'random').returns( 0.25 );

		// jscs:disable disallowDanglingUnderscores
		this.sandbox.stub( mw.config, 'get' ).withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( false );
		mw.errorLogging.register( w, jq );
		assert.ok( !w.setTimeout( $.noop, 0 ).__inner__, 'No wrapping when disabled' );

		mw.config.get.withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( 5 );
		mw.errorLogging.register( w, jq );
		assert.ok( !w.setTimeout( $.noop, 0 ).__inner__, 'Sampling test 1' );

		mw.config.get.withArgs( 'wgRegisterJavascriptErrorLogging' ).returns( 2 );
		mw.errorLogging.register( w, jq );
		assert.ok( w.setTimeout( $.noop, 0 ).__inner__, 'Sampling test 2' );
		// jscs:enable disallowDanglingUnderscores
	} );

}( jQuery, mediaWiki ) );
