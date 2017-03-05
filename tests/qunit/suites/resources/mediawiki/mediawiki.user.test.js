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

	QUnit.test( 'options', function ( assert ) {
		assert.ok( mw.user.options instanceof mw.Map, 'options instance of mw.Map' );
	} );

	QUnit.test( 'getters (anonymous)', function ( assert ) {
		// Forge an anonymous user
		mw.config.set( 'wgUserName', null );
		mw.config.set( 'wgUserId', null );

		assert.strictEqual( mw.user.getName(), null, 'getName()' );
		assert.strictEqual( mw.user.isAnon(), true, 'isAnon()' );
		assert.strictEqual( mw.user.getId(), 0, 'getId()' );
	} );

	QUnit.test( 'getters (logged-in)', function ( assert ) {
		mw.config.set( 'wgUserName', 'John' );
		mw.config.set( 'wgUserId', 123 );

		assert.equal( mw.user.getName(), 'John', 'getName()' );
		assert.strictEqual( mw.user.isAnon(), false, 'isAnon()' );
		assert.strictEqual( mw.user.getId(), 123, 'getId()' );

		assert.equal( mw.user.id(), 'John', 'user.id()' );
	} );

	QUnit.test( 'getUserInfo', function ( assert ) {
		mw.config.set( 'wgUserGroups', [ '*', 'user' ] );

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
				'{ "query": { "userinfo": { "groups": [ "unused" ], "rights": [ "read", "edit", "createtalk" ] } } }'
			);
		} );

		this.server.respond();
	} );

	QUnit.test( 'generateRandomSessionId', function ( assert ) {
		var result, result2;

		result = mw.user.generateRandomSessionId();
		assert.equal( typeof result, 'string', 'type' );
		assert.equal( $.trim( result ), result, 'no whitespace at beginning or end' );
		assert.equal( result.length, 16, 'size' );

		result2 = mw.user.generateRandomSessionId();
		assert.notEqual( result, result2, 'different when called multiple times' );

	} );

	QUnit.test( 'generateRandomSessionId (fallback)', function ( assert ) {
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

	QUnit.test( 'sessionId', function ( assert ) {
		var result = mw.user.sessionId(),
			result2 = mw.user.sessionId();
		assert.equal( typeof result, 'string', 'type' );
		assert.equal( $.trim( result ), result, 'no leading or trailing whitespace' );
		assert.equal( result2, result, 'retained' );
	} );
}( mediaWiki, jQuery ) );
