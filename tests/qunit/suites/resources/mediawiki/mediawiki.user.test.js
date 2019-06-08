( function () {
	QUnit.module( 'mediawiki.user', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
			// Cannot stub by simple assignment because read-only.
			// Instead, stub in tests by using 'delete', and re-create
			// in teardown using the original descriptor (including its
			// accessors and readonly settings etc.)
			this.crypto = Object.getOwnPropertyDescriptor( window, 'crypto' );
			this.msCrypto = Object.getOwnPropertyDescriptor( window, 'msCrypto' );
		},
		teardown: function () {
			if ( this.crypto ) {
				Object.defineProperty( window, 'crypto', this.crypto );
			}
			if ( this.msCrypto ) {
				Object.defineProperty( window, 'msCrypto', this.msCrypto );
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

		assert.strictEqual( mw.user.getName(), 'John', 'getName()' );
		assert.strictEqual( mw.user.isAnon(), false, 'isAnon()' );
		assert.strictEqual( mw.user.getId(), 123, 'getId()' );

		assert.strictEqual( mw.user.id(), 'John', 'user.id()' );
	} );

	QUnit.test( 'getGroups (callback)', function ( assert ) {
		var done = assert.async();
		mw.config.set( 'wgUserGroups', [ '*', 'user' ] );

		mw.user.getGroups( function ( groups ) {
			assert.deepEqual( groups, [ '*', 'user' ], 'Result' );
			done();
		} );
	} );

	QUnit.test( 'getGroups (Promise)', function ( assert ) {
		mw.config.set( 'wgUserGroups', [ '*', 'user' ] );

		return mw.user.getGroups().then( function ( groups ) {
			assert.deepEqual( groups, [ '*', 'user' ], 'Result' );
		} );
	} );

	QUnit.test( 'getRights (callback)', function ( assert ) {
		var done = assert.async();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "userinfo": { "groups": [ "unused" ], "rights": [ "read", "edit", "createtalk" ] } } }'
		] );

		mw.user.getRights( function ( rights ) {
			assert.deepEqual( rights, [ 'read', 'edit', 'createtalk' ], 'Result (callback)' );
			done();
		} );
	} );

	QUnit.test( 'getRights (Promise)', function ( assert ) {
		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "userinfo": { "groups": [ "unused" ], "rights": [ "read", "edit", "createtalk" ] } } }'
		] );

		return mw.user.getRights().then( function ( rights ) {
			assert.deepEqual( rights, [ 'read', 'edit', 'createtalk' ], 'Result (promise)' );
		} );
	} );

	QUnit.test( 'generateRandomSessionId', function ( assert ) {
		var result, result2;

		result = mw.user.generateRandomSessionId();
		assert.strictEqual( typeof result, 'string', 'type' );
		assert.strictEqual( result.trim(), result, 'no whitespace at beginning or end' );
		assert.strictEqual( result.length, 20, 'size' );

		result2 = mw.user.generateRandomSessionId();
		assert.notEqual( result, result2, 'different when called multiple times' );

	} );

	QUnit.test( 'generateRandomSessionId (fallback)', function ( assert ) {
		var result, result2;

		// Pretend crypto API is not there to test the Math.random fallback
		delete window.crypto;
		delete window.msCrypto;
		// Assert that the above actually worked. If we use the wrong method
		// of stubbing, JavaScript silently continues and we need to know that
		// it was the wrong method. As of writing, assigning undefined is
		// ineffective as the window property for Crypto is read-only.
		// However, deleting does work. (T203275)
		assert.strictEqual( window.crypto || window.msCrypto, undefined, 'fallback is active' );

		result = mw.user.generateRandomSessionId();
		assert.strictEqual( typeof result, 'string', 'type' );
		assert.strictEqual( result.trim(), result, 'no whitespace at beginning or end' );
		assert.strictEqual( result.length, 20, 'size' );

		result2 = mw.user.generateRandomSessionId();
		assert.notEqual( result, result2, 'different when called multiple times' );
	} );

	QUnit.test( 'getPageviewToken', function ( assert ) {
		var result = mw.user.getPageviewToken(),
			result2 = mw.user.getPageviewToken();
		assert.strictEqual( typeof result, 'string', 'type' );
		assert.strictEqual( /^[a-f0-9]{20}$/.test( result ), true, '20 HEX symbols string' );
		assert.strictEqual( result2, result, 'sticky' );
	} );

	QUnit.test( 'sessionId', function ( assert ) {
		var result = mw.user.sessionId(),
			result2 = mw.user.sessionId();
		assert.strictEqual( typeof result, 'string', 'type' );
		assert.strictEqual( result.trim(), result, 'no leading or trailing whitespace' );
		assert.strictEqual( result2, result, 'retained' );
	} );
}() );
