( function ( mw ) {
	QUnit.module( 'mediawiki.storage' );

	QUnit.test( 'set/get with browser support', 3, function ( assert ) {
		this.sandbox.stub( mw.storage, 'store', {
			setItem: this.sandbox.spy(),
			getItem: this.sandbox.stub()
		} );

		mw.storage.set( 'foo', 'test' );
		assert.ok( mw.storage.store.setItem.calledOnce );

		mw.storage.store.getItem.withArgs( 'foo' ).returns( 'test' );
		mw.storage.store.getItem.returns( null );
		assert.strictEqual( mw.storage.get( 'foo' ), 'test', 'Check value gets stored.' );
		assert.strictEqual( mw.storage.get( 'bar' ), null, 'Unset values are null.' );
	} );

	QUnit.test( 'set/get without browser support', 3, function ( assert ) {
		this.sandbox.stub( mw.storage, 'store', {
			getItem: this.sandbox.stub(),
			removeItem: this.sandbox.stub(),
			setItem: this.sandbox.stub()
		} );

		mw.storage.store.getItem.throws();
		assert.strictEqual( mw.storage.get( 'foo' ), false );

		mw.storage.store.setItem.throws();
		assert.strictEqual( mw.storage.set( 'foo', 'test' ), false );

		mw.storage.store.removeItem.throws();
		assert.strictEqual( mw.storage.remove( 'foo', 'test' ), false );
	} );

}( mediaWiki ) );
