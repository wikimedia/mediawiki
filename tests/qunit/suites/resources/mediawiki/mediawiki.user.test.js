( function ( mw ) {
	QUnit.module( 'mediawiki.user', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( 'options', 1, function ( assert ) {
		assert.ok( mw.user.options instanceof mw.Map, 'options instance of mw.Map' );
	} );

	QUnit.test( 'user status', 7, function ( assert ) {

		// Forge an anonymous user
		mw.config.set( 'wgUserName', null );
		delete mw.config.values.wgUserId;

		assert.strictEqual( mw.user.getName(), null, 'user.getName() returns null when anonymous' );
		assert.assertTrue( mw.user.isAnon(), 'user.isAnon() returns true when anonymous' );
		assert.strictEqual( mw.user.getId(), 0, 'user.getId() returns 0 when anonymous' );

		// Not part of startUp module
		mw.config.set( 'wgUserName', 'John' );
		mw.config.set( 'wgUserId', 123 );

		assert.equal( mw.user.getName(), 'John', 'user.getName() returns username when logged-in' );
		assert.assertFalse( mw.user.isAnon(), 'user.isAnon() returns false when logged-in' );
		assert.strictEqual( mw.user.getId(), 123, 'user.getId() returns correct ID when logged-in' );

		assert.equal( mw.user.id(), 'John', 'user.id Returns username when logged-in' );
	} );

	QUnit.test( 'getUserInfos', 3, function ( assert ) {
		mw.user.getGroups( function ( groups ) {
			assert.deepEqual( groups, [ '*', 'user' ], 'Result' );
		} );

		mw.user.getRights( function ( rights ) {
			assert.deepEqual( rights, [ 'read', 'edit', 'createtalk' ], 'Result (callback)' );
		} );

		mw.user.getRights().done( function ( rights ) {
			assert.deepEqual( rights, [ 'read', 'edit', 'createtalk' ], 'Result (promise)' );
		} );

		this.server.respondWith( /meta=userinfo/, function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' },
				'{ "query": { "userinfo": { "groups": [ "*", "user" ], "rights": [ "read", "edit", "createtalk" ] } } }'
			);
		} );

		this.server.respond();
	} );

	QUnit.test( 'session numbers', 4, function ( assert ) {
		/*global $:false */
		var sessionId = mw.user.generateRandomSessionId(),
		cryptoObj = window.crypto || window.msCrypto;

		assert.equal( typeof sessionId, 'string', 'generateRandomSessionId should return a string' );
		assert.equal( $.trim(sessionId), sessionId, 'generateRandomSessionId should not contain whitespace' );
		// pretend crypto API is not there and do same test, make sure code runs
		// through  Math.random loop
		window.crypto = undefined;
		sessionId =  mw.user.generateRandomSessionId();
		assert.equal( typeof sessionId, 'string', 'generateRandomSessionId should return a string' );
		assert.equal( sessionId.trim(), sessionId, 'generateRandomSessionId should not be empty' );
		//restoring crypto object
		window.crypto = cryptoObj;

	} );
}( mediaWiki ) );
