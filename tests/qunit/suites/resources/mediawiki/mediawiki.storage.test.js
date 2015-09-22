( function ( mw ) {
	QUnit.module( 'mediawiki.storage', {
		teardown: function () {
			try {
				for ( var i = 0; i < localStorage.length; i++ ) {
					if ( localStorage.key( i ).indexOf( 'test' ) === 0 ) {
						localStorage.removeItem( localStorage.key( i ) );
					}
				}
			} catch ( e ) {}
		}
	} );

	QUnit.test( 'set/get with localStorage', 4, function ( assert ) {
		mw.storage.set( 'test-foo', 'example' );
		assert.strictEqual( mw.storage.get( 'test-foo' ), 'test', 'Check value gets stored.' );
		assert.strictEqual( mw.storage.get( 'test-bar' ), null, 'Unset values are null.' );
	} );

	QUnit.test( 'set/get without localStorage', 3, function ( assert ) {
		this.sandbox.stub( mw.storage, 'isLocalStorageSupported', false );
		this.sandbox.stub( localStorage, 'setItem' ).throws();

		assert.strictEqual( mw.storage.set( 'test-foo', 'test' ), false,
			'When localStorage not available save fails.'
		);

		assert.strictEqual( mw.storage.remove( 'test-foo', 'test' ), false,
			'When localStorage not available remove fails.'
		);

		assert.strictEqual( mw.storage.get( 'test-foo' ), false,
			'Inability to retrieve values return false to differentiate from null (not set).'
		);
	} );

	QUnit.test( 'set/get without localStorage', 2, function ( assert ) {
		this.sandbox.stub( mw.storage, 'isLocalStorageSupported', true );
		this.sandbox.stub( localStorage, 'setItem' ).throws();
		this.sandbox.stub( localStorage, 'getItem' ).returns( null );

		assert.strictEqual( mw.storage.set( 'test-foo', 'test' ), false,
			'When localStorage not available inform user with false.'
		);
		assert.strictEqual( mw.storage.get( 'test-foo' ), null, 'No value registered.' );
	} );

}( mediaWiki ) );
