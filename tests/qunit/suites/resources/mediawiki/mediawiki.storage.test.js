( function ( mw ) {
	QUnit.module( 'mediawiki.storage' );

	QUnit.test( 'set/get with localStorage', 3, function ( assert ) {
		this.sandbox.stub( mw.storage, 'localStorage', {
			setItem: this.sandbox.spy(),
			getItem: this.sandbox.stub()
		} );

		mw.storage.set( 'foo', 'test' );
		assert.ok( mw.storage.localStorage.setItem.calledOnce );

		mw.storage.localStorage.getItem.withArgs( 'foo' ).returns( 'test' );
		mw.storage.localStorage.getItem.returns( null );
		assert.strictEqual( mw.storage.get( 'foo' ), 'test', 'Check value gets stored.' );
		assert.strictEqual( mw.storage.get( 'bar' ), null, 'Unset values are null.' );
	} );

	QUnit.test( 'set/get without localStorage', 3, function ( assert ) {
		this.sandbox.stub( mw.storage, 'localStorage', {
			getItem: this.sandbox.stub(),
			removeItem: this.sandbox.stub(),
			setItem: this.sandbox.stub()
		} );

		mw.storage.localStorage.getItem.throws();
		assert.strictEqual( mw.storage.get( 'foo' ), false );

		mw.storage.localStorage.setItem.throws();
		assert.strictEqual( mw.storage.set( 'foo', 'test' ), false );

		mw.storage.localStorage.removeItem.throws();
		assert.strictEqual( mw.storage.remove( 'foo', 'test' ), false );
	} );

}( mediaWiki ) );
