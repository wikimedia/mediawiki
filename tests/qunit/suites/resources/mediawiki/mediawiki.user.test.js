( function ( mw, $ ) {
	QUnit.module( 'mediawiki.user', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.crypto = window.crypto;
			this.msCrypto = window.msCrypto;
		},
		teardown: function () {
			if ( this.crypto ) {
				window.crypto = this.crypto;
			}
			if ( this.msCrypto ) {
				window.msCrypto = this.msCrypto;
			}
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

	QUnit.test( 'generateRandomSessionId', 4, function ( assert ) {
		var result, result2;

		result = mw.user.generateRandomSessionId();
		assert.equal( typeof result, 'string', 'type' );
		assert.equal( $.trim( result ), result, 'no whitespace at beginning or end' );
		assert.equal( result.length, 16, 'size' );

		result2 = mw.user.generateRandomSessionId();
		assert.notEqual( result, result2, 'different when called multiple times' );

	} );

	QUnit.test( 'generateRandomSessionId (fallback)', 4, function ( assert ) {
		var result, result2;

		// Pretend crypto API is not there to test the Math.random fallback
		if ( window.crypto ) {
			window.crypto = undefined;
		}
		if ( window.msCrypto ) {
			window.msCrypto = undefined;
		}

		result = mw.user.generateRandomSessionId();
		assert.equal( typeof result, 'string', 'type' );
		assert.equal( $.trim( result ), result, 'no whitespace at beginning or end' );
		assert.equal( result.length, 16, 'size' );

		result2 = mw.user.generateRandomSessionId();
		assert.notEqual( result, result2, 'different when called multiple times' );

	} );
}( mediaWiki, jQuery ) );
