( function ( $, mw ) {
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

	QUnit.test( 'register', 5, function ( assert ) {
		var w, jq;

		w = {
			setTimeout: this.sandbox.stub().returnsArg( 0 ),
			setInterval: this.sandbox.stub().returnsArg( 0 )
		};
		jq = {
			fn: {
				ready: this.sandbox.stub().returnsArg( 0 )
			},
			event: {
				add: this.sandbox.stub().returnsArg( 2 )
			},
			ajax: this.sandbox.stub().returnsArg( 1 ),
			each: $.proxy( $.each, $ )
		};

		mw.errorLogging.register( w, jq );

		// jscs:disable disallowDanglingUnderscores
		assert.ok( w.setTimeout( $.noop, 0 ).__inner__, 'setTimeout callback has been wrapped' );
		assert.ok( w.setInterval( $.noop, 0 ).__inner__, 'setInterval callback has been wrapped' );
		assert.ok( jq.fn.ready( $.noop ).__inner__, '$.ready callback has been wrapped' );
		assert.ok( jq.event.add( {}, 'click', $.noop ).__inner__, '$.on callback has been wrapped' );
		assert.ok( jq.ajax( 'foo://', { success: $.noop } ).success.__inner__, 'setTimeout has been wrapped' );
		// jscs:enable disallowDanglingUnderscores
	} );
}( jQuery, mediaWiki ) );
