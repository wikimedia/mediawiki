( function ( mw, $ ) {

	var NOW = 9012, // miliseconds
		DEFAULT_DURATION = 5678, // seconds
		expiryDate = new Date();

	expiryDate.setTime( NOW + ( DEFAULT_DURATION * 1000 ) );

	QUnit.module( 'mediawiki.cookie', QUnit.newMwEnvironment( {
		setup: function () {
			this.stub( $, 'cookie' ).returns( null );

			this.sandbox.useFakeTimers( NOW );
		},
		config: {
			wgCookiePrefix: 'mywiki',
			wgCookieDomain: 'example.org',
			wgCookiePath: '/path',
			wgCookieExpiration: DEFAULT_DURATION
		}
	} ) );

	QUnit.test( 'set( key, value )', 3, function ( assert ) {
		var call;

		// Simple case
		mw.cookie.set( 'foo', 'bar' );

		call = $.cookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'mywikifoo' );
		assert.strictEqual( call[ 1 ], 'bar' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'example.org',
			path: '/path',
			secure: false
		} );
	} );

	QUnit.test( 'set( key, value, options )', 3, function ( assert ) {
		var call;

		mw.cookie.set( 'foo', 'bar', {
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		call = $.cookie.lastCall.args;
		assert.strictEqual( call[0], 'myPrefixfoo' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		mw.cookie.set( 'foo', 'bar', {
			invalid: true
		} );

		call = $.cookie.lastCall.args;
		assert.strictEqual( call[ 2 ].invalid, undefined, 'Invalid options are not passed through' );
	} );

	QUnit.test( 'set( key, value, expires )', 3, function ( assert ) {
		var date, call;

		date = new Date();
		date.setTime( 1234 );

		mw.cookie.set( 'foo', 'bar', date );
		call = $.cookie.lastCall.args;
		assert.strictEqual( call[ 2 ].expires, date, 'Custom date' );

		mw.cookie.set( 'foo', 'bar', null );
		call = $.cookie.lastCall.args;
		assert.strictEqual( call[ 2 ].expires, undefined, 'Expiry null creates session cookie' );

		mw.cookie.set( 'foo', 'bar', false );
		call = $.cookie.lastCall.args;
		assert.strictEqual( call[ 2 ].expires, undefined, 'Expiry false creates session cookie' );
	} );

	QUnit.test( 'set( key, "" )', 1, function ( assert ) {
		var value;

		mw.cookie.set( 'foo', '' );

		value = $.cookie.lastCall.args[ 1 ];
		assert.strictEqual( value, null, 'Empty string as value is the same as no value' );
	} );

	QUnit.test( 'get( key ) - no values', 6, function ( assert ) {
		var key, value;

		mw.cookie.get( 'foo' );

		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Default prefix' );

		mw.cookie.get( 'foo', undefined );
		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for undefined' );

		mw.cookie.get( 'foo', null );
		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for null' );

		mw.cookie.get( 'foo', '' );
		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'foo', 'Don\'t use default prefix for empty string' );

		value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, null, 'Return null by default' );

		value = mw.cookie.get( 'foo', null, 'bar' );
		assert.strictEqual( value, 'bar', 'Custom default value' );
	} );

	QUnit.test( 'get( key ) - with value', 1, function ( assert ) {
		var value;

		$.cookie.returns( 'bar' );

		value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, 'bar', 'Return value of cookie' );
	} );

	QUnit.test( 'get( key, prefix )', 1, function ( assert ) {
		var key;

		mw.cookie.get( 'foo', 'bar' );

		key = $.cookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'barfoo' );
	} );

} ( mediaWiki, jQuery ) );
