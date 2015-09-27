( function ( mw ) {
	QUnit.module( 'mediawiki.storage: normal case.', {
		setup: function () {
			this.sandbox.stub( mw.storage, 'isLocalStorageSupported', true );
			this.spy = this.sandbox.spy( localStorage, 'setItem' );
			this.sandbox.stub( localStorage, 'getItem' )
				.withArgs( 'foo' ).returns( 'test' )
				.withArgs( 'bar' ).returns( null );
		}
	} );

	QUnit.test( 'set/get with localStorage', 4, function ( assert ) {
		mw.storage.set( 'foo', 'test' );
		assert.strictEqual( this.spy.calledOnce, true, 'Check localStorage called.' );
		assert.strictEqual( this.spy.calledWith( 'foo', 'test' ), true,
			'Check prefixed.' );
		assert.strictEqual( mw.storage.get( 'foo' ), 'test', 'Check value gets stored.' );
		assert.strictEqual( mw.storage.get( 'bar' ), null, 'Unset values are null.' );
	} );

	QUnit.module( 'mediawiki.storage: localStorage does not exist', {
		setup: function () {
			this.sandbox.stub( mw.storage, 'isLocalStorageSupported', false );
			this.sandbox.stub( localStorage, 'setItem' ).throws();
		}
	} );

	QUnit.test( 'set/get without localStorage', 3, function ( assert ) {
		assert.strictEqual( mw.storage.set( 'foo', 'test' ), false,
			'When localStorage not available save fails.' );

		assert.strictEqual( mw.storage.remove( 'foo', 'test' ), false,
			'When localStorage not available remove fails.' );

		assert.strictEqual( mw.storage.get( 'foo' ), false,
				'Inability to retrieve values return false to differentiate from null (not set).' );
	} );

	QUnit.module( 'mediawiki.storage: localStorage exhausted', {
		setup: function () {
			this.sandbox.stub( mw.storage, 'isLocalStorageSupported', true );
			this.sandbox.stub( localStorage, 'setItem' ).throws();
			this.sandbox.stub( localStorage, 'getItem' ).returns( null );
		}
	} );

	QUnit.test( 'set/get without localStorage', 2, function ( assert ) {
		assert.strictEqual( mw.storage.set( 'foo', 'test' ), false,
			'When localStorage not available inform user with false.' );
		assert.strictEqual( mw.storage.get( 'foo' ), null, 'No value registered.' );
	} );

}( mediaWiki ) );
