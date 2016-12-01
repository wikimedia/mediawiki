( function ( mw ) {
	QUnit.module( 'mediawiki.storage' );

	QUnit.test( 'set/get with storage support', function ( assert ) {
		var stub = {
			setItem: this.sandbox.spy(),
			getItem: this.sandbox.stub()
		};
		stub.getItem.withArgs( 'foo' ).returns( 'test' );
		stub.getItem.returns( null );
		this.sandbox.stub( mw.storage, 'store', stub );

		mw.storage.set( 'foo', 'test' );
		assert.ok( stub.setItem.calledOnce );

		assert.strictEqual( mw.storage.get( 'foo' ), 'test', 'Check value gets stored.' );
		assert.strictEqual( mw.storage.get( 'bar' ), null, 'Unset values are null.' );
	} );

	QUnit.test( 'set/get with storage methods disabled', function ( assert ) {
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
		assert.strictEqual( mw.storage.remove( 'foo', 'test' ), false );
	} );

	QUnit.test( 'set/get with storage object disabled', function ( assert ) {
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

		mw.storage.store = old;
	} );

}( mediaWiki ) );
