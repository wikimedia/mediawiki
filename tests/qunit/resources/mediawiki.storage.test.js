( function () {
	QUnit.module( 'mediawiki.storage' );

	QUnit.test( 'set/get(Object) with storage support', function ( assert ) {
		var data = {},
			done = assert.async(),
			EXPIRY_PREFIX = '_EXPIRY_',
			object = { test: 'value' },
			stub = {
				setItem: function ( k, v ) {
					data[ k ] = v;
					return true;
				},
				getItem: function ( k ) {
					return Object.prototype.hasOwnProperty.call( data, k ) ? data[ k ] : null;
				},
				removeItem: function ( k ) {
					delete data[ k ];
					return true;
				},
				key: function ( i ) {
					return Object.keys( data )[ i ];
				}
			};

		Object.defineProperty( stub, 'length', {
			get: function () {
				return Object.keys( data ).length;
			}
		} );

		this.sandbox.stub( mw.storage, 'store', stub );

		assert.strictEqual( mw.storage.set( 'foo', 'test' ), true, 'set returns true' );
		assert.strictEqual( mw.storage.get( 'foo' ), 'test', 'Check value gets stored' );
		assert.strictEqual( mw.storage.get( 'bar' ), null, 'Unset values are null' );
		assert.strictEqual( mw.storage.remove( 'foo' ), true, 'remove returns true' );
		assert.strictEqual( mw.storage.get( 'foo' ), null, 'Removed item is null' );

		assert.strictEqual( mw.storage.setObject( 'baz', object ), true, 'setObject returns true' );
		assert.deepEqual( mw.storage.getObject( 'baz' ), object, 'Check value gets stored' );
		assert.notStrictEqual( mw.storage.getObject( 'baz' ), object, 'Retrieved value is a new object' );
		assert.strictEqual( mw.storage.getObject( 'quux' ), null, 'Unset values are null' );
		assert.strictEqual( mw.storage.remove( 'baz' ), true, 'remove returns true' );
		assert.strictEqual( mw.storage.getObject( 'baz' ), null, 'Removed item is null' );

		mw.storage.set( 'baz', 'Non-JSON' );
		assert.strictEqual( mw.storage.getObject( 'baz' ), null, 'Non-JSON values are null' );

		var now = Math.floor( Date.now() / 1000 );
		mw.storage.set( 'foo', 'test', 60 * 60 );
		assert.true( mw.storage.get( EXPIRY_PREFIX + 'foo' ) > now, 'Future expiry time stored' );
		assert.strictEqual( mw.storage.get( 'foo' ), 'test', 'Non-expired item fetched from store' );

		mw.storage.setObject( 'foo', 'test', 60 * 60 );
		assert.true( mw.storage.get( EXPIRY_PREFIX + 'foo' ) > now, 'Future expiry time stored (object)' );
		assert.strictEqual( mw.storage.getObject( 'foo' ), 'test', 'Non-expired item fetched from store (object)' );

		mw.storage.setObject( 'foo', 'test', -60 );
		assert.strictEqual( mw.storage.get( 'foo' ), null, 'Expired item returns null' );
		assert.strictEqual( data.foo, '"test"', 'Expired item exists in storage' );

		mw.storage.set( 'baz', 'test' );
		assert.strictEqual( mw.storage.get( EXPIRY_PREFIX + 'baz' ), null, 'Item with no expiry has no expiry item' );

		mw.storage.set( 'foo', 'test', 60 * 60 );
		mw.storage.remove( 'foo' );
		assert.strictEqual( mw.storage.get( EXPIRY_PREFIX + 'baz' ), null, 'Removed item has no expiry' );

		assert.throws( function () {
			mw.storage.set( EXPIRY_PREFIX + 'foo', 'test', 60 * 60 );
		}, 'Error thrown when key prefix conflicts with EXPIRY_PREFIX' );

		mw.storage.clearExpired().then( function () {
			assert.deepEqual( Object.keys( data ), [ 'baz' ], 'Only unexpired keys present after #clearExpired' );
			done();
		} );
	} );

	QUnit.test( 'set/get(Object) with storage methods disabled', function ( assert ) {
		// This covers browsers where storage is disabled
		// (quota full, or security/privacy settings).
		// On most browsers, these interface will be accessible with
		// their methods throwing.
		var stub = {
			getItem: this.sandbox.stub(),
			removeItem: this.sandbox.stub(),
			setItem: this.sandbox.stub()
		};
		stub.getItem.throws();
		stub.setItem.throws();
		stub.removeItem.throws();
		this.sandbox.stub( mw.storage, 'store', stub );

		assert.strictEqual( mw.storage.get( 'foo' ), false );
		assert.strictEqual( mw.storage.set( 'foo', 'test' ), false );
		assert.strictEqual( mw.storage.remove( 'foo' ), false );

		assert.strictEqual( mw.storage.getObject( 'bar' ), false );
		assert.strictEqual( mw.storage.setObject( 'bar', { test: 'value' } ), false );
		assert.strictEqual( mw.storage.remove( 'bar' ), false );
	} );

	QUnit.test( 'set/get(Object) with storage object disabled', function ( assert ) {
		// On other browsers, these entire object is disabled.
		// `'localStorage' in window` would be true (and pass feature test)
		// but trying to read the object as window.localStorage would throw
		// an exception. Such case would instantiate SafeStorage with
		// undefined after the internal try/catch.
		var old = mw.storage.store;
		mw.storage.store = undefined;

		assert.strictEqual( mw.storage.get( 'foo' ), false );
		assert.strictEqual( mw.storage.set( 'foo', 'test' ), false );
		assert.strictEqual( mw.storage.remove( 'foo', 'test' ), false );

		assert.strictEqual( mw.storage.getObject( 'bar' ), false );
		assert.strictEqual( mw.storage.setObject( 'bar', { test: 'value' } ), false );
		assert.strictEqual( mw.storage.remove( 'bar' ), false );

		mw.storage.store = old;
	} );

}() );
