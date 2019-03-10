( function () {

	var NOW = 9012, // miliseconds
		DEFAULT_DURATION = 5678, // seconds
		jqcookie,
		defaults = {
			prefix: 'mywiki',
			domain: 'example.org',
			path: '/path',
			expires: DEFAULT_DURATION,
			secure: false
		},
		setDefaults = require( 'mediawiki.cookie' ).setDefaults,
		expiryDate = new Date();

	expiryDate.setTime( NOW + ( DEFAULT_DURATION * 1000 ) );

	QUnit.module( 'mediawiki.cookie', {
		beforeEach: function () {
			jqcookie = sinon.stub( $, 'cookie' ).returns( null );
			this.clock = sinon.useFakeTimers( NOW );
			this.savedDefaults = setDefaults( defaults );
		},
		afterEach: function () {
			jqcookie.restore();
			this.clock.restore();
			setDefaults( this.savedDefaults );
		}
	} );

	QUnit.test( 'set( key, value )', function ( assert ) {
		var call;

		// Simple case
		mw.cookie.set( 'foo', 'bar' );

		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'mywikifoo' );
		assert.strictEqual( call[ 1 ], 'bar' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'example.org',
			path: '/path',
			secure: false
		} );

		mw.cookie.set( 'foo', null );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], null, 'null removes cookie' );

		mw.cookie.set( 'foo', undefined );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], 'undefined', 'undefined is value' );

		mw.cookie.set( 'foo', false );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], 'false', 'false is a value' );

		mw.cookie.set( 'foo', 0 );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], '0', '0 is value' );
	} );

	QUnit.test( 'set( key, value, expires )', function ( assert ) {
		var date, options;

		date = new Date();
		date.setTime( 1234 );

		mw.cookie.set( 'foo', 'bar' );
		options = jqcookie.lastCall.args[ 2 ];
		assert.deepEqual( options.expires, expiryDate, 'default expiration' );

		mw.cookie.set( 'foo', 'bar', date );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, date, 'custom expiration as Date' );

		date = new Date();
		date.setDate( date.getDate() + 1 );

		mw.cookie.set( 'foo', 'bar', 86400 );
		options = jqcookie.lastCall.args[ 2 ];
		assert.deepEqual( options.expires, date, 'custom expiration as lifetime in seconds' );

		mw.cookie.set( 'foo', 'bar', null );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, undefined, 'null forces session cookie' );

		// Per DefaultSettings.php, if wgCookieExpiration is 0,
		// then the default should be session cookies
		setDefaults( $.extend( {}, defaults, { expires: 0 } ) );

		mw.cookie.set( 'foo', 'bar' );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, undefined, 'wgCookieExpiration=0 results in session cookies by default' );

		mw.cookie.set( 'foo', 'bar', date );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, date, 'custom expiration (with wgCookieExpiration=0)' );
	} );

	QUnit.test( 'set( key, value, options )', function ( assert ) {
		var date, call;

		mw.cookie.set( 'foo', 'bar', {
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'myPrefixfoo' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		}, 'Options (without expires)' );

		date = new Date();
		date.setTime( 1234 );

		mw.cookie.set( 'foo', 'bar', {
			expires: date,
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'myPrefixfoo' );
		assert.deepEqual( call[ 2 ], {
			expires: date,
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		}, 'Options (incl. expires)' );
	} );

	QUnit.test( 'get( key ) - no values', function ( assert ) {
		var key, value;

		mw.cookie.get( 'foo' );

		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Default prefix' );

		mw.cookie.get( 'foo', undefined );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for undefined' );

		mw.cookie.get( 'foo', null );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for null' );

		mw.cookie.get( 'foo', '' );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'foo', 'Don\'t use default prefix for empty string' );

		value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, null, 'Return null by default' );

		value = mw.cookie.get( 'foo', null, 'bar' );
		assert.strictEqual( value, 'bar', 'Custom default value' );
	} );

	QUnit.test( 'get( key ) - with value', function ( assert ) {
		var value;

		jqcookie.returns( 'bar' );

		value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, 'bar', 'Return value of cookie' );
	} );

	QUnit.test( 'get( key, prefix )', function ( assert ) {
		var key;

		mw.cookie.get( 'foo', 'bar' );

		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'barfoo' );
	} );

}() );
