( function () {
	var Router = require( 'oojs-router' ),
		router;

	QUnit.module( 'Router', {
		setup: function () {
			router = new Router();
			this.stub( router, 'getPath', function () {
				return router.testHash.slice( 1 );
			} );
			this.stub( router, 'navigate', function ( path ) {
				router.testHash = path;
			} );
		},

		teardown: function () {
			router.testHash = '';
		}
	} );

	QUnit.test( '#route, string', 1, function ( assert ) {
		router.testHash = '';
		router.route( 'teststring', function () {
			assert.ok( true, 'run callback for route' );
		} );
		router.testHash = '#teststring';
		router.emit( 'hashchange' );
	} );

	QUnit.test( '#route, RegExp', 1, function ( assert ) {
		router.testHash = '';
		router.route( /^testre-(\d+)$/, function ( param ) {
			assert.strictEqual( param, '123', 'run callback for route with correct params' );
		} );
		router.testHash = '#testre-abc';
		router.emit( 'hashchange' );
		router.testHash = '#testre-123';
		router.emit( 'hashchange' );
	} );

	QUnit.test( 'on route', 2, function ( assert ) {
		var count = 0,
			spy = this.sandbox.spy();

		router.testHash = '';
		router.route( 'testprevent', spy );

		// try preventing second route (#testprevent)
		router.once( 'route', function () {
			router.testHash = '#testprevent';
			router.once( 'route', function ( ev ) {
				ev.preventDefault();
			} );
		} );
		router.testHash = '#initial';

		router.on( 'hashchange.test', function () {
			++count;
			if ( count === 3 ) {
				assert.strictEqual( router.testHash, '#initial', 'reset hash' );
				assert.ok( !spy.called, 'don\'t run callback for prevented route' );
			}
		} );
		// emit a hashchange thrice to check if the hash has changed or not
		router.emit( 'hashchange.test' );
		router.emit( 'hashchange.test' );
		router.emit( 'hashchange.test' );
	} );

	QUnit.test( 'on back', 2, function ( assert ) {
		this.sandbox.stub( router, 'goBack' );
		router.back().done( function () {
			assert.ok( true, 'back 1 complete' );
		} );
		router.back().done( function () {
			assert.ok( true, 'back 2 complete' );
		} );
		router.emit( 'popstate' );
	} );

	QUnit.test( 'on back without popstate', 2, function ( assert ) {
		var historyStub = this.sandbox.stub( router, 'goBack' ), // do not emit popstate
			done = assert.async();
		router.on( 'popstate', function () {
			assert.ok( false, 'this assertion is not supposed to get called' );
		} );

		router.back().done( function () {
			assert.ok( historyStub.called, 'history back has been called' );
			assert.ok( true, 'back without popstate complete' );
			done();
		} );
	} );

}() );
